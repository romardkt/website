<?php

namespace Cupa;

class EmailList extends Eloquent
{
    protected $table = 'email_lists';
    protected $fillable = [
        'email',
        'name',
    ];
}
