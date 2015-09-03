<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class TournamentFeed extends Model
{
    protected $table = 'tournament_feeds';
    protected $fillable = [
        'tournament_id',
        'title',
        'content',
    ];
}
