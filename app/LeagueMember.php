<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class LeagueMember extends Model
{
    protected $table = 'league_members';
    protected $fillable = [
        'league_id',
        'user_id',
        'requirements',
        'position',
        'league_team_id',
        'paid',
        'answers',
        'updated_by',
    ];
}
