<?php

namespace Cupa;

class TournamentFeed extends Eloquent
{
    protected $table = 'tournament_feeds';
    protected $fillable = [
        'tournament_id',
        'title',
        'content',
    ];
}
