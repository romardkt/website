<?php

namespace Cupa\Http\Controllers\League;

use Cupa\Http\Controllers\Controller;
use Cupa\Http\Requests\LeagueAddRequest;
use Cupa\Models\League;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function add($season, Request $request)
    {
        $days = Config::get('cupa.days');
        $types = Config::get('cupa.leagueTypes');
        $prevLeagues = League::fetchOldLeagues($season);
        $seasons = Config::get('cupa.distinctSeasons');
        $years = [
            0 => 'Select Year',
            date('Y') => date('Y'),
            date('Y') + 1 => date('Y') + 1,
        ];
        $initial = $request->old('directors');
        $isYouth = ($season == 'youth') ? 1 : 0;

        return view('leagues.add', compact('season', 'days', 'types', 'prevLeagues', 'years', 'initial', 'seasons', 'isYouth'));
    }

    public function postAdd($season, LeagueAddRequest $request)
    {
        $input = $request->all();
        $input['directors'] = explode(',', $input['directors']);

        if ($input['league_type'] == 1) {
            $league = League::createLeagueFromLeague($input['year'], $input['copy']);
        } else {
            $league = League::createBlankLeague($input, ($season == 'youth') ? 1 : 0);
        }

        Session::flash('msg-success', $league->displayName().' created');

        return ($season == 'youth') ? redirect()->route('youth_leagues', [$league->slug]) : redirect()->route('league', [$league->slug]);
    }

    public function archive($slug)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('archive', $league);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirect()->route('leagues');
        }

        $league->is_archived = ($league->is_archived == 1) ? 0 : 1;
        $league->save();
        $status = ($league->is_archived == 1) ? ' archived' : ' un-archived';

        Session::flash('msg-success', $league->displayName().' '.$status);

        return redirect()->route('league', [$league->slug]);
    }
}
