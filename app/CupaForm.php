<?php

namespace Cupa;

class CupaForm extends Eloquent
{
    protected $table = 'cupa_forms';
    protected $fillable = [
        'year',
        'name',
        'slug',
        'location',
        'extension',
        'size',
        'md5',
    ];
}
