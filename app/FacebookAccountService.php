<?php

namespace Cupa;

use Cupa\Models\User;
use Cupa\Models\SocialAccount;
use Laravel\Socialite\Contracts\User as ProviderUser;

class FacebookAccountService
{
    public function getUser(ProviderUser $providerUser)
    {
        $account = SocialAccount::whereProvider('facebook')
            ->whereProviderUserId($providerUser->getId())
            ->first();

        if ($account) {
            // $this->updateAvatar($account->user, $providerUser->getAvatar());

            return $account->user;
        } else {
            $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => 'facebook'
            ]);

            $user = User::whereEmail($providerUser->getEmail())->first();


            if (!$user) {
                throw new \Exception('Could not find account for email `'.$providerUser->getEmail().'`');
            }

            // update the users' avatar
            // $this->updateAvatar($user, $providerUser->getAvatar());

            // update the user
            $account->user_id = $user->id;
            $account->save();

            return $user;
        }

    }

    private function updateAvatar($user, $avatar)
    {
        $user->avatar = $avatar;
        $user->save();
    }
}