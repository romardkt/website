<?php

namespace Cupa;

class Post extends Eloquent
{
    protected $table = 'posts';
    protected $fillable = [
        'category',
        'title',
        'slug',
        'image',
        'link',
        'content',
        'posted_by',
        'post_at',
        'remove_at',
        'is_featured',
        'is_visible',
    ];
}
