<?php

namespace Cupa\Http\Controllers;

use Cupa\Http\Requests\LoginRequest;
use Auth;
use Session;
use Carbon\Carbon;

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
}
