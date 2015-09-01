<?php

namespace Cupa;

class Role extends Eloquent
{
    protected $table = 'roles';
    protected $fillable = [
        'name',
        'weight',
    ];
}
