<?php

namespace Cupa\Http\Controllers\League;

use stdClass;
use Cupa\Http\Controllers\Controller;
use Cupa\League;
use Cupa\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RegistrationController extends Controller
{
    public function register($slug, $state = 'who')
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

        if ($state != 'who') {
            $user = User::with(['profile', 'parentObj', 'parentObj.profile'])->find($session->registrant->id);
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

        return view('leagues.registration.register', compact('league', 'state', 'session', 'type'));
    }

    public function postRegister($slug, $state = 'who')
    {
        return $this->{'register_'.$state}($league);
    }

    private function registerWho($league, Request $request)
    {
        $session = Session::get('league_registration');

        $input = $request->all();

        $rules = [
            'user' => 'required|numeric',
        ];

        $messages = [
            'user.required' => 'Please select a player to register with',
        ];

        $validator = Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            return redirect()->route('league_register', [$league->slug, 'who'])->withInput()->withErrors($validator);
        }

        if (UserBalance::owesMoney(Auth::user()->id)) {
            Session::flash('owe', 'You owe money');

            return redirect()->route('league_register', [$league->slug, 'who']);
        }

        $user = User::with(['profile', 'parentObj', 'parentObj.profile'])->find($input['user']);
        $session->registrant = $user;

        return redirect()->route('league_register', [$league->slug, 'info']);
    }

    private function registerInfo($league)
    {
        $session = Session::get('league_registration');
        $input = $request->all();
        $userId = ($session->registrant->parent !== null) ? $session->registrant->parentObj->id : $session->registrant->id;

        $rules = [
            'email' => 'required|email|unique:users,email,'.$userId,
            'first_name' => 'required',
            'last_name' => 'required',
            'birthday' => 'required|date',
            'gender' => 'required',
            'phone' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
            'level' => 'required',
            'height' => 'required|integer|min:24|max:108',
            'experience' => 'required|integer|min:1950|max:'.date('Y'),
        ];

        $messages = [
            'phone' => 'Please enter a valid phone number',
        ];

        $validator = Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            return redirect()->route('league_register', [$league->slug, 'info'])->withInput()->withErrors($validator);
        }

        $input['birthday'] = convertDate($input['birthday']);

        $session->info = $input;

        return redirect()->route('league_register', [$league->slug, 'contacts']);
    }

    private function registerContacts($league)
    {
        $session = Session::get('league_registration');

        if (count($session->registrant->contacts()->get()) < 2) {
            Session::flash('msg-error', 'You must enter at least 2 contacts');

            return redirect()->route('league_register', [$league->slug, 'contacts']);
        }

        $session->contacts = true;

        return redirect()->route('league_register', [$league->slug, 'league']);
    }

    private function registerLeague($league)
    {
        $session = Session::get('league_registration');

        $input = $request->all();

        if ($league->user_teams) {
            $rules = ['user_teams' => 'required|not_in:0'];
        } else {
            $rules = [];
        }

        foreach (json_decode($league->registration()->first()->questions) as $i => $questionData) {
            list($questionId, $required) = explode('-', $questionData);
            $question = LeagueQuestion::find($questionId);
            $rules[$question->name] = ($required == 1) ? 'required' : '';
        }

        $messages = [
            'user_teams.not_in' => 'Please select a team',
        ];

        $validator = Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            return redirect()->route('league_register', [$league->slug, 'league'])->withInput()->withErrors($validator);
        }

        unset($input['_token']);
        unset($input['_url']);

        $session->league = json_encode($input);

        return redirect()->route('league_register', [$league->slug, 'finish']);
    }

    private function registerFinish($league)
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
            'updated_by' => View::shared('isAuthorized')['userData']->id,
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

        $players = User::fetchAllPlayers($league->id, Auth::user()->id);
        if (count($players) < 1) {
            Session::flash('msg-error', 'You have not registered for this league yet');

            return redirect()->route('league_register', [$league->slug, 'who']);
        }

        if (Request::method() == 'POST') {
            $input = $request->all();

            $rules = [
                'player' => 'required:not_in:0',
            ];

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return redirect()->route('league_success', [$league->slug])->withErrors($validator);
            }

            return redirect()->route('paypal', [$league->id, 'league', $input['player']]);
        }

        return view('leagues.success', compact('league', 'players'));
    }
}
