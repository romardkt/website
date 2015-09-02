<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;
use Gate;

class Tournament extends Model
{
    protected $table = 'tournaments';
    protected $fillable = [
        'name',
        'year',
        'display_name',
        'divisions',
        'override_email',
        'location_id',
        'tenative_date',
        'start',
        'end',
        'description',
        'schedule',
        'cost',
        'use_bid',
        'use_paypal',
        'bid_due',
        'paypal',
        'is_visible',
    ];

    public function contacts()
    {
        return $this->hasMany('Cupa\TournamentMember')->orderBy('weight', 'asc');
    }

    /**
     * This will return all of the tournaments based on the division passed in.  It will
     * not filter by division if null is passed in.
     *
     * @param  string   The division to filter on or null
     *
     * @return array The array of tournaments.
     */
    public static function fetchAllCurrent($division = null)
    {
        // build the base query
        $select = static::orderBy('start', 'DESC')
                        ->orderBy('name');

        // limit by division given if not null
        if ($division !== null) {
            $select->where('divisions', 'LIKE', '%'.$division.'%');
        }

        // filter the array based on authorization
        $tournaments = [];
        foreach ($select->get() as $tournament) {
            if (!isset($tournaments[$tournament->name]) && (Gate::allows('show', $tournament) || ($tournament->is_visible == 1))) {
                $tournaments[$tournament->name] = $tournament;
            }
        }

        // return the filtered array
        return $tournaments;
    }
}
