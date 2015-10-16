<?php

namespace Cupa;

use Carbon\Carbon;
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

    public function category()
    {
        return $this->belongsTo('Cupa\VolunteerEventCategory', 'volunteer_event_category_id');
    }

    public function members()
    {
        return $this->hasMany('Cupa\VolunteerEventSignup');
    }

    public function contacts()
    {
        return $this->hasMany('Cupa\VolunteerEventContact');
    }

    public function location()
    {
        return $this->belongsTo('Cupa\Location');
    }

    public function needed()
    {
        $count = $this->num_volunteers - $this->members->count();

        return ($count < 0) ? 0 : $count;
    }

    public static function fetchAllCurrentEvents()
    {
        $now = Carbon::now();

        return static::with(['members', 'category', 'contacts', 'contacts.user'])
            ->where('end', '>=', $now->format('Y-m-d 23:59:00'))
            ->orderBy('start', 'asc')
            ->get();
    }

    public static function fetchBySlug($slug)
    {
        return static::with(array('members', 'category', 'contacts', 'contacts.user'))
            ->where('slug', '=', $slug)
            ->get();
    }

    public static function isMember($eventId, $volunteerId)
    {
        return (VolunteerEventSignup::where('volunteer_id', '=', $volunteerId)
            ->where('volunteer_event_id', '=', $eventId)
            ->count() > 0) ? true : false;
    }
}
