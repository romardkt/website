<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class Minute extends Model
{
    protected $table = 'minutes';
    protected $fillable = [
        'start',
        'end',
        'location',
        'pdf',
    ];
}
