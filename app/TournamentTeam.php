<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class TournamentTeam extends Model
{
    protected $table = 'tournament_teams';
    protected $fillable = [
        'tournament_id',
        'division',
        'name',
        'city',
        'state',
        'contact_name',
        'contact_phone',
        'contact_email',
        'accepted',
        'paid',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public static function fetchUnpaidTeamsByDivision($tournamentId)
    {
        $result = static::where('paid', '=', 0)
            ->where('tournament_id', '=', $tournamentId)
            ->orderBy('division')
            ->orderBy('name')
            ->get();

        $teams = [];
        foreach ($result as $team) {
            $teams[$team->division][] = $team;
        }

        return $teams;
    }
}
