<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    protected $table = 'clinics';
    protected $fillable = [
        'type',
        'name',
        'display',
        'content',
    ];
}
