<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class LeagueTeam extends Model
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
