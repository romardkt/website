<?php

namespace Cupa;

class Scholarship extends Eloquent
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
