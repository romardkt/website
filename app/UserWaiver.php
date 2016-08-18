<?php

namespace Cupa;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserWaiver extends Model
{
    protected $table = 'user_waivers';
    protected $fillable = [
        'user_id',
        'year',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo('Cupa\User');
    }

    public function updatedBy()
    {
        return $this->belongsTo('Cupa\User', 'updated_by');
    }

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

    public static function toggleWaiver($userId, $year = null)
    {
        if ($year === null) {
            $year = date('Y');
        }

        $row = static::where('user_id', '=', $userId)
            ->where('year', '=', $year)
            ->first();
        if (!$row) {
            $row = static::create([
                'user_id' => $userId,
                'year' => $year,
                'updated_by' => Auth::id(),
            ]);
        } else {
            $row->delete();
        }
    }

    public static function signWaiver($userId, $year)
    {
        if (!static::hasWaiver($userId, $year)) {
            static::create([
                'user_id' => $userId,
                'year' => $year,
                'updated_by' => Auth::id(),
            ]);
        }
    }

    public static function fetchWaiver($userId, $year)
    {
        return static::where('user_id', '=', $userId)
            ->where('year', '=', $year)
            ->first();
    }
}
