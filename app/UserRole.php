<?php

namespace Cupa;

class UserRole extends Eloquent
{
    protected $table = 'user_roles';
    protected $fillable = [
        'user_id',
        'role_id',
    ];
}
