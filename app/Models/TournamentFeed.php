<?php

namespace Cupa\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentFeed extends Model
{
    protected $table = 'tournament_feeds';
    protected $fillable = [
        'tournament_id',
        'title',
        'content',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}
