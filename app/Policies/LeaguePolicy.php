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
        foreach ($roles->get() as $role) {
            if (in_array($role->role->name, $this->globalPerms)) {
                return true;
            }
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

    public function coach(User $user, League $league)
    {
        if ($league->is_youth) {
            if ($league->coaches()->contains('user_id', $user->id)) {
                return true;
            }

            foreach ($user->roles()->get() as $userRole) {
                if ($userRole->role->name == 'background') {
                    return true;
                }
            }
        }

        return $this->isAuthorized($user, $league);
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
