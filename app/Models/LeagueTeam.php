<?php

namespace Cupa\Models;

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

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function captains()
    {
        return LeagueMember::fetchAllMembers('captain', $this->id);
    }

    public function coaches()
    {
        return LeagueMember::fetchAllMembers('coaches', $this->id);
    }

    public function headCoaches()
    {
        return LeagueMember::fetchAllMembers('coach', $this->id);
    }

    public function asstCoaches()
    {
        return LeagueMember::fetchAllMembers('assistant_coach', $this->id);
    }

    public function players()
    {
        return LeagueMember::fetchAllMembers('player', $this->id);
    }

    public function record()
    {
        return $this->hasOne(LeagueTeamRecord::class);
    }

    public function points()
    {
        $points = LeagueTeamRecord::fetchRecord($this->id);

        return ($points) ? $points->points() : '0 - 0 (0)';
    }
}
