<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    protected $table = 'volunteers';
    protected $fillable = [
        'user_id',
        'involvement',
        'primary_interest',
        'experience',
    ];

    public function user()
    {
        return $this->belongsTo('Cupa\User');
    }

    public static function fetchAllVolunteers()
    {
        return static::join('users', 'users.id', '=', 'volunteers.user_id')
            ->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
            ->leftJoin('users AS u2', 'u2.id', '=', 'users.parent')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->select('users.*', 'u2.email AS parent', 'user_profiles.phone', 'volunteers.involvement', 'volunteers.primary_interest', 'volunteers.other', 'volunteers.experience')
            ->get();
    }

    public static function fetchAllVolunteersForDownload()
    {
        $data = [];
        $data[] = ['Email', 'First Name', 'Last Name', 'Gender', 'Birthday', 'Phone', 'Years Involved', 'Primary Interests', 'Other', 'Experience'];
        foreach (static::fetchAllVolunteers() as $volunteer) {
            $data[] = [
                (empty($volunteer->email)) ? $volunteer->parent : $volunteer->email,
                $volunteer->first_name,
                $volunteer->last_name,
                $volunteer->gender,
                $volunteer->birthday,
                $volunteer->phone,
                $volunteer->involvement,
                $volunteer->primary_interest,
                $volunteer->other,
                $volunteer->experience,
            ];
        }

        return $data;
    }
}
