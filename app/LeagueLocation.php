<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class LeagueLocation extends Model
{
    protected $table = 'league_locations';
    protected $fillable = [
        'league_id',
        'type',
        'location_id',
        'begin',
        'end',
        'begin',
        'num_fields',
        'link',
    ];

    public function location()
    {
        return $this->belongsTo('Cupa\Location');
    }
}
