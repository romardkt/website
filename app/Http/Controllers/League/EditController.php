<?php

namespace Cupa\Http\Controllers\League;

use Cupa\Http\Controllers\Controller;
use Cupa\Http\Requests\LeagueEditRequest;
use Cupa\League;
use Cupa\LeagueLocation;
use Cupa\LeagueMember;
use Cupa\LeagueQuestion;
use Cupa\Location;
use Illuminate\Support\Facades\Session;

class EditController extends Controller
{
    public function handle($slug, $type, LeagueEditRequest $request)
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

    private function edit_registration_questions($league)
    {
        $currentQuestions = json_decode($league->registration()->first()->questions);
        $questions = LeagueQuestion::fetchQuestions($currentQuestions);
        $allQuestions = LeagueQuestion::fetchAllQuestions($currentQuestions);

        if (Request::method() == 'POST') {
            $input = $request->all();

            $rules = ['question' => 'required'];

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return response()->json(['status' => 'error']);
            }

            $league->updateQuestion($input['question'], $input['type']);

            return response()->json(['status' => 'success']);
        }

        $isYouth = $league->is_youth;

        return view('leagues.edit_registration_questions', compact('league', 'questions', 'allQuestions', 'isYouth'));
    }

    private function editRegistrationQuestions($league, $request)
    {
        $currentQuestions = json_decode($league->registration()->first()->questions);
        $questions = LeagueQuestion::fetchQuestions($currentQuestions);
        $allQuestions = LeagueQuestion::fetchAllQuestions($currentQuestions);

        $isYouth = $league->is_youth;

        return view('leagues.edit.registration_questions', compact('league', 'questions', 'allQuestions', 'isYouth'));
    }

    private function postEditRegistrationQuestions($league, $request)
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
            $input['slug'] .=  ' '.$league->day;
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
}
