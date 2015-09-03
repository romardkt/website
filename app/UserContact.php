<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class UserContact extends Model
{
    protected $table = 'user_contacts';
    protected $fillable = [
        'user_id',
        'name',
        'phone',
    ];
}
