<?php

namespace Cupa\Models;

use Illuminate\Database\Eloquent\Model;

class LeaguePlayerCount extends Model
{
    protected $table = 'league_player_counts';
    protected $fillable = [];

    public function league()
    {
        return $this->belongsTo(League::class);
    }
}
