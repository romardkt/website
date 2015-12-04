<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class UserRequirement extends Model
{
    protected $table = 'user_requirements';
    protected $fillable = [
        'user_id',
        'year',
        'requirements',
    ];

    public static function fetchOrCreateRequirements($userId, $year)
    {
        $row = static::where('user_id', '=', $userId)
            ->where('year', '=', $year)
            ->first();

        if (!$row) {
            $row = static::create([
                'user_id' => $userId,
                'year' => $year,
                'requirements' => '',
            ]);
        }

        return $row;
    }

    public static function updateRequirements($userId, $year, $reqs)
    {
        $row = static::fetchOrCreateRequirements($userId, $year);

        if (is_array($reqs)) {
            $reqs = json_encode($reqs);
        }

        $row->requirements = $reqs;
        $row->save();
    }
}
