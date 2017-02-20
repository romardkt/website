<?php

namespace Cupa\Models;

use Illuminate\Database\Eloquent\Model;

class UserBalance extends Model
{
    protected $table = 'user_balances';
    protected $fillable = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function fetchAllUnpaid()
    {
        return static::with('user')
                     ->join('users AS u', 'u.id', '=', 'user_balances.user_id')
                     ->whereNotNull('balance')
                     ->orderBy('u.last_name')
                     ->orderBy('u.first_name')
                     ->select('user_balances.*')
                     ->get();
    }

    public static function owesMoney($userId)
    {
        if (is_array($userId)) {
            return static::whereIn('user_id', $userId)->count() > 0;
        }

        return static::where('user_id', '=', $userId)->count() > 0;
    }

    public static function fetchOwed($userId)
    {
        if (is_array($userId)) {
            return static::whereIn('user_id', $userId)->sum('balance');
        }

        return static::where('user_id', '=', $userId)->sum('balance');
    }
}
