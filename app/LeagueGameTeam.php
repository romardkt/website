<?php

namespace Cupa;

class LeagueGameTeam extends Eloquent
{
    protected $table = 'league_game_teams';
    protected $fillable = [
        'league_game_id',
        'type',
        'league_team_id',
        'score',
    ];
}
