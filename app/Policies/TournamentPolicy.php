<?php

namespace Cupa\Policies;

use Cupa\Models\User;
use Cupa\Models\Tournament;
use Illuminate\Auth\Access\HandlesAuthorization;

class TournamentPolicy extends CachedPolicy
{
    use HandlesAuthorization;

    protected $globalPerms = ['admin', 'manager'];

    private function isAuthorized(User $user, Tournament $tournament)
    {
        if ($user->parent !== null) {
            $user = $user->parentObject;
        }

        if (empty($user)) {
            return false;
        }

        return $this->remember("tournament-auth-{$userId}-{$tournament->id}", function() use ($user, $tournament) {
            $roles = $user->roles();
            foreach ($roles->get() as $role) {
                if (in_array($role->role->name, $this->globalPerms)) {
                    return true;
                }
            }

            return $tournament->contacts->contains(function($value, $key) use ($user) {
                return in_array($value['user_id'], $user->fetchAllIds());
            });
        });
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
        return $this->isAuthorized($user, $tournament);
    }
}
