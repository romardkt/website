<?php

namespace Cupa\Models;

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
        return $this->belongsTo(VolunteerEvent::class, 'volunteer_event_id', 'id');
    }

    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }
}
