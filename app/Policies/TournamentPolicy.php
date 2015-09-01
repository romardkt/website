<?php

namespace Cupa\Policies;

use Cupa\User;
use Cupa\Tournament;

class TournamentPolicy
{
    protected $globalPerms = ['admin', 'manager'];

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }

    public function show(User $user, Tournament $tournament)
    {
        if (in_array($user->roles()->first()->name, $this->globalPerms)) {
            return true;
        }

        return $tournament->contacts->contains('user_id', $user->id);
    }
}
