<?php

namespace Cupa\Http\Controllers;

use Illuminate\Http\Request;
use Cupa\FacebookAccountService;
use Socialite;
use Session;
use Auth;

class SocialAuthController extends Controller
{
    public function facebookRedirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookCallback(FacebookAccountService $service)
    {
        try {
            // get the user from facebook response
            $facebookUser = Socialite::driver('facebook')->user();

            # get the CUPA user corrisponding to the facebook user
            $user = $service->getUser($facebookUser);

            # log the user in
            Auth::login($user, true);
        } catch (\Exception $e) {
            Session::flash('msg-error', 'Could not log you in with Facebook, please try again.');
        }

        return redirect()->to(Session::get('previous'));
    }
}
