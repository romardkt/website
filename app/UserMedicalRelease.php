<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class UserMedicalRelease extends Model
{
    protected $table = 'user_medical_releases';
    protected $fillable = [
        'user_id',
        'year',
        'data',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo('Cupa\User');
    }

    public static function updateOrCreateRelease($user, $year, $by, $data)
    {
        $release = self::where('user_id', '=', $user->id)
            ->where('year', '=', $year)
            ->first();

        if (!$release) {
            // create release
            $release = self::create([
                'user_id' => $user->id,
                'year' => $year,
                'data' => $data,
                'updated_by' => $by,
            ]);
        } else {
            // update the release
            $release->data = $data;
            $release->save();
        }

        return $release;
    }

    public static function fetchRelease($userId, $year)
    {
        return self::where('user_id', '=', $userId)
            ->where('year', '=', $year)
            ->first();
    }
}
