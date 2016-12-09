<?php

namespace Cupa\Http\Controllers;

use Cupa\Page;
use Cupa\Post;
use Cupa\User;
use Cupa\League;
use Cupa\Paypal;
use Cupa\Pickup;
use Cupa\CupaForm;
use Cupa\Location;
use Cupa\LeagueGame;
use Cupa\Tournament;
use Cupa\LeagueMember;
use Cupa\PaypalPayment;
use Cupa\TournamentTeam;
use Cupa\UserMedicalRelease;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Cupa\Http\Requests\WaiverRequest;
use Cupa\Http\Requests\ContactRequest;
use Illuminate\Support\Facades\Config;
use Cupa\Http\Requests\PageEditRequest;
use Illuminate\Support\Facades\Session;
use Cupa\Http\Requests\LocationAddRequest;

class PageController extends Controller
{
    public function home()
    {
        $posts = Post::fetchPostsForHomePage(8);
        $featured = Post::fetchPostsForHomePage(null, true);
        $leagues = League::fetchAllLeaguesForHomePage();
        $tournaments = Tournament::fetchAllCurrent();
        $pickups = Pickup::fetchAllPickups(true);
        $leagueAlerts = LeagueGame::fetchAlerts();

        return view('page.home', compact('posts', 'featured', 'leagues', 'tournaments', 'pickups', 'leagueAlerts'));
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
            if (isset($paypalPayment->Response['PAYERID'])) {
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
            }
        } else {
            $paypal->state = 'error';
            $paypal->data = print_r($paypalPayment->Error, true);
            $paypal->save();
        }

        if ($paypal->tournament_team_id !== null) {
            // redirect to tournament
            $tournament = Tournament::findOrFail($paypal->tournament_id);

            $tournamentTeam = TournamentTeam::findOrFail($paypal->tournament_team_id);
            $tournamentTeam->paid = 1;
            $tournamentTeam->save();

            Session::flash('msg-success', 'Paypal payment received and applied for '.$tournamentTeam->name);

            return redirect()->route('tournament_payment', [$tournament->name, $tournament->year]);
        } else {
            // redirect to league
            $league = League::findOrFail($paypal->league_id);

            $leagueMember = LeagueMember::findOrFail($paypal->league_member_id);
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

    public function waiver($year, User $user = null)
    {
        $redirect = (Session::has('waiver_redirect')) ? Session::get('waiver_redirect') : route('home');
        if (empty($user)) {
            $user = Auth::user();
        }

        if (!$user || Auth::guest()) {
            Session::flash('msg-error', 'Please login to sign a waiver');

            return redirect()->to($redirect);
        }

        if ($user->hasWaiver($year)) {
            Session::flash('msg-error', 'You have already signed a waiver for the '.$year.' year');

            return redirect()->to($redirect);
        }

        $isYouth = $user->getAge() < 18;
        if ($isYouth) {
            return view('page.waiver_youth', compact('user', 'year', 'isYouth'));
        }

        return view('page.waiver', compact('user', 'year'));
    }

    public function postWaiver(WaiverRequest $request, $year, User $user = null)
    {
        $redirect = (Session::has('waiver_redirect')) ? Session::get('waiver_redirect') : route('home');

        if (empty($user)) {
            $user = Auth::user();
        }

        if ($user->getAge() < 18) {
            // check to see if the parent is logged in
            if ($user->parent != Auth::id()) {
                $errors = new MessageBag();
                $errors->add('fullname', 'You are not able to sign a waiver for this player.');

                return redirect()->route('waiver', [$year, $user->id])->withErrors($errors)->withInput();
            }

            // get the medical release data
            $input = $request->all();
            unset($input['_token']);
            $data = json_encode($input);

            UserMedicalRelease::updateOrCreateRelease($user, $year, Auth::id(), $data);
        }

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

    public function waiverExport($year, User $user)
    {
        if (!$user->hasWaiver($year)) {
            Session::flash('msg-error', 'You have not signed a waiver for the '.$year.' year');

            return redirect()->to(Session::get('waiver_redirect'));
        }

        // make sure the user can view the waiver
        $this->authorize('waiver', [$user, $year]);

        // calculate the age based on the date of waiver/medical release signed
        $release = $user->fetchRelease($year);
        $waiver = $user->fetchWaiver($year);
        if ($release) {
            // go by medical release datetime
            $signedDate = new \DateTime($release->updated_at->toDateTimeString());
        } else {
            // go by waiver datetime
            $signedDate = new \DateTime($waiver->updated_at->toDateTimeString());
        }

        // calculate age based on signed date
        $age = $user->getAge($signedDate);
        if ($age < 18 && $release) {
            // handle displaying youth waiver/medical release
            $release = $user->fetchRelease($year);
            $release->data = json_decode($release->data);

            return view('page.waiver_youth_export', compact('release'));
        }

        // handle adult waiver
        $waiver = $user->fetchWaiver($year);

        return view('page.waiver_export', compact('waiver', 'age'));
    }
}
