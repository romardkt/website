<?php

namespace Cupa;

class OfficerPosition extends Eloquent
{
    protected $table = 'officer_positions';
    protected $fillable = [
        'name',
        'weight',
    ];
}
