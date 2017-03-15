<?php

namespace Cupa\Http\Controllers\League;

use Cupa\Http\Controllers\Controller;
use Cupa\Http\Requests\LeagueCoachEmailRequest;
use Cupa\Http\Requests\LeagueCoachRequest;
use Cupa\Http\Requests\LeagueEditRequest;
use Cupa\Models\League;
use Cupa\Models\LeagueLocation;
use Cupa\Models\LeagueMember;
use Cupa\Models\LeagueQuestion;
use Cupa\Models\Location;
use Cupa\Models\UserRequirement;
use Cupa\Models\UserWaiver;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class EditController extends Controller
{
    public function handle(LeagueEditRequest $request, $slug, $type)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirect()->route('leagues');
        }

        $prefix = ($request->method() == 'GET') ? 'e' : 'postE';

        $type = $this->convertType($type);

        return $this->{$prefix.'dit'.$type}($league, $request);
    }

    private function convertType($type)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $type)));
    }

    private function editDescription($league)
    {
        $isYouth = $league->is_youth;

        return view('leagues.edit.description', compact('league', 'isYouth'));
    }

    private function postEditDescription($league, LeagueEditRequest $request)
    {
        $input = $request->all();
        $league->description = $input['description'];
        $league->save();

        Session::flash('msg-success', 'League description updated');

        return redirect()->route('league', [$league->slug]);
    }

    private function editInformation($league, $request)
    {
        if ($request->has('directors')) {
            $initial = $request->old('directors');
        } else {
            $directors = [];
            foreach ($league->directors() as $director) {
                $directors[] = $director->user->id;
            }
            $initial = implode(',', $directors);
        }
        $locations = Location::fetchForSelect();

        $selectedLocations = [];
        foreach ($league->locations as $location) {
            $selectedLocations[$location->type] = $location;
        }

        $isYouth = $league->is_youth;

        return view('leagues.edit.information', compact('league', 'locations', 'initial', 'isYouth', 'selectedLocations'));
    }

    private function postEditInformation($league, LeagueEditRequest $request)
    {
        $selectedLocations = [];
        foreach ($league->locations as $location) {
            $selectedLocations[$location->type] = $location;
        }

        $input = $request->all();

        $input['directors'] = explode(',', $input['directors']);
        $input['draft_location_id'] = ($input['draft_location_id'] == 0) ? null : $input['draft_location_id'];
        $input['tournament_location_id'] = ($input['tournament_location_id'] == 0) ? null : $input['tournament_location_id'];
        $input['is_league'] = 1;

        foreach (['league', 'draft', 'tournament'] as $type) {
            $location = null;
            if (isset($selectedLocations[$type]) && !isset($input['is_'.$type])) {
                //var_dump('Deleting Location ' . $type);
                $location = $selectedLocations[$type];
                $location->delete();
                continue;
            } elseif (isset($selectedLocations[$type])) {
                //var_dump('Updating Location ' . $type);
                $location = $selectedLocations[$type];
            } elseif (!empty($input[$type.'_location_id'])) {
                //var_dump('Creating Location ' . $type);
                $location = new LeagueLocation();
                $location->league_id = $league->id;
                $location->type = $type;
            }

            if ($location) {
                $location->location_id = $input[$type.'_location_id'];
                $location->begin = convertDate($input[$type.'_start_date'].' '.$input[$type.'_start_time']);
                $location->end = convertDate($input[$type.'_end_date'].' '.$input[$type.'_end_time']);
                $location->save();
            }
        }

        LeagueMember::updateMembers($league->id, null, $input['directors'], 'director');

        Session::flash('msg-success', 'League Information updated');
        if ($league->is_youth) {
            return redirect()->route('youth_leagues', [$league->slug]);
        } else {
            return redirect()->route('league', [$league->slug]);
        }
    }

    private function editRegistration($league, $request)
    {
        $locations = Location::fetchForSelect();
        $registration = $league->registration;
        $limits = $league->limits;

        $isYouth = $league->is_youth;

        return view('leagues.edit.registration', compact('league', 'locations', 'isYouth'));
    }

    private function postEditRegistration($league, $request)
    {
        $input = $request->all();
        $registration = $league->registration;
        $limits = $league->limits;

        $registration->cost = $input['cost'];
        $registration->cost_female = (empty($input['cost_female'])) ? null : $input['cost_female'];
        $registration->begin = convertDate($input['start_date'].' '.$input['start_time']);
        $registration->end = convertDate($input['end_date'].' '.$input['end_time']);
        $registration->save();

        $limits->male = ($input['male'] != '') ? $input['male'] : null;
        $limits->female = ($input['female'] != '') ? $input['female'] : null;
        $limits->total = ($input['total'] != '') ? $input['total'] : null;
        $limits->save();

        Session::flash('msg-success', 'League registration updated');

        return redirect()->route('league', [$league->slug]);
    }

    private function editRegistrationQuestions(League $league, $request)
    {
        $currentQuestions = json_decode($league->registration()->first()->questions);
        $questions = LeagueQuestion::fetchQuestions($currentQuestions);
        $allQuestions = LeagueQuestion::fetchAllQuestions($currentQuestions);

        $isYouth = $league->is_youth;

        return view('leagues.edit.registration_questions', compact('league', 'questions', 'allQuestions', 'isYouth'));
    }

    private function postEditRegistrationQuestions(League $league, $request)
    {
        $input = $request->all();
        $league->updateQuestion($input['question'], $input['type']);

        return response()->json(['status' => 'success']);
    }

    private function editSettings($league, $request)
    {
        $isYouth = $league->is_youth;
        $days = [
            'Sunday' => 'Sunday',
            'Monday' => 'Monday',
            'Tuesday' => 'Tuesday',
            'Wednesday' => 'Wednesday',
            'Thursday' => 'Thursday',
            'Friday' => 'Friday',
            'Saturday' => 'Saturday',
        ];
        $seasons = [
            'winter' => 'Winter',
            'spring' => 'Spring',
            'summer' => 'Summer',
            'fall' => 'fall',
        ];

        return view('leagues.edit.settings', compact('league', 'isYouth', 'days', 'seasons'));
    }

    private function postEditSettings($league, $request)
    {
        $input = $request->all();
        $input['slug'] = trim($league->year.' '.ucwords($league->season).' '.$input['name']);
        if (empty($input['name'])) {
            $input['slug'] .= ' '.$league->day;
        }
        $input['slug'] = str_slug($input['slug']);

        $league->slug = $input['slug'];
        $league->date_visible = (empty($input['date_visible'])) ? null : convertDate($input['date_visible'], 'Y-m-d 00:00:00');
        $league->season = $input['season'];
        $league->day = $input['day'];
        $league->name = (empty($input['name'])) ? null : $input['name'];
        $league->override_email = (empty($input['override_email'])) ? null : $input['override_email'];
        $league->user_teams = (empty($input['user_teams'])) ? 0 : 1;
        $league->has_pods = (empty($input['has_pods'])) ? 0 : 1;
        $league->is_youth = (empty($input['is_youth'])) ? 0 : 1;
        $league->has_registration = (empty($input['has_registration'])) ? 0 : 1;
        $league->has_waitlist = (empty($input['has_waitlist'])) ? 0 : 1;
        $league->default_waitlist = (empty($input['default_waitlist'])) ? 0 : 1;
        $league->save();

        Session::flash('msg-success', 'League settings updated');

        return redirect()->route('league', [$league->slug]);
    }

    public function coachesEdit($slug, LeagueMember $member)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('coach', $league, $member);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirect()->route('youth_leagues');
        }
        $requirements = json_decode(UserRequirement::fetchOrCreateRequirements($member->user_id, $league->year)->requirements, true);
        $hiddenReqs = Config::get('cupa.coachingRequirements');
        unset($hiddenReqs['manual']);
        unset($hiddenReqs['rules']);

        if (Gate::denies('edit', $league) && $member->user_id != Auth::id()) {
            Session::flash('msg-error', 'You may only edit your own coaching requirements');

            return redirect()->route('league_coaches', [$slug]);
        }

        return view('leagues.edit.coaches_edit', compact('league', 'member', 'requirements', 'hiddenReqs'));
    }

    public function postCoachesEdit($slug, LeagueMember $member, LeagueCoachRequest $request)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('coach', $league, $member);
        $input = $request->all();

        $user = $member->user;
        $profile = $user->profile;

        $user->first_name = $input['first_name'];
        $user->last_name = $input['last_name'];
        $user->email = $input['email'];
        $user->save();

        $profile->phone = $input['phone'];
        $profile->save();

        if (isset($input['waiver'])) {
            $user->signWaiver($league->year);
        } else {
            if ($user->hasWaiver($league->year)) {
                UserWaiver::toggleWaiver($user->id, $league->year);
            }
        }

        $requirements = json_decode(UserRequirement::fetchOrCreateRequirements($member->user_id, $league->year)->requirements, true);
        $hiddenReqs = Config::get('cupa.coachingRequirements');
        unset($hiddenReqs['manual']);
        unset($hiddenReqs['rules']);

        foreach (Config::get('cupa.coachingRequirements') as $req => $text) {
            if (Gate::allows('edit', $league, $member) || !in_array($req, array_keys($hiddenReqs))) {
                $requirements[$req] = (isset($input[$req])) ? 1 : 0;
            }
        }

        UserRequirement::updateRequirements($member->user_id, $league->year, $requirements);
        Session::flash('msg-success', 'Coach updated');

        return redirect()->route('league_coaches', [$slug]);
    }

    public function coachesEmail($slug)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);

        return view('leagues.edit.coaches_email', compact('league'));
    }

    public function postCoachesEmail($slug, LeagueCoachEmailRequest $request)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);

        $input = $request->all();
        $league = League::fetchBySlug($slug);

        $coaches = LeagueMember::fetchAllLeagueMembers($league->id, ['coach', 'assistant_coach'], 'team');
        foreach ($coaches as $coach) {
            if ($coach->user->coachingRequirements($league->year)) {
                $reqs = json_decode($coach->user->coachingRequirements($league->year)->requirements, true);
            } else {
                $reqs = [];
            }

            if (is_array($reqs) && in_array(0, array_values($reqs))) {
                Mail::send('emails.league_coaches_email', ['coach' => $coach, 'data' => $input, 'requirements' => $reqs, 'league' => $league], function ($m) use ($input, $coach) {
                    if (App::environment() == 'prod') {
                        $m->to($coach->user->email);
                    } else {
                        $m->to('kcin1018@gmail.com', 'Nick Felicelli');
                    }

                    $m->subject($input['subject'])
                        ->replyTo($input['from'], $input['name']);
                });
            }
        }

        Session::flash('msg-success', 'Email messages sent');

        return redirect()->route('league_coaches', [$league->slug]);
    }
}
