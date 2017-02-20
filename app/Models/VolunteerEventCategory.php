<?php

namespace Cupa\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerEventCategory extends Model
{
    protected $table = 'volunteer_event_categories';
    protected $fillable = [
        'name',
        'questions',
    ];

    public static function fetchForSelect()
    {
        $options = [];
        foreach (static::orderBy('name')->get() as $row) {
            $options[$row->id] = $row->name;
        }

        return $options;
    }
}
