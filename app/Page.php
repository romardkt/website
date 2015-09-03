<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';
    protected $fillable = [
        'route',
        'display',
        'content',
        'is_visible',
        'weight',
        'created_by',
        'updated_by',
    ];
}
