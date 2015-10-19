<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class LeagueGame extends Model
{
    protected $table = 'league_games';
    protected $fillable = [
        'league_id',
        'played_at',
        'week',
        'field',
        'status',
    ];

    public function teams()
    {
        return $this->hasMany('Cupa\LeagueGameTeam');
    }

    public function team($type)
    {
        $teams = [];
        foreach ($this->teams as $team) {
            if ($team->type == $type) {
                $teams[] = $team;
            }
        }

        return $teams;
    }

    public static function fetchRecord($teamId)
    {
        $records = [];
        foreach (LeagueGameTeam::fetchGames($teamId) as $team) {
            $type = ($team->type == 'home') ? 'away' : 'home';

            $teams = [];
            $score = [$team->score];
            foreach ($team->game->team($type) as $otherTeam) {
                $score[] = $otherTeam->score;
                $teams[] = $otherTeam->team->name;
            }

            $data = $team->game->toArray();
            unset($data['teams']);
            if ($score[0] == $score[1]) {
                $result = 't';
            } else {
                $result = ($score[0] > $score[1]) ? 'w' : 'l';
            }

            $data['team'] = 'vs '.implode(', ', $teams);
            $data['score'] = $score[0].' - '.$score[1];
            $data['result'] = $result;
            $records[] = $data;
        }

        return $records;
    }

    public static function fetchAllGames($leagueId)
    {
        return static::with(['teams', 'teams.team'])
            ->where('league_id', '=', $leagueId)
            ->orderBy('played_at')
            ->orderBy('field', 'asc')
            ->get();
    }

    public static function fetchAllWeeks($leagueId)
    {
        $select = static::where('league_id', '=', $leagueId)
            ->orderBy('week', 'asc')
            ->distinct()
            ->select('week');

        $weeks = [];
        foreach ($select->get() as $row) {
            $weeks[$row->week] = $row->week;
        }

        return $weeks;
    }
}
