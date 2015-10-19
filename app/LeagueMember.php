<?php

namespace Cupa;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LeagueMember extends Model
{
    protected $table = 'league_members';
    protected $fillable = [
        'league_id',
        'user_id',
        'requirements',
        'position',
        'league_team_id',
        'paid',
        'answers',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo('Cupa\User');
    }

    public function league()
    {
        return $this->belongsTo('Cupa\League');
    }

    public function team()
    {
        return $this->belongsTo('Cupa\LeagueTeam', 'league_team_id');
    }

    public static function typeahead($leagueId, $filter, $ids = false)
    {
        $filter = urldecode($filter);

        if (empty($filter)) {
            return [];
        }

        $results = static::from('league_members AS lm')
            ->leftJoin('users AS u', 'u.id', '=', 'lm.user_id')
            ->where('lm.league_id', '=', $leagueId)
            ->where('lm.position', '=', 'player')
            ->orderBy('u.last_name')
            ->orderBy('u.first_name')
            ->select('lm.id', 'u.first_name', 'u.last_name');

        if ($ids) {
            $filter = explode(',', $filter);
            $results->whereIn('lm.id', $filter);
        } else {
            $results->where(DB::raw("CONCAT_WS(' ', u.first_name, u.last_name)"), 'LIKE', '%'.$filter.'%');
        }

        $data = [];
        foreach ($results->get() as $row) {
            $data[] = [
                'id' => $row['id'],
                'text' => $row['first_name'].' '.$row['last_name'],
            ];
        }

        return $data;
    }

    public static function fetchAllCoaches()
    {
        $coaches = static::with(['user', 'team'])
            ->join('leagues', 'leagues.id', '=', 'league_members.league_id')
            ->where(function ($query) {
                $query->where('position', '=', 'coach')
                    ->orWhere('position', '=', 'assistant_coach');
            })
            ->where('leagues.is_youth', '=', 1)
            ->select('league_members.*')
            ->get();

        $result = [];
        foreach ($coaches as $coach) {
            if (empty($coach->team->name)) {
                continue;
            }

            $name = $coach->user->fullname();
            if (empty($result[$name])) {
                $result[$name] = [
                    'name' => $name,
                    'email' => (empty($coach->user->email)) ? 'Unknown' : $coach->user->email,
                    'teams' => $coach->team->name,
                ];
            } else {
                $result[$name]['teams'] .= ','.$coach->team->name;
            }
        }

        return $result;
    }

    public static function fetchAllLeagues(array $userIds, $withMinors)
    {
        return static::with(['league', 'team', 'team.record'])
            ->whereIn('user_id', $userIds)
            ->where('position', '=', 'player')
            ->join('leagues AS l', 'l.id', '=', 'league_members.league_id')
            ->join('league_registrations AS lr', 'lr.league_id', '=', 'l.id')
            ->orderBy('l.year', 'desc')
            ->orderBy('lr.begin', 'desc')
            ->select('league_members.*')
            ->get();
    }

    public static function updateMembers($leagueId, $leagueTeamId, $members, $position)
    {
        $dbMembers = [];
        foreach (static::where('league_id', '=', $leagueId)->where('league_team_id', '=', $leagueTeamId)->where('position', '=', $position)->get() as $member) {
            $dbMembers[] = $member->user_id;
        }

        $subMembers = [];
        foreach ($members as $memberId) {
            $subMembers[] = $memberId;
        }

        $remove = array_diff($dbMembers, $subMembers);
        if (count($remove)) {
            DB::table('league_members')->where('league_id', '=', $leagueId)->where('league_team_id', '=', $leagueTeamId)->where('position', '=', $position)->whereIn('user_id', $remove)->delete();
        }

        $add = array_diff($subMembers, $dbMembers);
        foreach ($add as $a) {
            static::create([
                'league_id' => $leagueId,
                'user_id' => $a,
                'position' => $position,
                'league_team_id' => $leagueTeamId,
                'updated_by' => Auth::id(),
            ]);
        }

        return;
    }

    public static function fetchLeagueMembers($leagueId, $position = null, $teamId = null)
    {
        $select = static::with(['user', 'user.profile', 'league'])
            ->join('users', 'users.id', '=', 'league_members.user_id')
            ->where('league_id', '=', $leagueId)
            ->where('league_team_id', '=', $teamId)
            ->select('league_members.*')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name');

        if ($position !== null) {
            if (is_array($position)) {
                $select->where(function ($query) use ($position) {
                    foreach ($position as $p) {
                        $query->orWhere('position', '=', $p);
                    }
                });
            } else {
                $select->where('position', '=', $position);
            }
        }

        return $select->get();
    }

    public static function fetchAllMembers($position = null, $teamId = null)
    {
        $select = static::with(['user', 'user.profile'])
            ->join('users', 'users.id', '=', 'league_members.user_id')
            ->select('league_members.*')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name');

        if ($position !== null) {
            if ($position == 'coaches') {
                $select->where('position', 'LIKE', '%coach');
            } else {
                $select->where('position', '=', $position);
            }
        }

        if ($teamId !== null) {
            $select->where('league_team_id', '=', $teamId);
        }

        return $select->get();
    }

    public static function fetchAllLeagueMembers($leagueId, $position = null, $order = 'name')
    {
        $select = static::with(['user', 'user.profile'])
            ->leftJoin('users', 'users.id', '=', 'league_members.user_id')
            ->leftJoin('league_teams', 'league_teams.id', '=', 'league_members.league_team_id')
            ->where('league_members.league_id', '=', $leagueId)
            ->select('league_members.*', 'league_teams.name AS team_name');

        if ($order == 'name') {
            $select->orderBy('users.last_name')
                ->orderBy('users.first_name');
        } elseif ($order == 'team') {
            $select->orderBy('league_teams.name')
               ->orderBy('league_members.position', 'desc')
               ->orderBy('users.last_name')
               ->orderBy('users.first_name');
        } else {
            $select->orderBy($order);
        }

        if ($position !== null) {
            if (is_array($position)) {
                $select->where(function ($query) use ($position) {
                    foreach ($position as $p) {
                        $query->orWhere('position', '=', $p);
                    }
                });
            } else {
                $select->where('position', '=', $position);
            }
        }

        return $select->get();
    }

    public static function fetchMemberNoTeam($leagueId, $userId, $position)
    {
        $select = static::with(['user', 'user.profile'])
            ->where('league_id', '=', $leagueId);

        if ($userId !== null) {
            $select->where('user_id', '=', $userId);
        }

        if (is_array($position)) {
            $select->where(function ($query) use ($position) {
                foreach ($position as $p) {
                    $query->orWhere('position', '=', $p);
                }
            });
        } else {
            $select->where('position', '=', $position);
        }

        return $select->first();
    }

    public static function isMember($leagueId, $userId, $position = null)
    {
        if ($position === null) {
            $position = ['player', 'waitlist'];
        }

        $select = static::where('league_id', '=', $leagueId)
            ->where('user_id', '=', $userId);

        if (is_array($position)) {
            $select->where(function ($query) use ($position) {
                foreach ($position as $p) {
                    $query->orWhere('position', '=', $p);
                }
            });
        } else {
            $select->where('position', '=', $position);
        }

        return ($select->first()) ? true : false;
    }
}
