<?php

namespace Cupa\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use Cupa\Models\File;
use Cupa\Models\User;
use Cupa\Models\League;
use Cupa\Models\CupaForm;
use Cupa\Models\Volunteer;
use Cupa\Models\LeagueTeam;
use Cupa\Models\Tournament;
use Cupa\Models\UserBalance;
use Illuminate\Http\Request;
use Cupa\Models\LeagueMember;
use Cupa\Models\LeagueLocation;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Cupa\Models\VolunteerEventSignup;
use Illuminate\Support\Facades\Session;
use Cupa\Http\Requests\DuplicatesRequest;
use Cupa\Http\Requests\LoadLeagueRequest;
use Cupa\Http\Requests\ManageFileRequest;
use Cupa\Http\Requests\ManageUserRequest;
use Cupa\Http\Requests\FormAddEditRequest;
use Cupa\Http\Requests\LeaguePlayersRequest;

class ManageController extends Controller
{
    public function manage()
    {
        return view('manage.manage');
    }

    public function users()
    {
        $this->authorize('is-volunteer');

        return view('manage.users');
    }

    public function usersDetail(ManageUserRequest $request)
    {
        $user = User::find($request->get('user_id'));

        return view('manage.users_detail', compact('user'));
    }

    public function impersonate(User $user)
    {
        $this->authorize('is-admin');
        Session::put('admin_user', Auth::user());
        Auth::login($user);

        return redirect()->route('profile');
    }

    public function unpaid()
    {
        $this->authorize('is-director');
        $unpaid = UserBalance::fetchAllUnpaid();
        $leagues = League::fetchAllHash();

        return view('manage.unpaid', compact('unpaid', 'leagues'));
    }

    public function leaguePlayers()
    {
        $this->authorize('is-manager');
        $leagues = [0 => 'Select League'] + League::fetchAllForSelect();

        return view('manage.league_players', compact('leagues'));
    }

    public function postLeaguePlayers(LeaguePlayersRequest $request)
    {
        $input = $request->all();

        $member = LeagueMember::find($input['source_player']);
        $member->league_team_id = (!isset($input['to_team']) || $input['to_team'] == 0) ? null : $input['to_team'];
        $member->league_id = $input['to'];
        $member->save();

        Session::flash('msg-success', 'League player moved');

        return redirect()->route('manage_league_players');
    }

    public function loadLeagueTeams(LoadLeagueRequest $request)
    {
        $this->authorize('is-manager');
        $leagueId = $request->get('league_id');
        $league = League::find($leagueId);

        return response()->json($league->fetchTeamsForSelect());
    }

    public function duplicates()
    {
        $this->authorize('is-admin');
        $duplicates = User::fetchAllDuplicates();

        return view('manage.duplicates', compact('duplicates'));
    }

    public function postDuplicates(DuplicatesRequest $request)
    {
        $userId = $request->get('user_id');
        $user = User::find($userId);
        if ($user && ($result = $user->combineDuplicates()) === true) {
            Session::flash('msg-success', 'User merged');

            return response()->json(['status' => 'ok']);
        }

        return response()->json(['status' => 'error', 'message' => $result]);
    }

    public function forms()
    {
        $this->authorize('is-manager');
        $forms = CupaForm::fetchAllForms();

        return view('manage.forms', compact('forms'));
    }

    public function formsAdd()
    {
        $this->authorize('is-manager');

        return view('manage.forms_add');
    }

    public function postFormsAdd(FormAddEditRequest $request)
    {
        $input = $request->all();

        // check md5
        $md5 = md5_file($request->file('document')->getRealPath());
        if (!CupaForm::isUnique($md5)) {
            $errors = new MessageBag();
            $errors->add('document', 'Uploaded file is a duplicate');

            return redirect()->route('manage_forms_add')->withInput()->withErrors($errors);
        }

        $slug = str_slug($input['year'].'-'.$input['name']);
        $extension = $request->file('document')->getClientOriginalExtension();
        $documentPath = '/data/forms/';
        $document = $slug.'.'.$extension;
        $documentFull = $documentPath.$document;
        $request->file('document')->move(public_path().$documentPath, $document);

        $form = new CupaForm();
        $form->year = $input['year'];
        $form->name = $input['name'];
        $form->slug = $slug;
        $form->location = $documentFull;
        $form->size = filesize(public_path().$documentFull);
        $form->md5 = $md5;
        $form->extension = $extension;
        $form->save();

        Session::flash('msg-success', 'Form `'.$form->name.'`added');

        return redirect()->route('manage_forms');
    }

    public function formsRemove($slug)
    {
        $this->authorize('is-manager');
        $form = CupaForm::fetchBySlug($slug);
        if ($form) {
            if (file_exists(public_path().$form->location)) {
                unlink(public_path().$form->location);
            }

            $name = $form->name;
            $form->delete();

            Session::flash('msg-success', 'Form `'.$name.'`removed');
        }

        return redirect()->route('manage_forms');
    }

