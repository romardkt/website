<?php

namespace Cupa;

class VolunteerEvent extends Eloquent
{
    protected $table = 'volunteer_events';
    protected $fillable = [
        'volunteer_event_category_id',
        'title',
        'slug',
        'email_override',
        'start',
        'end',
        'num_volunteers',
        'information',
        'location_id',
    ];
}
