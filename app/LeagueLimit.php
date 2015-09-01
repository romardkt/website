<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class LeagueLimit extends Model
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
