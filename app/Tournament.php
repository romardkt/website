<?php

namespace Cupa;

class Tournament extends Eloquent
{
    protected $table = 'tournaments';
    protected $fillable = [
        'name',
        'year',
        'display_name',
        'divisions',
        'override_email',
        'location_id',
        'tenative_date',
        'start',
        'end',
        'description',
        'schedule',
        'cost',
        'use_bid',
        'use_paypal',
        'bid_due',
        'paypal',
        'is_visible',
    ];
}
