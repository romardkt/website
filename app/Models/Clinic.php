<?php

namespace Cupa\Models;

use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    protected $table = 'clinics';
    protected $fillable = [
        'type',
        'name',
        'display',
        'content',
    ];

    public static function fetchAllClinics($type, $cache = true)
    {
        return static::where('type', '=', $type)
            ->orderBy('display')
            ->get();
    }

    public static function fetchClinic($name)
    {
        return static::where('name', '=', $name)
            ->first();
    }
}
