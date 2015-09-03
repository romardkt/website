<?php

namespace Cupa\Http\Controllers;

use Cupa\Http\Requests\LoginRequest;
use Cupa\Http\Requests\UserRegisterRequest;
use Illuminate\Support\MessageBag;
use Auth;
use Session;
use Mail;
use URL;
use Carbon\Carbon;
use Cupa\UserProfile;
use Cuap\EmailList;
use Cupa\Volunteer;
use Cupa\User;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        // get the posted data
        $credentials = $request->except(['_token', 'remember']);
        $rememberMe = $request->get('remember', 1);

        // try to login the user
        if (Auth::attempt($credentials, $rememberMe)) {
            // update the last login time
            $user = Auth::user();
            if ($user->is_active) {
                $user->last_login_at = Carbon::now()->format('Y-m-d H:i:s');
                $user->save();

                return response()->json(['status' => 'ok']);
            } else {
                Auth::logout();

                return response()->json(['status' => 'error', 'message' => 'Account Disabled: You '.$user->reason.', please email '.secureEmail('webmaster@cincyultimate.org', 'webmaster', '[CUPA] Account disabled').' and they can help enable your account.']);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Invalid email and/or password, you may reset your password <a href="'.URL::route('reset').'">here</a>']);
        }
    }

    public function logout()
    {
        Auth::logout();

        // log admin back in if user was impersonated
        if (Session::has('admin_user')) {
            Auth::login(Session::get('admin_user'), true);
            Session::forget('admin_user');
        }

        return response()->json(['status' => 'ok']);
    }

    public function register()
    {
        return view('auth.register');
    }

    public function postRegister(UserRegisterRequest $request)
    {
        // get the posted data
        $registrationData = $request->all();

        // check for duplicate user account
        $dupUser = User::checkForDuplicate($registrationData);
        if ($dupUser !== null) {
            $errors = new MessageBag();
            $errors->add('email', 'You already have an account.');
            Session::flash('msg-error', 'You already have an account with email `'.$dupUser->email.'`. You can reset that accounts password <a href = "'.URL::route('reset').'">here</a>.');

            return redirect()->route('register')->withInput()->withErrors($errors);
        }

        // create the user
        $user = User::create([
            'email' => $registrationData['email'],
            'first_name' => $registrationData['first_name'],
            'last_name' => $registrationData['last_name'],
            'birthday' => convertDate($registrationData['birthday'], 'Y-m-d'),
            'gender' => $registrationData['gender'],
            'password' => password_hash($registrationData['password'], PASSWORD_DEFAULT),
            'activation_code' => User::generateCode('activation_code'),
            'reason' => 'have not activated your account yet.',
        ]);

        $userProfile = UserProfile::create([
            'user_id' => $user->id,
        ]);

        // email admin to add to CUPA email list
        if (isset($registrationData['email_list']) && $registrationData['email_list'] == true) {
            EmailList::create([
                'email' => $user->email,
                'name' => $user->fullname(),
            ]);
        }

        // add to volunteer list
        if (isset($registrationData['volunteer_list']) && $registrationData['volunteer_list'] == true) {
            Volunteer::create([
                'user_id' => $user->id,
            ]);
        }

        // email activation link to the user
        Mail::send('emails.activate', ['code' => $user->activation_code], function ($m) use ($registrationData) {
            // send email to the user
            $m->to($registrationData['email'], $registrationData['first_name'].' '.$registrationData['last_name'])
              ->replyTo('webmaster@cincyultimate.org')
              ->subject('[CUPA] User Activation');
        });

        Session::flash('msg-success', 'User account created.  Please look for an email with the activation link to activate your account.');

        return redirect()->route('home');
    }
}
