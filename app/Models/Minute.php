<?php

namespace Cupa\Models;

use Illuminate\Database\Eloquent\Model;

class Minute extends Model
{
    protected $table = 'minutes';
    protected $fillable = [
        'start',
        'end',
        'location',
        'pdf',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public static function fetchMinutes()
    {
        return static::with('location')
            ->orderBy('start', 'desc')
            ->get();
    }
}
