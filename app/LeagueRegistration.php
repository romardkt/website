<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class LeagueRegistration extends Model
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
