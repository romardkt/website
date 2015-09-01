<?php

namespace Cupa;

class PickupContact extends Eloquent
{
    protected $table = 'pickup_contacts';
    protected $fillable = [
        'pickup_id',
        'user_id',
    ];
}
