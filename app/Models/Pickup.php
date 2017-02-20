<?php

namespace Cupa\Models;

use Illuminate\Database\Eloquent\Model;

class Pickup extends Model
{
    protected $table = 'pickups';
    protected $fillable = [
        'title',
        'day',
        'time',
        'email_override',
        'location_id',
        'info',
        'is_visible',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function contacts()
    {
        return $this->hasMany(PickupContact::class);
    }

    public static function fetchAllPickups()
    {
        $select = static::with(['location', 'contacts', 'contacts.user'])
                     ->where('is_visible', '=', 1)
                     ->orderBy('created_at', 'asc');

        return $select->get();
    }
}
