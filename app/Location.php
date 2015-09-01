<?php

namespace Cupa;

class Location extends Eloquent
{
    protected $table = 'locations';
    protected $fillable = [
        'name',
        'street',
        'city',
        'state',
        'zip',
        'comments',
    ];
    protected $_addNames = [
        'Otto Armleder Memorial Park',
        'Winton Woods Frisbee Golf Course',
        'Heritage Oak Park',
    ];
}
