<?php

namespace Cupa;

class TeamMember extends Eloquent
{
    protected $table = 'team_members';
    protected $fillable = [
        'team_id',
        'user_id',
        'year',
        'position',
    ];
}