    public function formsEdit($slug)
    {
        $this->authorize('is-manager');
        $form = CupaForm::fetchBySlug($slug);

        return view('manage.forms_edit', compact('form'));
    }

    public function postFormsEdit(FormAddEditRequest $request, $slug)
    {
        $form = CupaForm::fetchBySlug($slug);
        $input = $request->all();
        $slug = str_slug($input['year'].'-'.$input['name']);

        if ($request->hasFile('document')) {
            // check md5
            $md5 = md5_file($request->file('document')->getRealPath());
            if (!CupaForm::isUnique($md5, $form->id)) {
                $errors = new MessageBag();
                $errors->add('document', 'Uploaded file is a duplicate');

                return redirect()->route('manage_forms_edit')->withInput()->withErrors($errors);
            }

            $extension = $request->file('document')->getClientOriginalExtension();
            $documentPath = '/data/forms/';
            $document = $slug.'.'.$extension;
            $documentFull = $documentPath.$document;
            $request->file('document')->move(public_path().$documentPath, $document);

            $form->location = $documentFull;
            $form->size = filesize(public_path().$documentFull);
            $form->md5 = $md5;
            $form->extension = $extension;
        } elseif ($slug != $form->slug) {
            // rename file
            $base = basename($form->location);
            $extension = substr($base, strrpos($base, '.') + 1);
            $target = '/data/forms/'.$slug.'.'.$extension;
            rename(public_path().$form->location, public_path().$target);
            $form->location = $target;
        }

        $form->year = $input['year'];
        $form->name = $input['name'];
        $form->slug = $slug;
        $form->save();

        Session::flash('msg-success', 'Form `'.$form->name.'`added');

        return redirect()->route('manage_forms');
    }

    public function coaches()
    {
        $this->authorize('is-manager');
        $coaches = LeagueMember::fetchAllCoaches();

        return view('manage.coaches', compact('coaches'));
    }

    public function coachesDownload()
    {
        $this->authorize('is-manager');
        $coaches = LeagueMember::fetchAllCoaches();
        $file = storage_path().'/app/'.(new DateTime())->format('Y-m-d').'-CUPA-Coaches.csv';

        $fp = fopen($file, 'w');
        if ($fp) {
            $line = ['name', 'email', 'teams'];
            fputcsv($fp, $line);

            foreach ($coaches as $coach) {
                $line = [$coach['name'], $coach['email'], $coach['teams']];
                fputcsv($fp, $line);
            }

            fclose($fp);

            return response()->download($file);
        }

        Session::flash('msg-error', 'Error downloading file');

        return redirect()->route('manage_coaches');
    }

    public function files()
    {
        $this->authorize('is-manager');
        $files = File::fetchAllFiles();

        return view('manage.files', compact('files'));
    }

    public function filesAdd()
    {
        $this->authorize('is-manager');

        return view('manage.files_add');
    }

    public function postFilesAdd(ManageFileRequest $request)
    {
        $file = $request->file('file');
        $safeName = str_slug(str_replace('.'.$file->getClientOriginalExtension(), '', $file->getClientOriginalName())).'.'.$file->getClientOriginalExtension();

        $testFile = File::fetchBy('name', $safeName);

        // check if file exists
        $md5 = md5_file($request->file('file')->getRealPath());
        if (!File::isUnique($md5) || $testFile) {
            $errors = new MessageBag();
            $errors->add('document', 'Uploaded file already exists');

            return redirect()->route('manage_files_add')->withErrors($errors);
        }

        $file->move(public_path().'/upload', $safeName);
        $newFile = File::create([
            'name' => $safeName,
            'location' => '/upload/'.$safeName,
            'md5' => $md5,
            'size' => $file->getClientSize(),
            'mime' => $file->getClientMimeType(),
        ]);

        Session::flash('msg-success', 'File uploaded');

        return redirect()->route('manage_files');
    }

    public function filesRemove(File $file)
    {
        $this->authorize('is-manager');
        $filePath = public_path().$file->location;
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $file->delete();

        Session::flash('msg-success', 'File removed');

        return redirect()->route('manage_files');
    }

    public function volunteerRemove(Volunteer $volunteer_id)
    {
        $volunteer_id->delete();

        return response()->json([]);
    }

