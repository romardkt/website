<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class VolunteerEventSignup extends Model
{
    protected $table = 'volunteer_event_signups';
    protected $fillable = [
        'volunteer_event_id',
        'volunteer_id',
        'answers',
        'notes',
    ];

    public function event()
    {
        return $this->belongsTo('Cupa\VolunteerEvent', 'volunteer_event_id', 'id');
    }

    public function volunteer()
    {
        return $this->belongsTo('Cupa\Volunteer');
    }
}
