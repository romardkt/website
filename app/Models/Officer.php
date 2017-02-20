<?php

namespace Cupa\Models;

use Illuminate\Database\Eloquent\Model;

class Officer extends Model
{
    protected $table = 'officers';
    protected $fillable = [
        'user_id',
        'officer_position_id',
        'started',
        'stopped',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function position()
    {
        return $this->belongsTo(OfficerPosition::class, 'officer_position_id');
    }

    public static function fetchAllCurrent()
    {
        return static::with(array('position', 'user'))
            ->join('officer_positions', 'officers.officer_position_id', '=', 'officer_positions.id')
            ->join('users', 'officers.user_id', '=', 'users.id')
            ->orderBy('officer_positions.weight', 'asc')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->select('officers.*')
            ->whereNull('stopped')
            ->get();
    }

    public static function fetchAllPast()
    {
        return static::with(array('position', 'user'))
            ->join('officer_positions', 'officers.officer_position_id', '=', 'officer_positions.id')
            ->join('users', 'officers.user_id', '=', 'users.id')
            ->orderBy('officer_positions.weight', 'asc')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->select('officers.*')
            ->whereNotNull('stopped')
            ->get();
    }
}
