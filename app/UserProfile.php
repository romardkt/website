<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $table = 'user_profiles';
    protected $fillable = [
        'user_id',
        'phone',
        'nickname',
        'height',
        'level',
        'experience',
    ];

    public function isComplete()
    {
        foreach ($this->fillable as $test) {
            if (!in_array($test, ['nickname']) && $this->$test === null) {
                return false;
            }
        }

        return true;
    }
}
