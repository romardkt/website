<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class UserRequirement extends Model
{
    protected $table = 'user_requirements';
    protected $fillable = [
        'user_id',
        'year',
        'requirements',
    ];
}
