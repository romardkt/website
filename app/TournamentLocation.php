<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class TournamentLocation extends Model
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
