<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class EmailList extends Model
{
    protected $table = 'email_lists';
    protected $fillable = [
        'email',
        'name',
    ];
}
