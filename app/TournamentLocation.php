<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class TournamentLocation extends Model
{
    protected $table = 'tournament_locations';
    protected $fillable = [
        'tournament_id',
        'title',
        'link',
        'street',
        'city',
        'state',
        'zip',
        'phone',
        'other',
    ];

    public function tournament()
    {
        return $this->belongsTo('Cupa\Tournament');
    }

    public function address()
    {
        if ($this->street !== null) {
            return $this->street.'<br/>'.$this->city.', '.$this->state.' '.$this->zip.'<br/>';
        }
    }
}
