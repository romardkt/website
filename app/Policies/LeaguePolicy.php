<?php

namespace Cupa\Policies;

use Cupa\User;
use Cupa\League;

class LeaguePolicy
{
    protected $globalPerms = ['admin', 'manager'];

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    private function isAuthorized(User $user, League $league)
    {
        $roles = $user->roles();
        if ($roles->count() > 0 && in_array($roles->first()->role->name, $this->globalPerms)) {
            return true;
        }

        return $league->directors()->contains('user_id', $user->id);
    }

    public function show(User $user, League $league)
    {
        if ($league->is_archived === 1) {
            return $this->isAuthorized($user, $league);
        }

        return true;
    }

    public function create(User $user, League $league)
    {
        return $this->isAuthorized($user, $league);
    }

    public function edit(User $user, League $league)
    {
        return $this->isAuthorized($user, $league);
    }

    public function archive(User $user, League $league)
    {
        return $this->isAuthorized($user, $league);
    }
}
