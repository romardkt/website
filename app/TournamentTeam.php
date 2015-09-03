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
}
