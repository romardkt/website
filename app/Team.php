<?php

namespace Cupa;

class Team extends Eloquent
{
    protected $table = 'teams';
    protected $fillable = [
        'name',
        'type',
        'display_name',
        'menu',
        'override_email',
        'facebook',
        'twitter',
        'begin',
        'end',
        'website',
        'description',
        'updated_by',
    ];
}
