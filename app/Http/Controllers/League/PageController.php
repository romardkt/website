<?php

namespace Cupa\Http\Controllers\League;

use Cupa\Http\Controllers\Controller;
use Cupa\Http\Requests\LeagueEmailRequest;
use Cupa\League;
use Cupa\LeagueGame;
use Cupa\LeagueMember;
use Cupa\LeagueTeam;
use Cupa\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class PageController extends Controller
{
    public function leagues(Request $request)
    {
        $season = str_replace('leagues', '', Route::currentRouteName());
        if (empty($season)) {
            return redirect()->route('leagues_'.League::fetchCurrentSeason());
        }
        $season = substr($season, 1);

        $page = Page::fetchBy('route', 'leagues_'.$season);
        $actions = 'league_add';

        $leagues = League::fetchAllLeagues('adult', $season);

        return view('leagues.leagues', compact('page', 'actions', 'leagues', 'season'));
    }

    public function league($slug)
    {
        $league = League::fetchBySlug($slug);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirect()->route('leagues');
        }

        if ($league->is_youth) {
            $menu = Page::fetchMenu();
            View::share('pageRoot', 'youth');
            View::share('subMenus', $menu['youth']);
            unset($menu);
        }

        $registration = $league->getRegistrationData();
        $page = Page::fetchBy('route', 'leagues_'.$league->season);
        $actions = null;

        return view('leagues.league', compact('page', 'actions', 'league', 'registration'));
    }

    public function leagueOld($season, $slug)
    {
        $league = League::fetchByOldSlug($slug, $season);
        Log::info('Trying to redirect to '.$season.' '.$slug);
        if ($league === null) {
            App::abort(404);
        }

        return redirect()->route('league', [$league->slug]);
    }

    public function teams($slug)
    {
        $league = League::fetchBySlug($slug);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirect()->route('leagues');
        }

        $page = Page::fetchBy('route', 'leagues_'.$league->season);
        $actions = null;

        return view('leagues.teams', compact('page', 'actions', 'league'));
    }

    public function teamPlayers(Request $request)
    {
        $input = $request->all();
        $players = [];
        $team = LeagueTeam::find($input['team_id']);

        foreach (LeagueMember::fetchAllMembers('player', $input['team_id']) as $player) {
            $answers = json_decode($player->answers);
            $club = null;
            if (isset($answers->club_player)) {
                $club = (preg_match('/no/i', $answers->club_player)) ? '' : 'Club Player';
            }

            $level = displayLevel($player->user->profile->level).' <span class="label label-danger">'.$club.'</span>';

            $players[] = [
                'id' => $player->user->id,
                'name' => $player->user->fullname(),
                'height' => displayHeight($player->user->profile->height),
                'level' => $level,
            ];
        }

        return response()->json([
            'status' => 'success',
            'title' => $team->name.' players',
            'body' => view('leagues.team_players', compact('players'))->render(),
        ]);
    }

    public function teamRecord(Request $request)
    {
        $input = $request->all();
        $records = LeagueGame::fetchRecord($input['team_id']);
        $team = LeagueTeam::find($input['team_id']);

        return response()->json([
            'status' => 'success',
            'title' => $team->name.' record',
            'body' => view('leagues.team_record', compact('records'))->render(),
        ]);
    }

    public function schedule($slug)
    {
        $league = League::fetchBySlug($slug);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirect()->route('leagues');
        }

        $page = Page::fetchBy('route', 'leagues_'.$league->season);
        $actions = null;

        $games = LeagueGame::fetchAllGames($league->id);
        $weeks = LeagueGame::fetchAllWeeks($league->id);

        return view('leagues.schedule', compact('page', 'actions', 'league', 'games', 'weeks'));
    }

    public function email($slug)
    {
        $league = League::fetchBySlug($slug);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirec()->route('leagues');
        }

        $tos = $league->fetchContacts();

        return view('leagues.email', compact('league', 'tos'));
    }

    public function postEmail($slug, LeagueEmailRequest $request)
    {
        $input = $request->all();
        $league = League::fetchBySlug($slug);
        $tos = $league->fetchContacts();
        $input['to'] = (empty($input['to'])) ? null : explode(',', $input['to']);

        $emails = [];
        foreach ($input['to'] as $to) {
            foreach ($tos[$to] as $member) {
                if (!empty($member->user->email)) {
                    $emails[$member->user->email] = $member->user->email;
                } elseif (isset($member->user->parent) && $member->user->parent !== null) {
                    $emails[$member->user->parentObj->email] = $member->user->parentObj->email;
                }
            }
        }

        Mail::send('emails.league_email', ['data' => $input, 'emails' => $emails], function ($m) use ($input, $league, $emails) {
            if (App::environment() == 'prod') {
                foreach ($emails as $email) {
                    $m->bcc($email);
                }

                $m->to('webmaster@cincyultimate.org', 'CUPA Web System');
            } else {
                $m->to('kcin1018@gmail.com', 'Nick Felicelli');
            }

            $m->subject($input['subject'])
              ->replyTo($input['from'], $input['name']);
        });

        Session::flash('msg-success', 'Email message sent');

        return redirect()->route('league_email', [$league->slug]);
    }
}
