<?php

namespace Cupa\Policies;

use Cupa\User;
use Cupa\Team;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy extends CachedPolicy
{
    use HandlesAuthorization;

    protected $globalPerms = ['admin', 'manager', 'editor'];

    private function isAuthorized(User $user, Team $team)
    {
        return $this->remember("team-auth-{$user->id}-{$team->id}", function() use ($user, $team) {
            $roles = $user->roles();
            foreach ($roles->get() as $role) {
                if (in_array($role->role->name, $this->globalPerms)) {
                    return true;
                }
            }

            return $team->captains()->contains('user_id', $user->id);
        });
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
        return $this->remember("team-delete-{$user->id}-{$team->id}", function() use ($user) {
            return $user->roles()->first()->name === 'admin';
        });
    }
}
