<?php

namespace Cupa\Models;

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
        return $this->belongsTo(VolunteerEventCategory::class, 'volunteer_event_category_id');
    }

    public function members()
    {
        return $this->hasMany(VolunteerEventSignup::class);
    }

    public function contacts()
    {
        return $this->hasMany(VolunteerEventContact::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function needed()
    {
        $count = $this->num_volunteers - $this->members->count();

        return ($count < 0) ? 0 : $count;
    }

    public static function fetchAllEvents($past = false)
    {
        $now = Carbon::now();
        $query = static::with(['members', 'category', 'contacts', 'contacts.user'])
            ->orderBy('start', 'asc');

        if ($past) {
            $query->where('end', '<', $now->format('Y-m-d 23:59:00'));
        } else {
            $query->where('end', '>=', $now->format('Y-m-d 23:59:00'));
        }

        return $query->get();
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
