<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    protected $table = 'scholarships';
    protected $fillable = [
        'scholarship',
        'name',
        'email',
        'document',
        'comments',
    ];
}
