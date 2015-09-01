<?php

namespace Cupa;

class LeagueLimit extends Eloquent
{
    protected $table = 'league_limits';
    protected $fillable = [
        'league_id',
        'male',
        'female',
        'total',
        'teams',
        'players',
    ];
}
