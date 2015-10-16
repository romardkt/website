<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;
use DB;

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
}
