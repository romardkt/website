<?php

namespace Cupa;

class TournamentMember extends Eloquent
{
    protected $table = 'tournament_members';
    protected $fillable = [
        'tournament_id',
        'user_id',
        'position',
        'weight',
    ];
}
