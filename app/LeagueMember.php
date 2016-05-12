<?php

namespace Cupa;

use DB;
use StdClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

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
            ->whereIn('position', ['player', 'waitlist'])
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
        $select = static::with(['user', 'user.profile', 'user.parentObj', 'team'])
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

    public function getStatus()
    {
        $requirements = (isset($this->user->coachingRequirements($this->league->year)->requirements)) ? json_decode($this->user->coachingRequirements($this->league->year)->requirements) : new StdClass();

        // check requirements
        foreach (Config::get('cupa.coachingRequirements') as $req => $reqText) {
            if (!isset($requirements->$req) || $requirements->$req != 1) {
                return ['status' => 'text-danger', 'msg' => 'Not Complete'];
            }
        }

        // check user information
        if (empty($this->user->profile->phone)) {
            return ['status' => 'text-danger', 'msg' => 'Not Complete'];
        }

        // check waiver
        if (!$this->user->hasWaiver($this->league->year)) {
            return ['status' => 'text-danger', 'msg' => 'Not Complete'];
        }

        return ['status' => 'text-success', 'msg' => 'Complete'];
    }

    public static function clearCoaches($leagueTeamId)
    {
        $coaches = static::where(function ($query) {
                $query->where('position', '=', 'assistant_coach')
                    ->orWhere('position', '=', 'coach');
        })
            ->where('league_team_id', '=', $leagueTeamId)
            ->delete();
    }

    public static function fetchAllMembersFromUser($leagueId, array $userIds)
    {
        return static::with(['user', 'user.profile'])
            ->where('league_id', '=', $leagueId)
            ->whereIn('user_id', $userIds)
            ->where(function ($query) {
                $query->where('position', '=', 'player')
                    ->orWhere('position', '=', 'waitlist');
            })
            ->get();
    }

    public static function fetchAnswersByLeague($leagueId)
    {
        $result = static::where('position', '=', 'player')
            ->leftJoin('leagues', 'leagues.id', '=', 'league_members.league_id')
            ->leftJoin('users', 'users.id', '=', 'league_members.user_id')
            ->leftJoin('user_profiles', 'user_profiles.user_id', '=', 'users.id')
            ->leftJoin('league_teams', 'league_teams.id', '=', 'league_members.league_team_id')
            ->leftJoin('users AS parent', 'parent.id', '=', 'users.parent')
            ->leftJoin('user_profiles AS parentProfile', 'parentProfile.user_id', '=', 'parent.id')
            ->leftJoin('user_waivers', function ($join) {
                $join->on('user_waivers.user_id', '=', 'users.id')
                     ->on('user_waivers.year', '=', 'leagues.year');
            })
            ->leftJoin('user_balances', 'user_balances.user_id', '=', 'league_members.user_id')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->orderBy('league_members.created_at', 'DESC')
            ->select('league_members.id', 'league_members.league_team_id', 'league_teams.name AS team_name', 'league_members.paid', 'league_members.answers', 'league_members.created_at AS registered_at', 'users.id AS user_id', 'users.email', 'parent.email AS parent_email', 'users.avatar', 'users.first_name', 'users.last_name', 'users.gender', 'users.birthday', 'user_profiles.phone', 'user_profiles.nickname', 'user_profiles.height', 'user_profiles.level', 'user_profiles.experience', 'user_waivers.year AS waiver', 'user_balances.balance AS balance', 'user_balances.leagues AS balance_leagues', 'parentProfile.phone AS parent_phone')
            ->where('leagues.id', '=', $leagueId)
            ->groupBy('league_members.id')
            ->get();

        $data = [];
        foreach ($result as $row) {
            $row->answers = json_decode($row->answers, true);
            $data[] = $row->toArray();
        }

        return $data;
    }

    public static function fetchUnpaidLeagueMembers($leagueId)
    {
        return static::with(['user', 'user.profile'])
            ->join('users', 'users.id', '=', 'league_members.user_id')
            ->where('league_id', '=', $leagueId)
            ->where('paid', '=', 0)
            ->where('position', '=', 'player')
            ->select('league_members.*')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->get();
    }
}
