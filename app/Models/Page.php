<?php

namespace Cupa\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';
    protected $fillable = [
        'route',
        'display',
        'content',
        'is_visible',
        'weight',
        'created_by',
        'updated_by',
    ];

    public static function fetchBy($column, $value)
    {
        return static::where($column, '=', $value)
            ->first();
    }

    public static function fetchMenu()
    {
        $pages = [];
        $results = static::orderBy('weight', 'asc')
            ->get();

        foreach ($results as $row) {
            $data = explode('_', $row->route);
            if (isset($data[1])) {
                $pages[$data[0]][] = $row;
            } else {
                $pages['root'][] = $row;
            }
        }

        foreach (Team::fetchAllCurrent() as $team) {
            $pages['teams'][] = $team;
        }

        return $pages;
    }
}
