<?php

namespace Cupa;

class UserWaiver extends Eloquent
{
    protected $table = 'user_waivers';
    protected $fillable = [
        'user_id',
        'year',
        'updated_by',
    ];
}
