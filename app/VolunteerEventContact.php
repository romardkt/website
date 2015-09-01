<?php

namespace Cupa;

class VolunteerEventContact extends Eloquent
{
    protected $table = 'volunteer_event_contacts';
    protected $fillable = [
        'volunteer_event_id',
        'user_id',
        'email_override',
    ];
}
