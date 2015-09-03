<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class LeagueGame extends Model
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
