<?php

namespace Cupa\Policies;


use Cupa\User;
use Cupa\LeagueTeam;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeagueTeamPolicy extends CachedPolicy
{
    use HandlesAuthorization;

    protected $globalPerms = ['admin', 'manager'];

    private function isAuthorized(User $user, LeagueTeam $leagueTeam)
    {
        return $this->remember("leagueTeam-auth-{$user->id}-{$leagueTeam->id}", function() use ($user, $leagueTeam) {
            $roles = $user->roles();
            foreach ($roles->get() as $role) {
                if (in_array($role->role->name, $this->globalPerms)) {
                    return true;
                }
            }

            return $leagueTeam->league->directors()->contains('user_id', $user->id);
        });
    }

    public function coach(User $user, LeagueTeam $leagueTeam)
    {
        return $this->remember("leagueTeam-coach-{$user->id}-{$leagueTeam->id}", function() use ($user, $leagueTeam) {
            if ($leagueTeam->league->is_youth) {
                if ($leagueTeam->coaches()->contains('user_id', $user->id)) {
                    return true;
                }
            } else {
                if ($leagueTeam->captains()->contains('user_id', $user->id)) {
                    return true;
                }
            }

            return $this->isAuthorized($user, $leagueTeam);
        });
    }
}
