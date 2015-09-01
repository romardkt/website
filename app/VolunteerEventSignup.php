<?php

namespace Cupa;

class VolunteerEventSignup extends Eloquent
{
    protected $table = 'volunteer_event_signups';
    protected $fillable = [
        'volunteer_event_id',
        'volunteer_id',
        'answers',
        'notes',
    ];
}