    public function waivers()
    {
        $this->authorize('is-user');

        $user = Auth::user();
        $isManager = false;
        foreach ($user->roles->pluck('role.name') as $role) {
            if (in_array($role, ['admin', 'manager'])) {
                $isManager = true;
                break;
            }
        }

        // get all current leagues
        $now = Carbon::now();
        $leagues = LeagueLocation::join('leagues', 'leagues.id', '=', 'league_locations.league_id')
            ->where('begin', '<=', $now)
            ->where('end', '>', $now)
            ->select('leagues.*')
            ->get();

        $waivers = [];
        foreach ($leagues as $league) {
            $teamIds = [];
            $position = 'Director';

            if (!$isManager) {
                // check if director
                $director = LeagueMember::where('user_id', '=', $user->id)
                    ->where('league_id', '=', $league->id)
                    ->where('position', '=', 'director')
                    ->first();

                if (!$director) {
                    // get all team based leaders
                    $positionMembers = LeagueMember::where('user_id', '=', $user->id)
                        ->where('league_id', '=', $league->id)
                        ->whereNotIn('position', ['director', 'player', 'waitlist'])
                        ->get();

                    $position = 'Coach/Captain';

                    // add the teams
                    foreach ($positionMembers as $positionMember) {
                        $teamIds[] = $positionMember->league_team_id;
                    }
                } else {
                    $teamIds = LeagueTeam::where('league_id', '=', $league->id)
                        ->pluck('id');
                }
            } else {
                $teamIds = LeagueTeam::where('league_id', '=', $league->id)
                    ->pluck('id');
            }

            // get all the players
            $leagueMembers = LeagueMember::join('leagues', 'leagues.id', '=', 'league_members.league_id')
                ->join('users', 'users.id', '=', 'league_members.user_id')
                ->join('league_teams', 'league_teams.id', '=', 'league_members.league_team_id')
                ->whereIn('league_team_id', $teamIds)
                ->where('position', '=', 'player')
                ->select('league_members.*')
                ->orderBy('leagues.year', 'desc')
                ->orderBy('league_teams.name')
                ->orderBy('users.last_name')
                ->orderBy('users.first_name');

            foreach ($leagueMembers->get() as $leagueMember) {
                $leagueUser = $leagueMember->user;

                if (!isset($waivers[$leagueMember->league->id])) {
                    $waivers[$leagueMember->league->id] = [];
                }

                // add data
                $waivers[$leagueMember->league->id][] = [
                    'position' => $position,
                    'league' => $leagueMember->league,
                    'team' => $leagueMember->team,
                    'user' => $leagueUser,
                    'waiver' => $leagueUser->fetchWaiver($league->year),
                    'release' => $leagueUser->fetchRelease($league->year),
                ];
            }
        }

        return view('manage.waivers', compact('waivers'));
    }

    public function reports()
    {
        $this->authorize('is-manager');

        return view('manage.reports');
    }

    public function reportsData(Request $request)
    {
        $type = $request->get('type');

        switch ($type) {
            case 'league_counts_by_year':
                return LeagueMember::generateLeagueCountsByYearForChart();
            case 'leagues_by_year':
                return League::generateLeaguesByYearForChart();
            case 'volunteers_by_year':
                return VolunteerEventSignup::generateVolunteersByYearForChart();
            case 'tournaments_by_year':
                return Tournament::generateTournamentsByYearForChart();
            default:
                return response()->json([]);
        }
    }

    public function volunteers()
    {
        return view('manage.volunteers');
    }

    public function postVolunteers(Request $request)
    {
        $data = $request->input();
        $emails = (empty($data['emails'])) ? [] : explode(',', $data['emails']);
        $action = $data['action'];

        if (empty($emails)) {
            Session::flash('msg-error', 'You did not specify any emails');
            return redirect()->route('manage_volunteers');
        }

        $modifications = [];

        foreach($emails as $email) {
            $user = User::where('email', '=', $email)->first();

            // ignore invalid users
            if (!$user) {
                $modifications[$email] = '<span class="label label-danger">could not find email</span>';
                continue;
            }

            if ($action == 'remove') {
                if ($user->isVolunteer()) {
                    $modifications[$user->email] = '<span class="label label-danger">not removed</span>';
                    $user->volunteer()->first()->delete();
                    $modifications[$user->email] = '<span class="label label-success">removed</span>';
                } else {
                    $modifications[$user->email] = '<span class="label label-danger">not a volunteer</span>';
                }
            } else if ($action == 'addition') {
                if (!$user->isVolunteer()) {
                    $modifications[$user->email] = '<span class="label label-danger">not added</span>';
                    $volunteer = Volunteer::create([
                        'user_id' => $user->id,
                        'primary_interest' => 'Unknown',
                        'experience' => 'Unknown',
                    ]);
                    $volunteer->save();
                    $modifications[$user->email] = '<span class="label label-success">added</span>';
                } else {
                    $modifications[$user->email] = '<span class="label label-danger">already a volunteer</span>';
                }
            }
        }

        return redirect()->route('manage_volunteers')->withErrors($modifications);
    }
}
