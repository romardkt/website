<?php

namespace Cupa\Policies;

use Cupa\User;
use Cupa\LeagueMember;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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

    public function waiver(User $authUser, User $user, $year)
    {
        // check the user id
        if ($authUser->id == $user->id) {
            return true;
        }

        // check to see if it is the parent
        if ($authUser->id == $user->parent) {
            return true;
        }

        // // check for user roles
        foreach ($authUser->roles as $role) {
            if (in_array($role->role->name, $this->globalPerms)) {
                return true;
            }
        }

        // check to see if the auth user is a director, captain, coach, or assistant_coach
        $leagueMembers = LeagueMember::join('leagues', 'leagues.id', '=', 'league_members.league_id')
            ->where('league_members.user_id', '=', $user->id)
            ->where('leagues.year', '=', $year)
            ->select('league_members.*');

        $positions = [
            'director',
            'captain',
            'coach',
            'assistant_coach',
        ];

        // check all the leagues for the given year to see if the auth user is
        // one of the accepted positions
        foreach($leagueMembers as $leagueMember) {
            if ($authUser->isLeagueMember($leagueMember->league_id, $positions)) {
                return true;
            }
        }

        return false;
    }
}
