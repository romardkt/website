<?php

namespace Cupa\Http\Controllers;

use Illuminate\Support\MessageBag;
use Cupa\Http\Requests\ManageUserRequest;
use Cupa\Http\Requests\LeaguePlayersRequest;
use Cupa\Http\Requests\LoadLeagueRequest;
use Cupa\Http\Requests\DuplicatesRequest;
use Cupa\Http\Requests\FormAddEditRequest;
use Cupa\User;
use Cupa\UserBalance;
use Cupa\League;
use Cupa\LeagueMember;
use Cupa\CupaForm;
use Session;
use Auth;

class ManageController extends Controller
{
    public function manage()
    {
        return view('manage.manage');
    }

    public function users()
    {
        return view('manage.users');
    }

    public function users_detail(ManageUserRequest $request)
    {
        $user = User::find($request->get('user_id'));

        return view('manage.users_detail', compact('user'));
    }

    public function impersonate($userId)
    {
        $user = User::find($userId);
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

    public function load_league_teams(LoadLeagueRequest $request)
    {
        $leagueId = $request->get('league_id');
        $league = League::find($leagueId);

        return response()->json($league->fetchTeamsForSelect());
    }

    public function duplicates()
    {
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
        $forms = CupaForm::fetchAllForms();

        return view('manage.forms', compact('forms'));
    }

    public function formsAdd()
    {
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
}
