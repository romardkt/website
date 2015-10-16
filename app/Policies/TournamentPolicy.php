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

    private function isAuthorized(User $user, Tournament $tournament)
    {
        $roles = $user->roles();
        if ($roles->count() > 0 && in_array($roles->first()->role->name, $this->globalPerms)) {
            return true;
        }

        return $tournament->contacts->contains('user_id', $user->id);
    }

    public function show(User $user, Tournament $tournament)
    {
        if ($tournament->is_visible == 0) {
            return $this->isAuthorized($user, $tournament);
        }

        return true;
    }

    public function create(User $user, Tournament $tournament)
    {
        return $this->isAuthorized($user, $tournament);
    }

    public function edit(User $user, Tournament $tournament)
    {
        return $this->isAuthorized($user, $tournament);
    }

    public function delete(User $user, Tournament $tournament)
    {
        return $user->roles()->first()->name === 'admin';
    }
}
