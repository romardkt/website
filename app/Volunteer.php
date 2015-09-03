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
}
