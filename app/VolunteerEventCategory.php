<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class VolunteerEventCategory extends Model
{
    protected $table = 'volunteer_event_categories';
    protected $fillable = [
        'name',
        'questions',
    ];
}
