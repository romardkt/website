<?php

namespace Cupa;

class Officer extends Eloquent
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
