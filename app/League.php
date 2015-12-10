<?php

namespace Cupa;

use Auth;
use Carbon\Carbon;
use DB;
use DateTime;
use Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

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

    public function games()
    {
        return $this->hasMany('Cupa\LeagueGame');
    }

    public function members()
    {
        return $this->hasMany('Cupa\LeagueMember');
    }

    public function teams()
    {
        return $this->hasMany('Cupa\LeagueTeam')->orderBy('name');
    }

    public function directors()
    {
        return LeagueMember::where('position', '=', 'director')
            ->where('league_id', '=', $this->id)
            ->get();
    }

    public function coaches()
    {
        return LeagueMember::where('position', 'LIKE', '%coach')
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

    public static function fetchCurrentSeason()
    {
        return Config::get('cupa.seasons')[date('n')];
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
            return $league->is_archived == 0 || Gate::allows('show', $league);
        });
    }

    public static function fetchAllLeagues($type = null, $season = null)
    {
        $now = Carbon::now();

        $select = static::with(['locations', 'limits', 'games', 'members', 'registration', 'teams', 'counts'])
            ->from('leagues AS l')
            ->leftJoin('league_locations AS ll', function ($join) {
                $join->on('ll.league_id', '=', 'l.id')
                     ->where('ll.type', '=', 'league');
            })
            ->leftJoin('league_registrations AS lr', 'lr.league_id', '=', 'l.id')
            ->leftJoin('league_members AS lm', 'lm.league_id', '=', 'l.id')
            ->where('l.type', '=', 'league')
            ->where('lm.position', '=', 'director')
            ->orderBy('is_archived', 'asc')
            ->orderBy('lr.begin', 'desc')
            ->orderBy('ll.begin', 'desc')
            ->groupBy('l.id')
            ->select('l.*', DB::raw('GROUP_CONCAT(lm.user_id) AS directors'));

        switch ($type) {
            case 'youth':
                $select->where('l.is_youth', '=', 1);
                break;
            case 'adult':
                $select->where('l.is_youth', '=', 0);
                break;
        }

        if ($season !== null) {
            $select->where('l.season', '=', $season);
        }

        if (Auth::check() && Gate::allows('is-manager')) {
            $select->where(function ($query) use ($now) {
                $query->where('lm.user_id', '=', Auth::id())
                    ->orWhere(function ($query2) use ($now) {
                        $query2->where('l.is_archived', '=', 0)
                            ->whereNotNull('l.date_visible')
                            ->where('l.date_visible', '<', $now);
                    });
            });
        } else {
            // not logged in hide
            $select->where('l.is_archived', '=', 0)
                ->whereNotNull('l.date_visible')
                ->where('l.date_visible', '<', $now);
        }

        return $select->paginate(10);
    }

    public static function fetchAllHash()
    {
        $data = [];
        foreach (static::with('registration')->orderBy('id', 'asc')->get() as $league) {
            $data[$league->id] = [
                'name' => $league->displayName(),
                'cost' => $league->registration->cost,
                'slug' => $league->slug,
            ];
        }

        return $data;
    }

    public static function fetchAllForSelect()
    {
        $leagues = [];
        foreach (static::fetchAllLeagues() as $league) {
            $leagues[$league->id] = $league->displayName();
        }

        return $leagues;
    }

    public function fetchTeamsForSelect()
    {
        $teams = [];
        foreach ($this->teams as $team) {
            $teams[$team->id] = $team->name;
        }

        return $teams;
    }

    public static function fetchOldLeagues($season)
    {
        $select = static::where('year', '>=', date('Y') - 1)
            ->orderBy('year', 'DESC');

        if ($season == 'youth') {
            $select->where('is_youth', '=', 1);
        } else {
            $select->where('season', '=', $season);
        }

        $leagues = [0 => 'Select League'];
        foreach ($select->get() as $league) {
            $leagues[$league->id] = $league->displayName();
        }

        return $leagues;
    }

    public static function createLeagueFromLeague($year, $oldLeagueId)
    {
        $oldLeague = self::find($oldLeagueId);

        // create slug
        $name = (empty($oldLeague->name)) ? null : $oldLeague->name;
        $slug = str_slug($year.' '.$oldLeague->season.' '.$name);
        if ($name === null) {
            $slug .= $oldLeague->day;
        }

        $league = self::create([
            'type' => 'league',
            'year' => $year,
            'season' => $oldLeague->season,
            'day' => $oldLeague->day,
            'name' => $oldLeague->name,
            'slug' => $slug,
            'override_email' => (empty($oldLeague->override_email)) ? null : $oldLeague->override_email,
            'user_teams' => $oldLeague->user_teams,
            'has_pods' => $oldLeague->has_pods,
            'is_youth' => $oldLeague->is_youth,
            'has_waitlist' => $oldLeague->has_waitlist,
            'description' => str_replace($oldLeague->year, $year, $oldLeague->description),
            'date_visible' => null,
            'is_archived' => 0,
        ]);

        LeagueRegistration::create([
            'league_id' => $league->id,
            'begin' => str_replace($oldLeague->year, $year, $oldLeague->registration->begin),
            'end' => str_replace($oldLeague->year, $year, $oldLeague->registration->end),
            'cost' => $oldLeague->registration->cost,
            'questions' => $oldLeague->registration->questions,
        ]);

        foreach (LeagueMember::fetchLeagueMembers($oldLeague->id, 'director') as $director) {
            LeagueMember::create([
                'league_id' => $league->id,
                'user_id' => $director->user_id,
                'position' => 'director',
                'updated_by' => Auth::id(),
            ]);
        }

        foreach ($oldLeague->locations as $location) {
            LeagueLocation::create([
                'league_id' => $league->id,
                'type' => $location->type,
                'location_id' => $location->location_id,
                'begin' => str_replace($oldLeague->year, $year, $location->begin),
                'end' => str_replace($oldLeague->year, $year, $location->end),
                'num_fields' => $oldLeague->num_fields,
                'link' => $oldLeague->link,
            ]);
        }

        LeagueLimit::create([
            'league_id' => $league->id,
            'male' => $oldLeague->limits->male,
            'female' => $oldLeague->limits->female,
            'total' => $oldLeague->limits->total,
            'teams' => $oldLeague->limits->teams,
            'players' => $oldLeague->limits->players,
        ]);

        return $league;
    }

    public static function createBlankLeague($data, $isYouth)
    {
        // create slug
        $name = (empty($data['name'])) ? null : $data['name'];
        $slug = str_slug($data['year'].' '.$data['season'].' '.$name);
        if ($name === null) {
            $slug .= ' '.$data['day'];
        }

        $league = self::create([
            'type' => $data['type'],
            'year' => $data['year'],
            'season' => $data['season'],
            'day' => $data['day'],
            'name' => $name,
            'slug' => $slug,
            'override_email' => $data['override_email'],
            'user_teams' => 0,
            'has_pods' => 0,
            'is_youth' => $isYouth,
            'has_waitlist' => 1,
            'description' => '<p>Please replace this with a league description</p>',
            'date_visible' => null,
            'is_archived' => 0,
        ]);

        $dateTime = new DateTime();
        LeagueRegistration::create([
            'league_id' => $league->id,
            'begin' => $dateTime->modify('+1 month')->format('Y-m-d H:i:s'),
            'end' => $dateTime->modify('+4 weeks')->format('Y-m-d H:i:s'),
            'cost' => 50,
            'questions' => json_encode(Config::get('cupa.leagueDefaultQuestions')),
        ]);

        LeagueMember::updateMembers($league->id, null, $data['directors'], 'director');

        $location = Location::fetchLocation('TBD', 'Cincinnati', 'OH', '45209');
        LeagueLocation::create([
            'league_id' => $league->id,
            'type' => 'league',
            'location_id' => $location->id,
            'begin' => $dateTime->modify('+1 week')->format('Y-m-d H:i:s'),
            'end' => $dateTime->modify('+10 week')->format('Y-m-d H:i:s'),
            'num_fields' => null,
            'link' => null,
        ]);

        LeagueLimit::create([
            'league_id' => $league->id,
            'male' => null,
            'female' => null,
            'total' => null,
            'teams' => null,
            'players' => null,
        ]);

        return $league;
    }

    public function getRegistrationData()
    {
        $registration = $this->registration;
        $now = (new DateTime())->format('Y-m-d H:i:s');
        $data = [];
        $data['limits'] = $this->limits->toArray();
        $data['counts'] = $this->counts;

        if ($now >= $registration->begin && $now <= $registration->end) {
            $data['status'] = ['text-success' => 'Open'];

            if ($data['limits']['total'] !== null && $data['counts']->total >= $data['limits']['total']) {
                $data['status'] = ($this->has_waitlist == 1) ? ['text-success' => 'Waitlist'] : ['text-danger' => 'Full'];
            }

            if (Auth::check()) {
                $gender = strtolower(Auth::user()->gender);
                if ($gender != 'not specified' && $data['limits'][$gender] !== null && $data['counts']->$gender >= $data['limits'][$gender]) {
                    $data['status'] = ($this->has_waitlist == 1) ? ['text-success' => 'Waitlist'] : ['text-danger' => 'Full'];
                }
            }
        } elseif ($now < $registration->begin) {
            $data['status'] = ['text-info' => 'Not Open Yet'];
        } else {
            $data['status'] = ['text-danger' => 'Closed'];
        }

        return $data;
    }

    public static function fetchBySlug($slug)
    {
        return static::where('slug', '=', $slug)
            ->first();
    }

    public function teamsByRank()
    {
        return LeagueTeam::with('record')
            ->where('league_id', '=', $this->id)
            ->leftJoin('league_team_records AS ltr', 'ltr.league_team_id', '=', 'league_teams.id')
            ->orderBy('ltr.wins', 'desc')
            ->orderBy('ltr.diff', 'desc')
            ->orderBy('ltr.points_for', 'desc')
            ->orderBy('ltr.losses', 'asc')
            ->get();
    }

    public function fetchContacts()
    {
        $contacts = [];
        $user = Auth::user();

        if ($this->override_email !== null) {
            $contacts['all-directors'] = [$this->override_email];
        } else {
            $contacts['all-directors'] = LeagueMember::fetchLeagueMembers($this->id, 'director');
        }

        if (Gate::allows('edit', $this)) {
            if ($this->is_youth == 1) {
                $contacts['all-coaches'] = LeagueMember::fetchLeagueMembers($this->id, ['coach', 'assistant-coach']);
                $contacts['head-coaches'] = LeagueMember::fetchLeagueMembers($this->id, 'coach');
            } else {
                $contacts['all-captains'] = LeagueMember::fetchAllLeagueMembers($this->id, 'captain');
            }
        }

        $member = LeagueMember::fetchMemberNoTeam($this->id, (isset($user->id)) ? $user->id : null, ['player', 'waitlist']);
        if (Gate::allows('edit', $this) || Auth::check() && $user->isLeagueMember($this->id)) {
            if ($member && $member->league_team_id !== null) {
                if ($this->is_youth == 1) {
                    $contacts['my-coaches'] = LeagueMember::fetchLeagueMembers($this->id, ['coach', 'assistant-coach'], $member->league_team_id);
                } else {
                    $contacts['my-captains'] = LeagueMember::fetchLeagueMembers($this->id, 'captain', $member->league_team_id);
                }
                $contacts['my-team'] = LeagueMember::fetchLeagueMembers($this->id, 'player', $member->league_team_id);
            }
        }

        if (Gate::allows('edit', $this)) {
            $contacts['unpaid-players'] = LeagueMember::fetchUnpaidLeagueMembers($this->id);
            if (count($contacts['unpaid-players']) < 1) {
                unset($contacts['unpaid-players']);
            }

            $contacts['waitlisted-players'] = LeagueMember::fetchAllLeagueMembers($this->id, 'players');
            if (count($contacts['waitlisted-players']) < 1) {
                unset($contacts['waitlisted-players']);
            }
            $contacts['all-players'] = LeagueMember::fetchAllLeagueMembers($this->id, 'player');

            foreach ($this->teams as $team) {
                $contacts[str_slug($team->name)] = LeagueMember::fetchLeagueMembers($this->id, 'player', $team->id);
            }
        }

        return $contacts;
    }

    public function fetchRegistrationType($session)
    {
        $counts = $this->counts;
        $limits = $this->limits;
        $registration = $this->registration;
        $now = (new DateTime())->format('Y-m-d H:i:s');

        if ($limits->total !== null && $counts->total >= $limits->total) {
            return ($this->has_waitlist == 1) ? 'waitlist' : 'full';
        }

        $gender = (isset($session->registrant)) ? $session->registrant->gender : Auth::user()->gender;
        if ($limits->$gender !== null && $counts->$gender >= $limits->$gender) {
            return ($this->has_waitlist == 1) ? 'waitlist' : 'full';
        }

        if ($registration->end < $now && $this->has_waitlist == 1) {
            return 'waitlist';
        }

        return 'registration';
    }

    public function updateQuestion($questionId, $type)
    {
        $registration = $this->registration;
        $questions = json_decode($registration->questions, true);

        if ($type == 'add-question') {
            $questions[] = $questionId.'-1';
        } else {
            foreach ($questions as $i => $question) {
                list($aQuestionId, $required) = explode('-', $question);

                if ($aQuestionId == $questionId) {
                    switch ($type) {
                        case 'required':
                            $required = ($required == 1) ? 0 : 1;
                            $questions[$i] = $questionId.'-'.$required;
                            break;
                        case 'remove':
                            unset($questions[$i]);
                            $questions = array_values($questions);
                            break;
                        case 'move-up':
                            if ($i > 0) {
                                $tmp = $questions[$i - 1];
                                $questions[$i - 1] = $questions[$i];
                                $questions[$i] = $tmp;
                                unset($tmp);
                                $questions = array_values($questions);
                            }
                            break;
                        case 'move-down':
                            if ($i < count($questions) - 2) {
                                $tmp = $questions[$i + 1];
                                $questions[$i + 1] = $questions[$i];
                                $questions[$i] = $tmp;
                                unset($tmp);
                                $questions = array_values($questions);
                            }
                            break;
                    }
                }
            }
        }

        $registration->questions = json_encode($questions);
        $registration->save();
    }

    public function fetchColorCounts()
    {
        $tmpCount = [];
        $counts = [];
        foreach (Config::get('cupa.shirts') as $abbr => $name) {
            $tmpCount[$abbr] = 0;
        }
        $tmpCount['total'] = 0;

        foreach ($this->teams as $team) {
            $counts[$team->id]['sizes'] = $tmpCount;
            $counts[$team->id]['code'] = $team->color_code;
            $counts[$team->id]['color'] = $team->color;
        }

        $data = LeagueMember::fetchAnswersByLeague($this->id);
        foreach ($data as $row) {
            if (isset($row['answers']['shirt']) && isset($counts[$row['league_team_id']]['sizes'][$row['answers']['shirt']])) {
                ++$counts[$row['league_team_id']]['sizes'][$row['answers']['shirt']];
                ++$counts[$row['league_team_id']]['sizes']['total'];
            }
        }

        return $counts;
    }

    public function fetchEmergencyContacts()
    {
        $result = LeagueMember::leftJoin('users AS u', 'u.id', '=', 'league_members.user_id')
            ->leftJoin('user_contacts AS uc', 'uc.user_id', '=', 'league_members.user_id')
            ->where('league_id', '=', $this->id)
            ->where('position', '=', 'player')
            ->orderBy('u.last_name')
            ->orderBy('u.first_name')
            ->select('u.first_name', 'u.last_name', 'uc.name', 'uc.phone')
            ->get();

        $contacts = [];
        foreach ($result as $row) {
            $contacts[$row['first_name'].' '.$row['last_name']][] = [
                'name' => $row['name'],
                'phone' => $row['phone'],
            ];
        }

        return $contacts;
    }

    public function fetchAllRequests()
    {
        $results = LeagueMember::fetchAnswersByLeague($this->id);

        $data = [];
        foreach ($results as $row) {
            if ($row['league_team_id'] === null && isset($row['answers']['user_teams'])) {
                $data[$row['first_name'].' '.$row['last_name']] = [
                    'member' => $row['id'],
                    'requested' => [
                        'id' => $row['answers']['user_teams'],
                        'name' => LeagueTeam::find($row['answers']['user_teams'])->name,
                    ],
                    'registered_at' => $row['registered_at'],
                ];
            }
        }

        return $data;
    }

    public function getAllPlayers($teamId = null)
    {
        return LeagueMember::fetchAllLeagueMembers($this->id, 'player');
    }
}
