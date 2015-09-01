<?php

namespace Cupa;

class Page extends Eloquent
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
