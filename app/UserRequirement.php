<?php

namespace Cupa;

class UserRequirement extends Eloquent
{
    protected $table = 'user_requirements';
    protected $fillable = [
        'user_id',
        'year',
        'requirements',
    ];
}
