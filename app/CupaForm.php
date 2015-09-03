<?php

namespace Cupa;

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
}
