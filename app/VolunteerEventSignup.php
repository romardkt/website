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

    public function volunteer()
    {
        return $this->belongsTo('Cupa\Volunteer');
    }
}
