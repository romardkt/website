<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class EmailList extends Model
{
    protected $table = 'email_lists';
    protected $fillable = [
        'email',
        'name',
    ];

    public static function fetchAllNotEmailed()
    {
        return static::where('emailed', '=', 0)->get();
    }
}
