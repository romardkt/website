<?php

namespace Cupa;

use Carbon\Carbon;
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

    public function league()
    {
        return $this->belongsTo('Cupa\League');
    }

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

    public function score()
    {
        $homeTeam = (count($this->team('home'))) ? $this->team('home')[0]->score : 0;
        $awayTeam = (count($this->team('away'))) ? $this->team('away')[0]->score : 0;

        //dd($homeTeam->score, $awayTeam->score);
        if (empty($homeTeam) && empty($awayTeam)) {
            return 'N/A';
        }

        return $awayTeam.' - '.$homeTeam;
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

            if (!isset($score[0]) || !isset($score[1])) {
                continue;
            } elseif ($score[0] == $score[1]) {
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

    public static function fetchAlerts()
    {
        $today = Carbon::now();
        $games = static::where('played_at', 'LIKE', $today->format('Y-m-d').'%')
            ->whereIn('status', ['gametime_decision', 'cancelled'])
            ->get();

        $alerts = [];
        foreach ($games as $game) {
            $awayTeams = [];
            foreach ($game->team('away') as $away) {
                $awayTeams[] = $away->team->name;
            }
            $awayTeams = implode(', ', $awayTeams);

            $homeTeams = [];
            foreach ($game->team('home') as $home) {
                $homeTeams[] = $home->team->name;
            }
            $homeTeams = implode(', ', $homeTeams);

            $alerts[] = [
                'slug' => $game->league->slug,
                'week' => $game->week,
                'time' => (new Carbon($game->played_at))->format('h:i A'),
                'teams' => $game->league->displayName().': '.$awayTeams.' vs. '.$homeTeams,
                'status' => str_replace('_', ' ', strtoupper($game->status)),
            ];
        }

        if (count($alerts) < 1) {
            return [];
        }

        return [
            'date' => $today->format('m/d/Y'),
            'alerts' => $alerts,
        ];
    }
}
