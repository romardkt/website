<?php

namespace Cupa;

class League extends Eloquent
{
    protected $table = 'leagues';
    protected $fillable = [
        'type',
        'year',
        'season',
        'day',
        'name',
        'slug',
        'override_email',
        'has_pods',
        'is_youth',
        'description',
        'date_visible',
        'is_archived',
    ];
}
