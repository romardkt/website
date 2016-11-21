<?php

namespace Cupa\Http\Controllers;

use Cupa\User;
use Carbon\Carbon;
use Cupa\EmailList;
use Cupa\Volunteer;
use Cupa\UserProfile;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\URL;
use Cupa\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Cupa\Http\Requests\UserRegisterRequest;
use Cupa\Http\Requests\PasswordResetRequest;
use Cupa\Http\Requests\PasswordDoResetRequest;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class AuthController extends Controller
{
    use ThrottlesLogins;

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
            'password' => Hash::make($registrationData['password']),
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
                'involvement' => 'Unknown',
                'primary_interest' => 'Unknown',
                'experience' => 'Unknown',
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

    public function activate($code)
    {
        // fetch user by code and check to see if its valid
        $user = User::fetchBy('activation_code', $code);
        if (!$user || $user->activated_at !== null) {
            if (!$user) {
                Session::flash('msg-error', 'The activation code that you used is not valid');
            } else {
                Session::flash('msg-error', 'You have already activated this account');
            }

            return redirect()->route('home');
        }

        // update the user information and activate account
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $user->is_active = 1;
        $user->reason = null;
        $user->activated_at = $now;
        $user->last_login_at = $now;
        $user->save();

        // email activation link to the user
        Mail::send('emails.welcome', [], function ($m) use ($user) {
            // send email to the user
            $m->to($user->email, $user->fullname())
                ->replyTo('webmaster@cincyultimate.org')
                ->subject('[CUPA] Welcome!');
        });

        // log user in and redirect to profile
        Auth::login($user, true);
        Session::flash('msg-success', 'You account has been activated.');

        return redirect()->route('profile');
    }

    public function reset()
    {
        return view('auth.reset');
    }

    public function postReset(PasswordResetRequest $request)
    {
        // find user by email and generate reset code
        $input = $request->all();
        $user = User::fetchBy('email', $input['email']);

        if ($user->is_active == 0) {
            Session::flash('status_error', 'Your account is disabled because you '.$user->reason.', please email '.secureEmail('webmaster@cincyultimate.org', 'webmaster', '[CUPA] Account disabled').' and they can help enable your account.');

            return redirect()->route('reset');
        }

        // send the email
        Mail::send('emails.reset', ['code' => $user->fetchPasswordResetCode(), 'email' => $input['email']], function ($m) use ($input, $user) {
            // send email to the user
            $m->to($input['email'], $user->fullname())
                ->replyTo('webmaster@cincyultimate.org')
                ->subject('[CUPA] Password Reset');
        });

        Session::flash('msg-success', 'Password reset link sent. Check your email for a message on resetting your password');

        return redirect()->route('reset');
    }

    public function doReset($code)
    {
        $user = User::fetchBy('reset_password_code', $code);
        if (!$user) {
            Session::flash('msg-error', 'Password reset code is not valid.  Please try requesting a new reset code');

            return redirect()->route('reset');
        }

        if (!$user->is_active) {
            Session::flash('msg-error', 'Your account is not active.');

            return redirect()->route('reset');
        }

        return view('auth.do_reset');
    }
    public function postDoReset($code, PasswordDoResetRequest $request)
    {
        $input = $request->all();
        $user = User::fetchBy('reset_password_code', $code);
        $user->password = Hash::make($input['password']);
        $user->reset_password_code = null;
        $user->last_reset_password_at = Carbon::now()->format('Y-m-d H:i:s');
        $user->reason = null;
        $user->save();

        // send the email
        Mail::send('emails.reset_confirm', [], function ($m) use ($user) {
            // send email to the user
            $m->to($user->email, $user->fullname())
                ->replyTo('webmaster@cincyultimate.org')
                ->subject('[CUPA] Password Reset Confirmation');
        });

        Auth::login($user, true);
        Session::flash('msg-success', 'Password has been reset to the entered password.');

        return redirect()->route('profile');
    }
}
