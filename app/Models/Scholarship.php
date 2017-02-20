<?php

namespace Cupa\Models;

use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    protected $table = 'scholarships';
    protected $fillable = [
        'scholarship',
        'name',
        'email',
        'document',
        'comments',
    ];

    public static function fetchSubmissions($type)
    {
        return static::where('scholarship', '=', $type)->get();
    }
}
