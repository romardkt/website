<?php

namespace Cupa\Http\Controllers\League;

use Cupa\Http\Controllers\Controller;
use Cupa\Http\Requests\LeagueScheduleRequest;
use Cupa\Http\Requests\LeagueTeamRequest;
use Cupa\League;
use Cupa\LeagueGame;
use Cupa\LeagueGameTeam;
use Cupa\LeagueMember;
use Cupa\LeagueQuestion;
use Cupa\LeagueTeam;
use Cupa\Page;
use Cupa\UserBalance;
use Cupa\UserWaiver;
use DateTime;
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
        $this->authorize('edit', $league);
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
        $this->authorize('edit', $league);
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

    public function markAll($slug, $week, $status)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);

        foreach (LeagueGame::where('week', '=', $week)->where('league_id', '=', $league->id)->get() as $game) {
            $game->status = $status;
            $game->save();
        }

        return redirect()->to(route('league_schedule', [$league->slug]).'#week'.$week);
    }

    public function shirts($slug)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirect()->route('leagues');
        }

        $data = $league->fetchColorCounts();

        return view('leagues.manage.shirts', compact('league', 'data'));
    }

    public function shirtsDownload($slug)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirect()->route('leagues');
        }

        $data = $league->fetchColorCounts();
        $file = storage_path().'/app/'.str_slug($league->displayName().' '.(new DateTime())->format('Y-m-d').' shirts').'.csv';

        $fp = fopen($file, 'w');
        if ($fp) {
            $line = ['color'];
            foreach (array_slice($data, 0, 1)[0]['sizes'] as $abbr => $counts) {
                $line[] = ucfirst($abbr);
            }
            fputcsv($fp, $line);

            foreach ($data as $sizes) {
                $line = [ucwords($sizes['color'])];

                foreach ($sizes['sizes'] as $counts) {
                    $line[] = $counts;
                }

                fputcsv($fp, $line);
            }
            fclose($fp);

            return response()->download($file);
        }

        Session::flash('msg-error', 'Error downloading file');

        return redirect()->route('league_shirts');
    }

    public function emergency($slug)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirect()->route('leagues');
        }

        $contacts = $league->fetchEmergencyContacts();

        return view('leagues.manage.emergency', compact('league', 'contacts'));
    }

    public function emergencyDownload($slug)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirect()->route('leagues');
        }

        $contacts = $league->fetchEmergencyContacts();
        $file = storage_path().'/app/'.str_slug($league->displayName().' '.(new DateTime())->format('Y-m-d').' emergency contacts').'.csv';

        $fp = fopen($file, 'w');
        if ($fp) {
            $line = ['player', 'contacts'];
            fputcsv($fp, $line);

            foreach ($contacts as $player => $contact) {
                $line = [$player];

                $contactLine = [];
                foreach ($contact as $data) {
                    $contactLine[] = $data['name'].' ('.$data['phone'].')';
                }
                $line[] = implode(', ', $contactLine);
                fputcsv($fp, $line);
            }

            fclose($fp);

            return response()->download($file);
        }

        Session::flash('msg-error', 'Error downloading file');

        return redirect()->route('league_shirts');
    }

    public function requests($slug)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirect()->route('leagues');
        }

        if (!$league->user_teams) {
            Session::flash('msg-error', 'This league does not support user selectable teams');

            return redirect()->route('league_teams', [$league->slug]);
        }

        $requests = $league->fetchAllRequests();
        $leagueTeams = $league->fetchTeamsForSelect();

        return view('leagues.manage.requests', compact('league', 'requests', 'leagueTeams'));
    }

    public function requestsAccept($slug, $memberId)
    {
        $member = LeagueMember::find($memberId);
        $this->authorize('edit', $member->league);
        if ($member) {
            $answers = json_decode($member->answers, true);
            if ($member->league_team_id === null && isset($answers['user_teams'])) {
                $member->league_team_id = $answers['user_teams'];
                $member->save();
                Session::flash('msg-success', 'Added player to team');
            } else {
                Session::flash('msg-error', 'Could not add player to team');
            }

            return redirect()->route('league_requests', [$slug]);
        }
    }

    public function players($slug)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirect()->route('leagues');
        }

        $players = LeagueMember::fetchAnswersByLeague($league->id);

        return view('leagues.manage.players', compact('league', 'players'));
    }

    public function playersDownload($slug)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirect()->route('leagues');
        }

        $players = LeagueMember::fetchAnswersByLeague($league->id);
        $file = storage_path().'/app/'.str_slug($league->displayName().' '.(new DateTime())->format('Y-m-d').' players').'.csv';

        $header = [
            'email',
            'first_name',
            'last_name',
            'gender',
            'age',
            'phone',
            'nickname',
            'height',
            'level',
            'experience',
            'waiver',
            'paid',
            'team_name',
        ];

        $fp = fopen($file, 'w');
        if ($fp) {
            $line = $header;
            $questions = json_decode($league->registration->questions, true);
            foreach ($questions as $question) {
                list($questionId, $required) = explode('-', $question);
                $questionObject = LeagueQuestion::find($questionId);
                switch ($questionObject->name) {
                    case 'user_teams':
                        break;
                    default:
                        $line[] = $questionObject->name;
                        break;
                }
            }
            fputcsv($fp, $line);

            foreach ($players as $player) {
                $line = [];
                foreach ($header as $col) {
                    switch ($col) {
                        case 'age':
                            $line[] = displayAge($player['birthday']);
                            break;
                        case 'height':
                            $line[] = displayHeight($player['height']);
                            break;
                        case 'experience':
                            $line[] = displayExperience($player['experience']);
                            break;
                        case 'paid':
                            $line[] = ($player['paid'] == 1) ? 'Yes' : 'No';
                            break;
                        case 'waiver':
                            $line[] = ($player['waiver'] !== null) ? 'Signed' : 'Not Signed';
                            break;
                        case 'email':
                            $line[] = (empty($player['parent_email'])) ? $player['email'] : $player['parent_email'];
                            break;
                        case 'phone':
                            $line[] = (empty($player['parent_phone'])) ? $player['phone'] : $player['parent_phone'];
                            break;
                        default:
                            $line[] = $player[$col];
                            break;
                    }
                }
                foreach ($questions as $question) {
                    list($questionId, $required) = explode('-', $question);
                    $questionObject = LeagueQuestion::find($questionId);
                    if ($questionObject->name == 'user_teams') {
                        continue;
                    }

                    switch ($questionObject->type) {
                        case 'boolean':
                            if (is_numeric($player['answers'][$questionObject->name])) {
                                $line[] = ($player['answers'][$questionObject->name] == 1) ? 'Yes' : 'No';
                            } else {
                                $line[] = $player['answers'][$questionObject->name];
                            }
                            break;
                        default:
                            if (isset($player['answers'][$questionObject->name])) {
                                $line[] = $player['answers'][$questionObject->name];
                            } else {
                                $line[] = 'Unknown';
                            }
                            break;
                    }
                }
                fputcsv($fp, $line);
            }
            fclose($fp);

            return response()->download($file);
        }

        Session::flash('msg-error', 'Error downloading file');

        return redirect()->route('league_players');
    }

    public function status($slug, $all = false)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirect()->route('leagues');
        }

        $statuses = LeagueMember::fetchAnswersByLeague($league->id);
        foreach ($statuses as $i => $status) {
            if (!$all && ($status['paid'] == 1 && $status['waiver'] !== null && $status['balance'] < 1)) {
                unset($statuses[$i]);
            }
        }

        Session::set('waiver_redirect', route('home'));

        return view('leagues.manage.status', compact('league', 'statuses', 'all'));
    }

    public function statusDownload($slug, $all)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirect()->route('leagues');
        }

        $statuses = LeagueMember::fetchAnswersByLeague($league->id);
        $file = storage_path().'/app/'.str_slug($league->displayName().' '.(new DateTime())->format('Y-m-d').' statuses').'.csv';

        $fp = fopen($file, 'w');
        if ($fp) {
            $line = ['player', 'paid', 'waiver', 'balance', 'outstanding_leagues'];
            fputcsv($fp, $line);

            foreach ($statuses as $status) {
                if ($all == 'outstanding' && ($status['paid'] == 1 && $status['waiver'] !== null && $status['balance'] < 1)) {
                    continue;
                }

                $leagues = [];
                if (!empty($status['balance_leagues'])) {
                    foreach (League::whereIn('id', explode(',', $status['balance_leagues']))->get() as $league) {
                        $leagues[] = $league->displayName();
                    }
                }

                $line = [
                    $status['first_name'].' '.$status['last_name'],
                    ($status['paid'] == 1) ? 'Yes' : 'No',
                    ($status['waiver'] === null) ? 'No' : 'Yes',
                    ($status['balance'] === null) ? '0' : $status['balance'],
                    (empty($leagues)) ? '' : implode(', ', $leagues),
                ];

                fputcsv($fp, $line);
            }

            fclose($fp);

            return response()->download($file);
        }

        Session::flash('msg-error', 'Error downloading file');

        return redirect()->route('league_shirts');
    }

    public function manage($slug, Request $request)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirect()->route('leagues');
        }

        $initial = $request->old('add_players');
        $members = $league->getAllPlayers();
        $teams = $league->teams;

        return view('leagues.manage.manage', compact('league', 'members', 'teams', 'initial'));
    }

    public function postManage($slug, Request $request)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);

        $input = $request->all();
        if (isset($input['add'])) {
            $member = LeagueMember::find($input['member_id']);
            $member->league_team_id = $input['team_id'];
            $member->save();

            return json_encode(['success' => 'ok']);
        } elseif (isset($input['remove'])) {
            $member = LeagueMember::find($input['member_id']);
            $member->league_team_id = null;
            $member->save();

            return json_encode(['success' => 'ok']);
        } elseif (isset($input['delete'])) {
            $member = LeagueMember::find($input['member_id']);
            $member->delete();

            return json_encode(['success' => 'ok']);
        } elseif (isset($input['add_players'])) {
            $players = explode(',', $input['players']);
            if (count($players) && $players[0] != '') {
                foreach ($players as $player) {
                    LeagueMember::create([
                        'league_id' => $league->id,
                        'user_id' => $player,
                        'position' => 'player',
                        'updated_by' => Auth::id(),
                    ]);
                }
            } else {
                Session::flash('msg-error', 'Please select at least one player to add');
            }
        }

        return redirect()->route('league_manage', [$slug]);
    }

    public function waitlist($slug)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirect()->route('leagues');
        }

        $players = LeagueMember::fetchAllLeagueMembers($league->id, 'waitlist', 'created_at');

        return view('leagues.manage.waitlist', compact('league', 'players'));
    }

    public function waitlistAccept($slug, LeagueMember $member)
    {
        $this->authorize('edit', $member->league);

        $member->position = 'player';
        $member->save();

        Session::flash('msg-success', 'Player moved from wailist to player');

        return redirect()->route('league_waitlist', [$slug]);
    }

    public function waitlistDownload($slug)
    {
        $league = League::fetchBySlug($slug);
        $this->authorize('edit', $league);
        if (!$league) {
            Session::flash('msg-error', 'Could not find league');

            return redirect()->route('leagues');
        }

        $players = LeagueMember::fetchAllLeagueMembers($league->id, 'waitlist', 'created_at');
        $file = storage_path().'/app/'.str_slug($league->displayName().' '.(new DateTime())->format('Y-m-d').' waitlist').'.csv';

        $header = [
            'first_name',
            'last_name',
            'gender',
            'age',
            'email',
            'phone',
            'nickname',
            'height',
            'level',
            'experience',
            'waiver',
            'paid',
            'registered_at',
        ];

        $fp = fopen($file, 'w');
        if ($fp) {
            $line = $header;
            $questions = json_decode($league->registration->questions, true);
            foreach ($questions as $question) {
                list($questionId, $required) = explode('-', $question);
                $questionObject = LeagueQuestion::find($questionId);
                switch ($questionObject->name) {
                    case 'user_teams':
                        break;
                    default:
                        $line[] = $questionObject->name;
                        break;
                }
            }
            fputcsv($fp, $line);

            foreach ($players as $player) {
                $line = [];
                foreach ($header as $col) {
                    if (isset($player->user->$col)) {
                        $line[] = $player->user->$col;
                    } elseif (isset($player->user->profile->$col)) {
                        switch ($col) {
                            case 'height':
                                $line[] = displayHeight($player->user->profile->height);
                                break;
                            case 'experience':
                                $line[] = displayExperience($player->user->profile->experience);
                                break;
                            default:
                                $line[] = $player->user->profile->$col;
                                break;
                        }
                    } elseif ($col == 'email') {
                        $line[] = $player->user->parentObj->email;
                    } elseif ($col == 'age') {
                        $line[] = displayAge($player->user->birthday);
                    } elseif ($col == 'waiver') {
                        $line[] = ($player->user->hasWaiver($league->year) == 1) ? 'Yes' : 'No';
                    } elseif ($col == 'registered_at') {
                        $line[] = $player->created_at;
                    } else {
                        $line[] = $player->$col;
                    }
                }

                $answers = json_decode($player->answers);
                foreach ($questions as $question) {
                    list($questionId, $required) = explode('-', $question);
                    $questionObject = LeagueQuestion::find($questionId);

                    if ($questionObject->name == 'user_teams') {
                        continue;
                    }

                    if (isset($answers->{$questionObject->name})) {
                        switch ($questionObject->type) {
                            case 'boolean':
                                $line[] = ($answers->{$questionObject->name} == 1) ? 'Yes' : 'No';
                                break;
                            default:
                                $line[] = $answers->{$questionObject->name};
                                break;
                        }
                    } else {
                        $line[] = 'Unknown';
                    }
                }
                fputcsv($fp, $line);
            }
            fclose($fp);

            return response()->download($file);
        }

        Session::flash('msg-error', 'Error downloading file');

        return redirect()->route('league_waitlist');
    }

    public function statusToggle(Request $request)
    {
        $input = $request->all();
        $member = LeagueMember::find($input['member']);
        if ($member) {
            $league = League::find($member->league_id);

            if ($input['type'] == 'paid') {
                $member->paid = ($member->paid == 1) ? 0 : 1;
                $member->save();
            } elseif ($input['type'] == 'waiver') {
                UserWaiver::toggleWaiver($member->user_id, $league->year);
            }

            $balance = UserBalance::find($member->user_id);
            if ($balance) {
                $balance = $balance->balance;
            }

            return response()->json(['status' => 'success', 'paid' => $member->paid, 'waiver' => UserWaiver::hasWaiver($member->user_id, $league->year), 'balance' => $balance]);
        }

        return response()->json(['status' => 'error', 'msg' => 'No Player Found']);
    }
}
