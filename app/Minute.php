<?php

namespace Cupa;

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
        return $this->belongsTo('Cupa\Location');
    }

    public static function fetchMinutes()
    {
        return static::with('location')
            ->orderBy('start', 'desc')
            ->get();
    }
}
