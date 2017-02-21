<?php

namespace Cupa\Policies;


use Cupa\Models\User;
use Cupa\Models\LeagueTeam;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeagueTeamPolicy extends CachedPolicy
{
    use HandlesAuthorization;

    protected $globalPerms = ['admin', 'manager'];

    private function isAuthorized(User $user, LeagueTeam $leagueTeam)
    {
        if ($user->parent !== null) {
            $user = $user->parentObject;
        }

        return $this->remember("leagueTeam-auth-{$user->id}-{$leagueTeam->id}", function() use ($user, $leagueTeam) {
            $roles = $user->roles();
            foreach ($roles->get() as $role) {
                if (in_array($role->role->name, $this->globalPerms)) {
                    return true;
                }
            }

            return $leagueTeam->league->directors()->contains(function($value, $key) use ($user) {
                return in_array($value['user_id'], $user->fetchAllIds());
            });
        });
    }

    public function coach(User $user, LeagueTeam $leagueTeam)
    {
        if ($user->parent !== null) {
            $user = $user->parentObject;
        }

        return $this->remember("leagueTeam-coach-{$user->id}-{$leagueTeam->id}", function() use ($user, $leagueTeam) {
            if ($leagueTeam->league->is_youth) {
                $isCoach = $leagueTeam->coaches()->contains(function($value, $key) use ($user) {
                    return in_array($value['user_id'], $user->fetchAllIds());
                });

                if ($isCoach) {
                    return true;
                }
            } else {
                $isCaptain = $leagueTeam->captains()->contains(function($value, $key) use ($user) {
                    return in_array($value['user_id'], $user->fetchAllIds());
                });

                if ($isCaptain) {
                    return true;
                }
            }

            return $this->isAuthorized($user, $leagueTeam);
        });
    }
}
