<?php

namespace Cupa;

class LeagueTeam extends Eloquent
{
    protected $table = 'league_teams';
    protected $fillable = [
        'league_id',
        'name',
        'logo',
        'color',
        'color_code',
        'text_code',
    ];
}
