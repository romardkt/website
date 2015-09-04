<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'teams';
    protected $fillable = [
        'name',
        'type',
        'display_name',
        'menu',
        'override_email',
        'facebook',
        'twitter',
        'begin',
        'end',
        'website',
        'description',
        'updated_by',
    ];

    public static function fetchAllCurrent()
    {
        return static::where('end', '>=', date('Y'))
            ->orWhereNull('end')
            ->orderBy('name')
            ->get();
    }
}
