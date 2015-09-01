<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class TournamentMember extends Model
{
    protected $table = 'tournament_members';
    protected $fillable = [
        'tournament_id',
        'user_id',
        'position',
        'weight',
    ];
}
