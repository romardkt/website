<?php

namespace Cupa\Http\Controllers;

use Exception;
use Cupa\CupaForm;
use Cupa\Http\Requests\ContactRequest;
use Cupa\Http\Requests\LocationAddRequest;
use Cupa\Http\Requests\PageEditRequest;
use Cupa\Http\Requests\WaiverRequest;
use Cupa\League;
use Cupa\LeagueMember;
use Cupa\Location;
use Cupa\Page;
use Cupa\Paypal;
use Cupa\PaypalPayment;
use Cupa\Pickup;
use Cupa\Post;
use Cupa\Tournament;
use Cupa\TournamentTeam;
use Cupa\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class PageController extends Controller
{
    public function home()
    {
        $posts = Post::fetchPostsForHomePage(8);
        $featured = Post::fetchPostsForHomePage(null, true);
        $leagues = League::fetchAllLeaguesForHomePage();
        $tournaments = Tournament::fetchAllCurrent();
        $pickups = Pickup::fetchAllPickups(true);

        return view('page.home', compact('posts', 'featured', 'leagues', 'tournaments', 'pickups'));
    }

    public function contact()
    {
        return view('page.contact');
    }

    public function postContact(ContactRequest $request)
    {
        $contactInformation = $request->all();

        Mail::send('emails.contact', array('data' => $contactInformation), function ($m) use ($contactInformation) {
            if (App::environment() == 'prod') {
                $m->to('webmaster@cincyultimate.org', 'CUPA Webmaster')
                  ->to('cincyultimate@gmail.com', 'CUPA Contact');
            } else {
                $m->to('kcin1018@gmail.com');
            }

            $m->subject($contactInformation['subject'])
              ->replyTo($contactInformation['from_email'], $contactInformation['from_name']);
        });

        Session::flash('msg-success', 'Message has been sent');

        return redirect()->route('contact');
    }

    public function locationAdd(LocationAddRequest $request)
    {
        $input = $request->all();

        $location = Location::fetchOrCreateLocation($input);
        if ($location) {
            return response()->json(['status' => 'ok', 'name' => $location->name, 'value' => $location->id]);
        }

        return response()->json(['status' => 'error', 'message' => 'Unkown Error']);
    }

    public function paypal($id, $type, $memberId = null, $teamId = null)
    {
        if (empty($memberId) && $type == 'league') {
            Session::flash('msg-error', 'You must be logged in to pay for a league');
            $league = League::find($id);

            return redirect()->route('league_success', [$league->slug]);
        }

        $paypal = Paypal::create([
            'league_member_id' => (empty($memberId)) ? null : $memberId,
            'type' => $type,
            'league_id' => ($type == 'league') ? $id : null,
            'tournament_id' => ($type == 'tournament') ? $id : null,
            'tournament_team_id' => ($type == 'tournament') ? $teamId : null,
        ]);

        $title = null;
        $redirect = route('home');
        $cost = 0;
        switch ($type) {
            case 'tournament':
                $tournament = Tournament::find($id);
                $title = $tournament->display_name;
                $cost = $tournament->cost;
                $redirect = route('tournament_payment', [$tournament->name, $tournament->year]);
                break;
            case 'league':
                $league = League::find($id);
                $title = $league->displayName();
                $cost = $league->registration->cost;
                $redirect = route('league_success', [$league->slug]);
                break;
        }

        if ($cost == 0) {
            Session::flash('msg-error', 'Paypal cost was not set');

            return redirect()->to($redirect);
        }

        $paypalPayment = $this->_paypalPayment($paypal->id);
        $paypalPayment->description = $title.' - $'.$cost;
        $paypalPayment->amount_total = $cost;
        $paypalPayment->set_express_checkout();

        if (!$paypalPayment->_error) {
            $paypal->state = 'created';
            $paypal->token = $paypalPayment->token;
            $paypal->save();

            Session::put('TOKEN', $paypalPayment->token);
            $paypalPayment->set_express_checkout_successful_redirect();
            exit(1);
        } else {
            $paypal->state = 'error';
            $paypal->data = print_r($paypalPayment->Error, true);
            $paypal->save();

            Log::info(print_r($paypalPayment, true));
        }

        Session::flash('msg-error', 'Could not pay with paypal');

        return redirect()->to($redirect);

        /*
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $item->setName($title . ' - $' . $cost)
             ->setCurrency('USD')
             ->setQuantity(1)
             ->setPrice($cost);

        $itemList = new ItemList();
        $itemList->setItems(array($item));

        $amount = new Amount();
        $amount->setCurrency('USD')
               ->setTotal($cost);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
                    ->setItemList($itemList)
                    ->setDescription(ucfirst($type) . ' payment for ' . $title);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('paypal_status', [$paypal->id]))
                     ->setCancelUrl(route('paypal_status', [$paypal->id]));

        $payment = new Payment();
        $payment->setIntent('sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions(array($transaction));

        try {
            $apiContext = paypalApiContext();
            $payment->create($apiContext);
        } catch (PayPal\Exception\PPConnectionException $ex) {
            Session::flash('msg-error', 'Could not pay with paypal');
            Log::error("Paypal Exception: " . $ex->getMessage());
            Log::error(print_r($ex->getData(), true));
            $paypal->delete();

            return redirect()->to($redirect);
        } catch (Exception $ex) {
            Session::flash('msg-error', 'System error trying to pay with paypal');
            Log::error("Paypal Exception: " . $ex->getMessage());
            $paypal->delete();

            return redirect()->to($redirect);
        }

        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirectUrl = $link->getHref();
                break;
            }
        }

        $paypal->payment_id = $payment->getId();
        $paypal->state = $payment->getState();
        $paypal->save();

        Session::put('paymentId', $paypal->payment_id);

        if (isset($redirectUrl)) {
            return redirect()->to($redirectUrl);
        }
        */
    }

    private function _paypalPayment($paypalId)
    {
        $paypalConfig = Config::get('cupa.paypal');
        $server = (App::environment() == 'prod') ? 'https://cincyultimate.org' : Config::get('app.url');
        $paypalConfig['return_url'] = $server.'/paypal/success/'.$paypalId;
        $paypalConfig['cancel_url'] = $server.'/paypal/fail/'.$paypalId;
        $paypalConfig['use_proxy'] = null;
        $paypalConfig['proxy_host'] = null;
        $paypalConfig['proxy_port'] = null;

        return new PaypalPayment($paypalConfig, (App::environment() == 'prod') ? false : true);
    }

    public function paypalSuccess(Request $request, Paypal $paypal)
    {
        //$paypal = Paypal::find($paypalId);
        $paypalPayment = $this->_paypalPayment($paypal->id);

        $paypal->state = 'approved';
        $paypal->save();

        $paypalPayment->token = $request->get('token');
        if ($paypalPayment->get_express_checkout_details()) {
            $data = [];
            $data['confirm'] = json_encode($paypalPayment->Response);
            $paypal->payer_id = $paypalPayment->Response['PAYERID'];
            $paypal->save();

            $paypalPayment->amount_total = $paypalPayment->Response['AMT'];
            if ($paypalPayment->do_express_checkout_payment()) {
                $data['complete'] = json_encode($paypalPayment->Response);
                $paypal->payment_id = $paypalPayment->Response['TRANSACTIONID'];
                $paypal->state = 'completed';
                $paypal->success = 1;
                $paypal->save();

                Session::flash('msg-success', 'Paypal payment received and applied');
            }
            $paypal->data = implode('::', $data);
            $paypal->save();
        } else {
            $paypal->state = 'error';
            $paypal->data = print_r($paypalPayment->Error, true);
            $paypal->save();
        }

        if ($paypal->tournament_team_id !== null) {
            // redirect to tournament
            $tournament = Tournament::find($paypal->tournament_id);

            $tournamentTeam = TournamentTeam::find($paypal->tournament_team_id);
            $tournamentTeam->paid = 1;
            $tournamentTeam->save();

            Session::flash('msg-success', 'Paypal payment received and applied for '.$tournamentTeam->name);

            return redirect()->route('tournament_payment', [$tournament->name, $tournament->year]);
        } else {
            // redirect to league
            $league = League::find($paypal->league_id);

            $leagueMember = LeagueMember::find($paypal->league_member_id);
            if ($league->default_waitlist) {
                $leagueMember->position = 'player';
            }
            $leagueMember->paid = 1;
            $leagueMember->save();

            return redirect()->route('league_success', [$league->slug]);
        }
    }

    public function paypalFail(Paypal $paypal)
    {
        //$paypal = Paypal::find($paypalId);
        $paypal->state = 'cancelled';
        $paypal->save();

        Session::flash('msg-error', 'Paypal payment was cancelled.');

        if ($paypal->tournament_team_id !== null) {
            // redirect to tournament
            $tournament = Tournament::find($paypal->tournament_id);

            return redirect()->route('tournament_payment', [$tournament->name, $tournament->year]);
        } else {
            // redirect to league
            $league = League::find($paypal->league_id);

            return redirect()->route('league_success', [$league->slug]);
        }
    }

/*
    public function paypal_status($paypalId)
    {
        $paypal = Paypal::find($paypalId);

        $payerId = $request->get('PayerID');
        $paypal->token = $request->get('token');
        $paypal->payer_id = $payerId;

        if (isset($payerId)) {
            $apiContext = paypalApiContext();
            $result = paypalExecutePayment(Session::get('paymentId'), $payerId);
            $paypal->data = json_encode($result);
            if (!isset($result->state)) {
                Session::flash('msg-error', 'Paypal payment was canceled or not completed');
            } else {
                $paypal->state = $result->state;
                if ($paypal->state == 'approved') {
                    $paypal->success = 1;
                    if ($paypal->tournament_id !== null) {
                        $team = TournamentTeam::find($paypal->tournament_team_id);
                        $team->paid = 1;
                        $team->save();
                    } else {
                        $member = LeagueMember::find($paypal->league_member_id);
                        $member->paid = 1;
                        $member->save();
                    }
                }
            }
        } else {
            Session::flash('msg-error', 'Paypal payment was canceled or not completed');

            if ($paypal->league_id === null) {
                $tournament = Tournament::find($paypal->tournament_id);

                return redirect()->route('tournament_payment', [$tournament->name, $tournament->year]);
            } else {
                $league = League::find($paypal->league_id);

                return redirect()->route('league_success', [$league->slug]);
            }
        }

        $paypal->save();

        if ($paypal->league_id === null) {
            Session::flash('msg-success', 'Your tournament payment has been received');
            $tournament = Tournament::find($paypal->tournament_id);

            return redirect()->route('tournament_payment', [$tournament->name, $tournament->year]);
        } else {
            Session::flash('msg-success', 'Your league payment has been received');
            $league = League::find($paypal->league_id);

            return redirect()->route('league_success', [$league->slug]);
        }
    }
    */
    public function dayton()
    {
        $page = Page::fetchBy('route', 'leagues_dayton');

        return view('page.dayton', compact('page'));
    }

    public function daytonEdit()
    {
        $page = Page::fetchBy('route', 'leagues_dayton');

        return view('page.dayton_edit', compact('page'));
    }

    public function postDaytonEdit(PageEditRequest $request)
    {
        // get the posted data
        $input = $request->all();

        $page = Page::fetchBy('route', 'leagues_dayton');
        $page->display = $input['display'];
        $page->content = $input['content'];
        $page->save();

        Session::flash('msg-success', $page->display.' updated.');

        return redirect()->route('leagues_dayton');
    }

    public function waiver($year, $user = null)
    {
        $redirect = (Session::has('waiver_redirect')) ?  Session::get('waiver_redirect') : route('home');
        if (empty($user)) {
            $user = Auth::user();
        }

        if (!$user || Auth::guest()) {
            Session::flash('msg-error', 'Please login to sign a waiver');

            return redirect()->to($redirect);
        }

        if (is_numeric($user)) {
            $user = User::find($user);
        }

        if ($user->hasWaiver($year)) {
            Session::flash('msg-error', 'You have already signed a waiver for the '.$year.' year');

            return redirect()->to($redirect);
        }

        if ($user->getAge() < 18) {
            Session::flash('msg-error', 'Player is too young to sign a waiver.');

            return redirect()->to($redirect);
        }

        return view('page.waiver', compact('user', 'year'));
    }

    public function postWaiver(WaiverRequest $request, $year, $user = null)
    {
        if (empty($user)) {
            $user = Auth::user();
        }

        $redirect = (Session::has('waiver_redirect')) ?  Session::get('waiver_redirect') : route('home');
        $input = $request->all();
        $user->signWaiver($year);
        Session::flash('msg-success', 'Waiver signed for the '.$year.' year');
        Session::forget('waiver_redirect');

        return redirect()->to($redirect);
    }

    public function waiverDownload($year, $type = null)
    {
        $form = CupaForm::fetchWaiver($year, $type);
        if ($form) {
            return response()->download(public_path().$form->location);
        }

        Session::flash('msg-error', 'Could not find form to download');

        return redirect()->to(Session::get('waiver_redirect'));
    }
}
