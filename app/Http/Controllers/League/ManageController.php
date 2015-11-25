<?php

namespace Cupa\Http\Controllers\League;

use Cupa\Http\Controllers\Controller;
use Cupa\Http\Requests\LeagueScheduleRequest;
use Cupa\Http\Requests\LeagueTeamRequest;
use Cupa\League;
use Cupa\LeagueGame;
use Cupa\LeagueGameTeam;
use Cupa\LeagueMember;
use Cupa\LeagueTeam;
use Cupa\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;
use Intervention\Image\Facades\Image;

class ManageController extends Controller
{
    public function teamAdd($slug, Request $request)
    {
        $league = League::fetchBySlug($slug);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirect()->route('leagues');
        }

        $initialC = $request->old('captains');
        $initialHc = $request->old('asst_coaches');
        $initialAc = $request->old('asst_coaches');

        return view('leagues.manage.team_add', compact('league', 'initialC', 'initialHc', 'initialAc'));
    }

    public function postTeamAdd($slug, LeagueTeamRequest $request)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);
        $input = $request->all();

        if ($league->is_youth) {
            $input['head_coaches'] = (empty($input['head_coaches'])) ? null : explode(',', $input['head_coaches']);
            $input['asst_coaches'] = (empty($input['asst_coaches'])) ? null : explode(',', $input['asst_coaches']);
        } else {
            $input['captains'] = (empty($input['captains'])) ? null : explode(',', $input['captains']);
        }

        $team = LeagueTeam::create([
            'league_id' => $league->id,
            'name' => $input['name'],
            'color' => $input['color'],
            'color_code' => $input['color_code'],
            'updated_by' => Auth::id(),
        ]);

        if ($request->hasFile('logo')) {
            $filePath = public_path().'/data/league_teams/'.time().'-'.$team->id.'.jpg';
            $img = Image::cache(function ($image) use ($filePath, $request) {
                return $image->make($request->file('logo')->getRealPath())->resize(400, 400)->orientate()->save($filePath);
            });
            $team->logo = str_replace(public_path(), '', $filePath);
            $team->save();
        }

        if ($league->is_youth) {
            LeagueMember::updateMembers($league->id, $team->id, $input['head_coaches'], 'coach');
            if (count($input['asst_coaches'])) {
                LeagueMember::updateMembers($league->id, $team->id, $input['asst_coaches'], 'assistant_coach');
            }
        } else {
            LeagueMember::updateMembers($league->id, $team->id, $input['captains'], 'captain');
        }

        Session::flash('msg-success', 'Team `'.$team->name.'` created');

        return redirect()->route('league_teams', [$league->slug]);
    }

    public function teamEdit($slug, LeagueTeam $team, Request $request)
    {
        $league = League::fetchBySlug($slug);
        $page = Page::fetchBy('route', 'leagues_'.$league->season);
        $actions = null;

        if ($league->is_youth) {
            if ($request->has('head_coaches')) {
                $initialHc = $request->old('head_coaches');
            } else {
                $headCoaches = [];
                foreach ($team->headCoaches() as $headCoach) {
                    $headCoaches[] = $headCoach->user->id;
                }
                $initialHc = implode(',', $headCoaches);
            }

            if ($request->has('asst_coaches')) {
                $initialAc = $request->old('asst_coaches');
            } else {
                $asstCoaches = [];
                foreach ($team->asstCoaches() as $asstCoach) {
                    $asstCoaches[] = $asstCoach->user->id;
                }
                $initialAc = implode(',', $asstCoaches);
            }
        } else {
            if ($request->has('captains')) {
                $initialC = $request->old('captains');
            } else {
                $captains = [];
                foreach ($team->captains() as $captain) {
                    $captains[] = $captain->user->id;
                }
                $initialC = implode(',', $captains);
            }
        }

        return view('leagues.manage.team_edit', compact('page', 'actions', 'league', 'team', 'initialC', 'initialHc', 'initialAc'));
    }

    public function postTeamEdit($slug, LeagueTeam $team, LeagueTeamRequest $request)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);
        $input = $request->all();

        $input['logo'] = $request->file('logo');

        if ($league->is_youth) {
            $input['head_coaches'] = (empty($input['head_coaches'])) ? null : explode(',', $input['head_coaches']);
            $input['asst_coaches'] = (empty($input['asst_coaches'])) ? null : explode(',', $input['asst_coaches']);
        } else {
            $input['captains'] = explode(',', $input['captains']);
        }

        $team->name = $input['name'];
        $team->color = $input['color'];
        $team->color_code = $input['color_code'];

        if (!$request->hasFile('logo') && isset($input['logo_remove']) && $input['logo_remove'] == 1) {
            $filePath = public_path().$team->logo;
            if ($team->logo != '/data/users/default.png' && file_exists($filePath)) {
                unlink($filePath);
            }
            $team->logo = '/data/users/default.png';
        } elseif ($request->hasFile('logo')) {
            $filePath = public_path().'/data/league_teams/'.time().'-'.$team->id.'.jpg';
            $img = Image::cache(function ($image) use ($filePath, $request) {
                return $image->make($request->file('logo')->getRealPath())->resize(400, 400)->orientate()->save($filePath);
            });
            $team->logo = str_replace(public_path(), '', $filePath);
        }

        $team->save();

        if ($league->is_youth) {
            LeagueMember::updateMembers($league->id, $team->id, $input['head_coaches'], 'coach');
            if (count($input['asst_coaches'])) {
                LeagueMember::updateMembers($league->id, $team->id, $input['asst_coaches'], 'assistant_coach');
            }
        } else {
            LeagueMember::updateMembers($league->id, $team->id, $input['captains'], 'captain');
        }

        Session::flash('msg-success', 'Team `'.$team->name.'` updated');

        return redirect()->route('league_teams', [$league->slug]);
    }

    public function teamRemove($slug, LeagueTeam $team)
    {
        // clear all coaches
        LeagueMember::clearCoaches($team->id);

        // remove the team
        $name = $team->name;
        $team->delete();

        Session::flash('msg-success', 'Team `'.$name.'` removed');

        return redirect()->route('league_teams', [$slug]);
    }

    public function scheduleAdd($slug)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirect()->route('leagues');
        }

        $leagueTeams = $league->fetchTeamsForSelect();
        if (count($leagueTeams) < 2) {
            Session::flash('msg-error', 'Please create at least 2 teams before scheduling');

            return redirect()->route('league_teams', [$league->slug]);
        }

        return view('leagues.manage.schedule_add', compact('leagueTeams', 'league'));
    }

    public function postScheduleAdd($slug, LeagueScheduleRequest $request)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);

        $input = $request->all();

        try {
            $game = LeagueGame::create([
                'league_id' => $league->id,
                'played_at' => convertDate($input['played_at_date'].' '.$input['played_at_time']),
                'week' => $input['week'],
                'field' => $input['field'],
                'status' => $input['status'],
            ]);
        } catch (\Exception $e) {
            $errors = new MessageBag();
            $errors->add('played_at', 'Please enter only the start time');

            return redirect()->route('league_schedule_add', [$league->slug])->withInput()->withErrors($errors);
        }

        if ($input['status'] == 'game_on') {
            foreach (['home', 'away'] as $type) {
                $teamVar = $type.'_team';
                $scoreVar = $type.'_score';

                if ($league->has_pods) {
                    foreach ($input[$teamVar] as $teamId) {
                        $team = LeagueGameTeam::create([
                            'league_game_id' => $game->id,
                            'type' => $type,
                            'league_team_id' => $teamId,
                            'score' => (empty($input[$scoreVar])) ? 0 : $input[$scoreVar],
                        ]);
                    }
                } else {
                    $team = LeagueGameTeam::create([
                        'league_game_id' => $game->id,
                        'type' => $type,
                        'league_team_id' => array_shift($input[$teamVar]),
                        'score' => (empty($input[$scoreVar])) ? 0 : $input[$scoreVar],
                    ]);
                }
            }
        }

        Session::flash('msg-success', 'Game added');

        return redirect()->route('league_schedule', [$league->slug]);
    }

    public function scheduleEdit($slug, LeagueGame $game)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);
        $leagueTeams = $league->fetchTeamsForSelect();
        $homeTeams = $game->team('home');
        $awayTeams = $game->team('away');

        $away = [];
        foreach ($awayTeams as $aTeam) {
            $away[] = $aTeam->league_team_id;
        }

        $home = [];
        foreach ($homeTeams as $hTeam) {
            $home[] = $hTeam->league_team_id;
        }

        return view('leagues.manage.schedule_edit', compact('game', 'leagueTeams', 'league', 'homeTeams', 'awayTeams'));
    }

    public function postScheduleEdit($slug, LeagueGame $game, LeagueScheduleRequest $request)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);

        $input = $request->all();
        $game->played_at = convertDate($input['played_at_date'].' '.$input['played_at_time']);
        $game->week = $input['week'];
        $game->field = $input['field'];
        $game->status = $input['status'];
        $game->save();

        if ($input['status'] == 'game_on') {
            LeagueGameTeam::updateTeams($game->id, $input);
        }

        Session::flash('msg-success', 'Game updated');

        return redirect()->route('league_schedule', [$league->slug]);
    }

    public function scheduleRemove($slug, LeagueGame $game)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);
        $game->delete();

        Session::flash('msg-success', 'Game removed');

        return redirect()->route('league_schedule', [$league->slug]);
    }
}
