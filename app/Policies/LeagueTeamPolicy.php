<?php

namespace Cupa\Policies;


use Cupa\User;
use Cupa\LeagueTeam;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeagueTeamPolicy
{
    use HandlesAuthorization;

    protected $globalPerms = ['admin', 'manager'];

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    private function isAuthorized(User $user, LeagueTeam $leagueTeam)
    {
        $roles = $user->roles();
        foreach ($roles->get() as $role) {
            if (in_array($role->role->name, $this->globalPerms)) {
                return true;
            }
        }

        return $leagueTeam->league->directors()->contains('user_id', $user->id);
    }

    public function coach(User $user, LeagueTeam $leagueTeam)
    {
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
    }
}
