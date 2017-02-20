<?php

namespace Cupa\Models;

use Illuminate\Database\Eloquent\Model;

class CupaForm extends Model
{
    protected $table = 'cupa_forms';
    protected $fillable = [
        'year',
        'name',
        'slug',
        'location',
        'extension',
        'size',
        'md5',
    ];

    public static function fetchAllForms()
    {
        return static::orderBy('year', 'desc')->get();
    }

    public static function fetchBySlug($slug)
    {
        return static::where('slug', '=', $slug)->first();
    }

    public static function isUnique($md5, $id = null)
    {
        $select = static::where('md5', '=', $md5);
        if ($id !== null) {
            $select->where('id', '<>', $id);
        }

        return $select->count() === 0;
    }

    public static function fetchWaiver($year, $type)
    {
        $select = static::where('year', '<=', $year)
            ->orderBy('year', 'desc');

        if ($type == 'yuc') {
            $select->where('name', 'LIKE', 'yuc_waiver_release');
        } else {
            $select->where('name', 'LIKE', 'waiver_release');
        }

        return $select->first();
    }
}
