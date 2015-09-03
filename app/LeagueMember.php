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
}
