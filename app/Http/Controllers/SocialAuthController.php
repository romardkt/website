<?php

namespace Cupa\Http\Controllers;

use Illuminate\Http\Request;
use Cupa\FacebookAccountService;
use Socialite;
use Session;
use Auth;
use Bugsnag;

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

            if (!$user->is_active) {
                throw new \Exception('Account Disabled: You '.$user->reason.', please email '.secureEmail('webmaster@cincyultimate.org', 'webmaster', '[CUPA] Account disabled'));
            }

            # log the user in
            Auth::login($user, true);
        } catch (\Exception $e) {
            Bugsnag::notifyException($e);

            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Could not login with Facebook at this time.';
            }
            Session::flash('msg-error', $e->getMessage());
        }

        return redirect()->to(Session::get('previous'));
    }
}
