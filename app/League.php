<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
use Gate;

class League extends Model
{
    protected $table = 'leagues';
    protected $fillable = [
        'type',
        'year',
        'season',
        'day',
        'name',
        'slug',
        'override_email',
        'has_pods',
        'is_youth',
        'description',
        'date_visible',
        'is_archived',
    ];

    public function registration()
    {
        return $this->hasOne('Cupa\LeagueRegistration');
    }

    public function locations()
    {
        return $this->hasMany('Cupa\LeagueLocation', 'league_id')->orderBy('begin');
    }

    public function counts()
    {
        return $this->hasOne('Cupa\LeaguePlayerCount');
    }

    public function limits()
    {
        return $this->hasOne('Cupa\LeagueLimit');
    }

    public function directors()
    {
        return LeagueMember::/*with('user')
                           ->*/where('position', '=', 'director')
                           ->where('league_id', '=', $this->id)
                           ->get();
    }

    public function displayName()
    {
        return trim($this->year.' '.ucwords($this->season).' '.$this->day.' '.$this->name);
    }

    public function status()
    {
        if ($this->has_registration == 0) {
            return 'No Registration';
        }

        $now = Carbon::now();
        $registration = $this->registration;
        $location = $this->locations()->first();
        $begin = new Carbon($location->begin);
        $end = new Carbon($location->end);
        $registrationBegin = new Carbon($registration->begin);
        $registrationEnd = new Carbon($registration->end);

        $status = 'Unknown';
        if ($now > $end) {
            $status = 'Finished';
        } elseif ($now >= $begin && $now <= $end && $now > $registrationEnd) {
            $status = 'In Progress';
        } elseif ($now >= $registration->begin && $now <= $registrationEnd) {
            $limits = $this->limits;
            $counts = $this->counts;

            $status = 'Registering';

            if ($limits->total !== null && $counts->total >= $limits->total) {
                $status = ($this->has_waitlist == 1) ? 'Waitlist' : 'Full';
            }

            if (Auth::check()) {
                $gender = strtolower(Auth::user()->gender);
                if ($limits->$gender !== null && $counts->$gender >= $limits->$gender) {
                    $status = ($this->has_waitlist == 1) ? 'Waitlist' : 'Full';
                }
            }
        } elseif ($now > $registrationEnd && $now < $begin) {
            $status = 'Reg Ended';
        } elseif ($now < $registrationBegin) {
            $status = 'Not Open';
        }

        return $status;
    }

    /**
     * This will return all of the leagues based on the current date.  It will filter all
     * of the leagues to display all of the leagues that are registering or in progress.
     *
     * @return Collection
     */
    public static function fetchAllLeaguesForHomePage()
    {
        $now = Carbon::now();

        $select = static::with(['registration', 'locations', 'counts', 'limits'])
            ->from('leagues AS l')
            ->leftJoin('league_locations AS ll', function ($join) {
                $join->on('ll.league_id', '=', 'l.id')
                    ->where('ll.type', '=', 'league');
             })
            ->leftJoin('league_registrations AS lr', 'lr.league_id', '=', 'l.id')
            ->where('l.type', '=', 'league')
            ->where('l.date_visible', '<=', $now)
            ->where('ll.end', '>=', $now)
            ->whereNotNull('l.date_visible')
            ->orderBy('lr.begin', 'desc')
            ->orderBy('ll.begin', 'desc')
            ->select('l.*');

        return $select->get()->filter(function ($league) {
            //var_dump($league->is_archived, Gate::allows('show', $league), $league->is_archived == 0 || Gate::allows('show', $league));

            return $league->is_archived == 0 || Gate::allows('show', $league);
        });
    }
}
