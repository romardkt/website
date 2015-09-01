<?php

namespace Cupa;

class Pickup extends Eloquent
{
    protected $table = 'pickups';
    protected $fillable = [
        'title',
        'day',
        'time',
        'email_override',
        'location_id',
        'info',
        'is_visible',
    ];
}
