<?php

namespace Cupa;

class VolunteerEventCategory extends Eloquent
{
    protected $table = 'volunteer_event_categories';
    protected $fillable = [
        'name',
        'questions',
    ];
}
