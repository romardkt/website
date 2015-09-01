<?php

namespace Cupa;

class Minute extends Eloquent
{
    protected $table = 'minutes';
    protected $fillable = [
        'start',
        'end',
        'location',
        'pdf',
    ];
}
