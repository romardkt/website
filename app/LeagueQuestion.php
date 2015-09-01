<?php

namespace Cupa;

class LeagueQuestion extends Eloquent
{
    protected $table = 'league_questions';
    protected $fillable = [
        'name',
        'title',
        'type',
        'answers',
    ];
}
