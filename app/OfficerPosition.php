<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class OfficerPosition extends Model
{
    protected $table = 'officer_positions';
    protected $fillable = [
        'name',
        'weight',
    ];

    public static function fetchAll()
    {
        return static::orderBy('weight', 'asc')->get();
    }

    public static function fetchForSelect()
    {
        $options = [];
        foreach (static::orderBy('name')->get() as $row) {
            $options[$row->id] = $row->name;
        }

        return $options;
    }
}
