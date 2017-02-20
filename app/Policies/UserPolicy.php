<?php

namespace Cupa\Policies;

use Cupa\Models\User;
use Cupa\Models\LeagueMember;
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

        // check for user roles
        foreach ($authUser->roles as $role) {
            if (in_array($role->role->name, $this->globalPerms)) {
                return true;
            }
        }

        // check to see if the auth user is a director, captain, coach, or assistant_coach
        $leaguesUserIsIn = LeagueMember::join('leagues', 'leagues.id', '=', 'league_members.league_id')
            ->where('league_members.user_id', '=', $user->id)
            ->where('leagues.year', '=', $year)
            ->select('leagues.id','league_members.league_team_id')
            ->get()
            ->toArray();

        $positions = [
            'director',
            'captain',
            'coach',
            'assistant_coach',
        ];

        foreach($leaguesUserIsIn as $leagueData) {
            $member = LeagueMember::join('leagues', 'leagues.id', '=', 'league_members.league_id')
                ->where('leagues.id', '=', $leagueData['id'])
                ->where('league_members.user_id', '=', $authUser->id)
                ->whereIn('league_members.position', $positions);

            if (!empty($leagueData['league_team_id'])) {
                $member->where('league_team_id', '=', $leagueData['league_team_id']);
            }

            if ($member->count() > 0) {
                return true;
            }
        }

        return false;
    }
}
