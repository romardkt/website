<?php

namespace Cupa\Models;

use Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function feed()
    {
        return $this->hasMany(TournamentFeed::class)->orderBy('created_at', 'desc');
    }

    public function contacts()
    {
        return $this->hasMany(TournamentMember::class)->with('user')->orderBy('weight', 'asc');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function locations()
    {
        return $this->hasMany(TournamentLocation::class);
    }

    /**
     * This will return all of the tournaments based on the division passed in.  It will
     * not filter by division if null is passed in.
     *
     * @param  string   The division to filter on or null
     *
     * @return array The array of tournaments
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

    public static function fetchTournament($name, $year)
    {
        $select = static::where('name', '=', $name)
            ->orderBy('year', 'desc');

        if ($year !== null) {
            $select->where('year', '=', $year);
        }

        return $select->first();
    }

    public static function fetchDistinctTournaments()
    {
        $tournaments = [];
        foreach (DB::table('tournaments')->distinct()->select('name')->orderBy('name')->get() as $tournament) {
            $tournaments[$tournament->name] = $tournament->name;
        }

        return $tournaments;
    }

    public function fetchTeams($division)
    {
        return TournamentTeam::where('division', '=', $division)
            ->where('tournament_id', '=', $this->id)
            ->orderBy('name')
            ->get();
    }
}
