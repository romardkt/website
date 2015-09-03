<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserWaiver extends Model
{
    protected $table = 'user_waivers';
    protected $fillable = [
        'user_id',
        'year',
        'updated_by',
    ];

    public static function hasWaiver($userId, $year = null)
    {
        if ($year === null) {
            $year = Carbon::now()->year;
        }

        return static::where('user_id', '=', $userId)
            ->where('year', '=', $year)
            ->count();
    }

    public static function fetchAllWaivers($userId)
    {
        return static::where('user_id', '=', $userId)
            ->get();
    }
}
