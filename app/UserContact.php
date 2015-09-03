<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class UserContact extends Model
{
    protected $table = 'user_contacts';
    protected $fillable = [
        'user_id',
        'name',
        'phone',
    ];

    public static function hasContact($userId, $name, $phone)
    {
        $result = static::where('user_id', '=', $userId)
            ->where('name', 'like', $name)
            ->where('phone', '=', $phone)
            ->first();

        return ($result) ? true : false;
    }
}
