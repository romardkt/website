<?php

namespace Cupa;

class LeagueRegistration extends Eloquent
{
    protected $table = 'league_registrations';
    protected $fillable = [
        'league_id',
        'begin',
        'end',
        'cost',
        'questions',
    ];
}
