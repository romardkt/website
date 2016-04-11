<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class LeagueTeamRecord extends Model
{
    protected $table = 'league_team_records';
    protected $fillable = [];

    public function record()
    {
        return $this->wins.' - '.$this->losses.' - '.$this->ties;
    }

    public function points()
    {
        $sign = ($this->diff > 0) ? '+' : '';

        return $this->points_for.' - '.$this->points_against.' ('.$sign.$this->diff.')';
    }

    public static function fetchRecord($leagueTeamId)
    {
        return static::where('league_team_id', '=', $leagueTeamId)->first();
    }
}
