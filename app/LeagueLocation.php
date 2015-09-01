<?php

namespace Cupa;

class LeagueLocation extends Eloquent
{
    protected $table = 'league_locations';
    protected $fillable = [
        'league_id',
        'type',
        'location_id',
        'begin',
        'end',
        'begin',
        'num_fields',
        'link',
    ];
}
