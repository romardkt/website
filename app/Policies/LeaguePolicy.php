<?php

namespace Cupa\Policies;

use Cupa\Models\User;
use Cupa\Models\League;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaguePolicy extends CachedPolicy
{
    use HandlesAuthorization;

    protected $globalPerms = ['admin', 'manager'];
    private function isAuthorized(User $user, League $league)
    {
        if ($user->parent !== null) {
            $user = $user->parentObject;
        }

        return $this->remember("league-auth-{$user->id}-{$league->id}", function () use ($user, $league) {
            $roles = $user->roles();
            foreach ($roles->get() as $role) {
                if (in_array($role->role->name, $this->globalPerms)) {
                    return true;
                }
            }

            return $league->directors()->contains(function($value, $key) use ($user) {
                return in_array($value['user_id'], $user->fetchAllIds());
            });
        });
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
        if ($user->parent !== null) {
            $user = $user->parentObject;
        }

        return $this->remember("league-coach-{$user->id}-{$league->id}", function () use ($user, $league) {
            if ($league->is_youth) {
                $isCoach = $league->coaches()->contains(function($value, $key) use ($user) {
                    return in_array($value['user_id'], $user->fetchAllIds());
                });

                if ($isCoach) {
                    return true;
                }

                foreach ($user->roles()->get() as $userRole) {
                    if ($userRole->role->name == 'background') {
                        return true;
                    }
                }
            }

            return $this->isAuthorized($user, $league);
        });
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
