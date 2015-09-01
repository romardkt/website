<?php

namespace Cupa;

class TournamentLocation extends Eloquent
{
    protected $table = 'tournament_locations';
    protected $fillable = [
        'tournament_id',
        'title',
        'link',
        'street',
        'city',
        'state',
        'zip',
        'phone',
        'other',
    ];
}
