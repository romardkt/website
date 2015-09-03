<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class VolunteerEventContact extends Model
{
    protected $table = 'volunteer_event_contacts';
    protected $fillable = [
        'volunteer_event_id',
        'user_id',
        'email_override',
    ];
}
