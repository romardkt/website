<?php

namespace Cupa\Policies;

class TeamPolicy
{
    protected $globalPerms = ['admin', 'manager', 'editor'];
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    private function isAuthorized(User $user, Team $team)
    {
        $roles = $user->roles();
        if ($roles->count() > 0 && in_array($roles->first()->role->name, $this->globalPerms)) {
            return true;
        }

        return $team->captians()->contains('user_id', $user->id);
    }
}
