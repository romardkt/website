<?php

namespace Cupa\Http\Controllers;

use DateTime;
use Cupa\File;
use Cupa\User;
use Cupa\League;
use Cupa\CupaForm;
use Cupa\Volunteer;
use Cupa\UserBalance;
use Cupa\LeagueMember;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
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
}
