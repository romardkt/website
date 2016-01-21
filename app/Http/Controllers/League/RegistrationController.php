<?php

namespace Cupa\Http\Controllers\League;

use Cupa\Http\Controllers\Controller;
use Cupa\Http\Requests\LeagueRegistrationRequest;
use Cupa\Http\Requests\LeagueRegistrationSuccessRequest;
use Cupa\League;
use Cupa\LeagueMember;
use Cupa\User;
use Cupa\UserBalance;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use stdClass;

class RegistrationController extends Controller
{
    public function register(LeagueRegistrationRequest $request, $slug, $state = 'who')
    {
        if ($state == 'who') {
            Session::set('league_registration', new stdClass());
        }
        $session = Session::get('league_registration');

        $league = League::fetchBySlug($slug);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirect()->route('leagues');
        }

        switch ($state) {
            case 'info':
                if (!isset($session->registrant)) {
                    return redirect()->route('league_register', [$league->slug, 'who']);
                }
                break;
            case 'contacts':
                if (!isset($session->info)) {
                    return redirect()->route('league_register', [$league->slug, 'who']);
                }
                break;
            case 'league':
                if (!isset($session->contacts)) {
                    return redirect()->route('league_register', [$league->slug, 'who']);
                }
                break;
            case 'finish':
                if (!isset($session->league)) {
                    return redirect()->route('league_register', [$league->slug, 'who']);
                }
                break;
        }

        $user = User::with(['profile', 'parentObj', 'parentObj.profile'])->find($session->registrant->id);

        if ($state != 'who') {
            if ($user->isLeagueMember($league->id)) {
                Session::flash('msg-error', $user->fullname().' is already registered for this league.');

                return redirect()->route('league_success', [$league->slug]);
            }
        }

        $type = $league->fetchRegistrationType($session);
        if ($type == 'full') {
            Session::flash('msg-error', 'League is full and not allowing wait listed players');

            return redirect()->route('league', [$league->slug]);
        }

        // calculate contacts
        $contacts = ($user->parentObj == null) ? $user->contacts : $user->parentObj->contacts;

        if ($request->method() == 'GET') {
            return view('leagues.registration.register', compact('league', 'state', 'session', 'type', 'contacts'));
        } elseif ($state != 'contacts') {
            return $this->{'register'.ucfirst($state)}($league, $request);
        } else {
            return $this->{'register'.ucfirst($state)}($league, $request, $contacts);
        }
    }

    private function registerWho($league, LeagueRegistrationRequest $request)
    {
        $session = Session::get('league_registration');
        $input = $request->all();

        if (UserBalance::owesMoney(Auth::id())) {
            Session::flash('owe', 'You owe money');

            return redirect()->route('league_register', [$league->slug, 'who']);
        }

        $user = User::with(['profile', 'parentObj', 'parentObj.profile'])->find($input['user']);
        $session->registrant = $user;

        return redirect()->route('league_register', [$league->slug, 'info']);
    }

    private function registerInfo($league, $request)
    {
        $session = Session::get('league_registration');
        $input = $request->all();
        $input['birthday'] = convertDate($input['birthday']);
        $session->info = $input;

        return redirect()->route('league_register', [$league->slug, 'contacts']);
    }

    private function registerContacts($league, $request, $contacts)
    {
        $session = Session::get('league_registration');

        if (count($contacts) < 2) {
            Session::flash('msg-error', 'You must enter at least 2 contacts');

            return redirect()->route('league_register', [$league->slug, 'contacts']);
        }

        $session->contacts = true;

        return redirect()->route('league_register', [$league->slug, 'league']);
    }

    private function registerLeague($league, $request)
    {
        $session = Session::get('league_registration');

        $input = $request->all();
        unset($input['_token']);
        unset($input['_url']);

        $session->league = json_encode($input);

        return redirect()->route('league_register', [$league->slug, 'finish']);
    }

    private function registerFinish($league, $request)
    {
        $session = Session::get('league_registration');
        $position = ($league->fetchRegistrationType($session) == 'registration') ? 'player' : 'waitlist';
        $position = ($league->default_waitlist) ? 'waitlist' : 'player';

        Log::info('*******************Registrant:');
        Log::info(print_r($session->registrant->toArray(), true));
        Log::info('*******************Info:');
        Log::info(print_r($session->info, true));
        Log::info('*******************Contacts:');
        Log::info(print_r($session->contacts, true));
        Log::info('*******************League:');
        Log::info(print_r($session->league, true));

        $user = User::find($session->registrant->id);
        foreach ($session->info as $key => $value) {
            if ($user->parent !== null && $key == 'email') {
                $user->parentObj->email = $value;
                $user->parentObj->save();
            } elseif ($user->parent !== null && $key == 'phone') {
                $user->parentObj->profile->phone = $value;
                $user->parentObj->profile->save();
            } else {
                if (in_array($key, ['email', 'first_name', 'last_name', 'birthday', 'gender'])) {
                    $user->$key = $value;
                } elseif (in_array($key, ['phone', 'nickname', 'height', 'level', 'experience'])) {
                    $user->profile->$key = $value;
                }
            }
        }

        $user->save();
        $user->profile->save();

        LeagueMember::create([
            'league_id' => $league->id,
            'user_id' => $session->registrant->id,
            'position' => $position,
            'answers' => $session->league,
            'updated_by' => Auth::id(),
        ]);

        $data = [
            'userName' => $user->fullname(),
            'leagueName' => $league->displayName(),
            'leagueSlug' => $league->slug,
            'leagueStatus' => ($position == 'player') ? 'Registration' : 'Waitlist',
        ];

        Mail::send('emails.league_register', $data, function ($m) use ($user, $league) {
            if (App::environment() == 'prod') {
                if (empty($user->email)) {
                    $m->to($user->parentObj->email, $user->fullname())->subject('['.$league->displayName().'] Registration');
                } else {
                    $m->to($user->email, $user->fullname())->subject('['.$league->displayName().'] Registration');
                }
            } else {
                $m->to('kcin1018@gmail.com', 'Nick Felicelli')->subject('['.$league->displayName().'] Registration');
            }
        });

        Session::forget('league_registration');

        if (!$user->hasWaiver($league->year)) {
            if ($user->getAge() >= 18) {
                Session::flash('msg-success', 'Registration finished, please fill out a waiver and pay for the league.');
                Session::set('waiver_redirect', route('league_success', [$league->slug]));

                return redirect()->route('waiver', [$league->year, $user->id]);
            } else {
                Session::flash('msg-success', 'Registration finished, you still need to print/fill out the waiver and pay for the league.');
            }
        } elseif ($league->default_waitlist) {
            Session::flash('msg-success', 'Registration finished, you still need to pay for the league to be added to the league and removed from the waitlist.');
        } else {
            Session::flash('msg-success', 'Registration finished, you still need to pay for the league.');
        }

        return redirect()->route('league_success', [$league->slug]);
    }

    public function success($slug)
    {
        $league = League::fetchBySlug($slug);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirect()->route('leagues');
        }

        Session::set('waiver_redirect', route('league_success', [$league->slug]));

        $players = User::fetchAllPlayers($league->id, Auth::id());
        if (count($players) < 1) {
            Session::flash('msg-error', 'You have not registered for this league yet');

            return redirect()->route('league_register', [$league->slug, 'who']);
        }

        return view('leagues.registration.success', compact('league', 'players'));
    }

    public function postSuccess($slug, LeagueRegistrationSuccessRequest $request)
    {
        $league = League::fetchBySlug($slug);
        $input = $request->all();

        return redirect()->route('paypal', [$league->id, 'league', $input['player']]);
    }
}
