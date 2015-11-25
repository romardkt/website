<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class LeagueGameTeam extends Model
{
    protected $table = 'league_game_teams';
    protected $fillable = [
        'league_game_id',
        'type',
        'league_team_id',
        'score',
    ];

    public function game()
    {
        return $this->belongsTo('Cupa\LeagueGame', 'league_game_id');
    }

    public function team()
    {
        return $this->belongsTo('Cupa\LeagueTeam', 'league_team_id');
    }

    public static function fetchGames($teamId)
    {
        return static::join('league_games AS lg', 'lg.id', '=', 'league_game_teams.league_game_id')
            ->where('league_game_teams.league_team_id', '=', $teamId)
            ->orderBy('lg.week', 'asc')
            ->orderBy('lg.field', 'asc')
            ->get();
    }

    public function getClassText()
    {
        if ($this->score === null) {
            return '';
        }

        $other = $this->where('league_game_id', '=', $this->league_game_id)
            ->where('type', '<>', $this->type)
            ->first();

        if ($this->score == $other->score) {
            return '';
        } elseif ($this->score > $other->score) {
            return 'text-yes';
        } else {
            return 'text-danger';
        }
    }

    public static function updateTeams($leagueGameId, $data)
    {
        foreach (['away_team', 'home_team'] as $type) {
            $t = ($type == 'away_team') ? 'away' : 'home';
            // get all teams in db
            $dbTeams = [];
            foreach (static::where('league_game_id', '=', $leagueGameId)->where('type', '=', $t)->get() as $team) {
                $dbTeams[] = $team->league_team_id;
            }

            // calculate ones to remove
            $remove = array_diff($dbTeams, $data[$type]);
            if (count($remove)) {
                DB::table('league_game_teams')->where('league_game_id', '=', $leagueGameId)->where('type', '=', $t)->whereIn('league_team_id', $remove)->delete();
            }

            // calculate ones to add
            $add = array_diff($data[$type], $dbTeams);
            foreach ($add as $a) {
                static::create([
                    'league_game_id' => $leagueGameId,
                    'type' => $t,
                    'league_team_id' => $a,
                    'score' => $data[$t.'_score'],
                ]);
            }
        }

        // update the scores
        foreach (static::where('league_game_id', '=', $leagueGameId)->get() as $team) {
            $team->score = ($team->type == 'home') ? $data['home_score'] : $data['away_score'];
            $team->save();
        }

        return;
    }
}
