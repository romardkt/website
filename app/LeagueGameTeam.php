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
}
