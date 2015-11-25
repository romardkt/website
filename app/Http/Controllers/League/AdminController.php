<?php

namespace Cupa\Http\Controllers\League;

use Cupa\Http\Controllers\Controller;
use Cupa\Http\Requests\LeagueAddRequest;
use Cupa\League;
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
}
