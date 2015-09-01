<?php

namespace Cupa;

class UserContact extends Eloquent
{
    protected $table = 'user_contacts';
    protected $fillable = [
        'user_id',
        'name',
        'phone',
    ];
}
