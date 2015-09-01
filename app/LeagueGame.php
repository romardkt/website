<?php

namespace Cupa;

class LeagueGame extends Eloquent
{
    protected $table = 'league_games';
    protected $fillable = [
        'league_id',
        'played_at',
        'week',
        'field',
        'status',
    ];
}
