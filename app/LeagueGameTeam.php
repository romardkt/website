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
}
