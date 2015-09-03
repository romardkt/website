<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class OfficerPosition extends Model
{
    protected $table = 'officer_positions';
    protected $fillable = [
        'name',
        'weight',
    ];
}
