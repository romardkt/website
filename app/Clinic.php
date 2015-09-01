<?php

namespace Cupa;

class Clinic extends Eloquent
{
    protected $table = 'clinics';
    protected $fillable = [
        'type',
        'name',
        'display',
        'content',
    ];
}
