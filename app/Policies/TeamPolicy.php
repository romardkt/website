<?php

namespace Cupa\Policies;

use Cupa\User;
use Cupa\Team;

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

    public function show(User $user, Team $team)
    {
        if ($team->is_visible === 0) {
            return $this->isAuthorized($user, $team);
        }

        return true;
    }

    public function create(User $user, Team $team)
    {
        return $this->isAuthorized($user, $team);
    }

    public function edit(User $user, Team $team)
    {
        return $this->isAuthorized($user, $team);
    }

    public function delete(User $user, Team $team)
    {
        return $user->roles()->first()->name === 'admin';
    }
}
