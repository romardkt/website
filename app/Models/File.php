<?php

namespace Cupa\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';
    protected $fillable = [
        'name',
        'mime',
        'location',
        'md5',
        'size',
    ];

    public static function fetchAllFiles()
    {
        return static::orderBy('name')->get();
    }

    public static function fetchBy($column, $value)
    {
        return static::where($column, $value)->first();
    }

    public static function isUnique($md5)
    {
        $file = static::fetchBy('md5', $md5);

        return empty($file);
    }
}
