<?php

namespace Cupa;

class Volunteer extends Eloquent
{
    protected $table = 'volunteers';
    protected $fillable = [
        'user_id',
        'involvement',
        'primary_interest',
        'experience',
    ];
}
