<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $table = 'team_members';
    protected $fillable = [
        'team_id',
        'user_id',
        'year',
        'position',
    ];
}
