<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class VolunteerEvent extends Model
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

    public function contacts()
    {
        return $this->hasMany('Cupa\VolunteerContact');
    }
}
