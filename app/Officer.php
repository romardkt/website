<?php

namespace Cupa;

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
}
