<?php

namespace Cupa;

class UserProfile extends Eloquent
{
    protected $table = 'user_profiles';
    protected $fillable = [
        'user_id',
        'phone',
        'nickname',
        'height',
        'level',
        'experience',
    ];
}
