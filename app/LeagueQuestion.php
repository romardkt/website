<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class LeagueQuestion extends Model
{
    protected $table = 'league_questions';
    protected $fillable = [
        'name',
        'title',
        'type',
        'answers',
    ];
}
