<?php

namespace Cupa\Http\Controllers;

use Cupa\Http\Requests\PageEditRequest;
use Cupa\Http\Requests\TeamAddEditRequest;
use Cupa\Page;
use Cupa\Team;
use Cupa\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;

class TeamsController extends Controller
{
    public function teams()
    {
        $page = Page::fetchBy('route', 'teams');
        $actions = 'add_edit';

        return view('teams.teams', compact('actions', 'page'));
    }

    public function teamsEdit()
    {
        $this->authorize('is-editor', null);
        $page = Page::fetchBy('route', 'teams');

        return view('teams.teams_edit', compact('page'));
    }

    public function postTeamsEdit(PageEditRequest $request)
    {
        $page = Page::fetchBy('route', 'teams');

        // get the posted data
        $input = $request->all();

        $page->display = $input['display'];
        $page->content = $input['content'];
        $page->save();

        Session::flash('msg-success', 'Teams page updated.');

        return redirect()->route('teams');
    }

    public function teamsAdd(Request $request)
    {
        $this->authorize('is-editor', null);
        $page = Page::fetchBy('route', 'teams');
        $initial = $request->old('captains');
        $teamTypes = array_combine(Config::get('cupa.teamTypes'), Config::get('cupa.teamTypes'));

        return view('teams.teams_add', compact('page', 'teamTypes', 'initial'));
    }

    public function postTeamsAdd(TeamAddEditRequest $requset)
    {
        // get the posted data
        $input = $request->all();
        $input['captains'] = explode(',', $input['captains']);

        $team = Team::create([
                'name' => str_slug($input['display_name']),
                'type' => (is_array($input['type'])) ? implode(', ', $input['type']) : $input['type'],
                'display_name' => $input['display_name'],
                'menu' => $input['menu'],
                'override_email' => (empty($input['override_email'])) ? null : $input['override_email'],
                'facebook' => (empty($input['facebook'])) ? null : $input['facebook'],
                'twitter' => (empty($input['twitter'])) ? null : $input['twitter'],
                'website' => (empty($input['website'])) ? null : $input['website'],
                'begin' => $input['begin'],
                'end' => (empty($input['end'])) ? null : $input['end'],
                'description' => $input['description'],
                'updated_by' => Auth::user()->id,
            ]);

        if ($request->hasFile('logo')) {
            $filePath = public_path().'/data/area_teams/'.time().'-'.$team->id.'.jpg';
            $img = Image::cache(function ($image) use ($filePath) {
                    return $image->make($request->file('logo')->getRealPath())->resize(400, 400)->orientate()->save($filePath);
                });
            $team->logo = str_replace(public_path(), '', $filePath);
            $team->save();
        }

        $result = TeamMember::updateMembers($team->id, $input['captains'], 'captain');
        if ($result !== null) {
            $errors = new MessageBag();
            $errors->add('captains', $result.' is not a valid user');

            return redirect()->route('teams_add')->withInput()->withErrors($errors);
        }

        Session::flash('msg-success', 'Team `'.$team->display_name.'` created');

        return redirect()->route('teams_show', [$team->name]);
    }

    public function show($name)
    {
        $team = Team::fetchByName($name);
        //$this->authorize('show', $team);

        $page = Page::fetchBy('route', 'teams');
        $actions = 'teams_show_edit,'.$team->name;

        return view('teams.show', compact('actions', 'page', 'team'));
    }

    public function showEdit(Request $request, $name)
    {
        $team = Team::fetchByName($name);
        $this->authorize('edit', $team);

        $page = Page::fetchBy('route', 'teams');
        $teamTypes = array_combine(Config::get('cupa.teamTypes'), Config::get('cupa.teamTypes'));

        if ($request->has('captains')) {
            $initial = $request->old('captains');
        } else {
            $captains = [];
            foreach ($team->captains() as $captain) {
                $captains[] = $captain->user->id;
            }
            $initial = implode(',', $captains);
        }

        return view('teams.show_edit', compact('page', 'team', 'teamTypes', 'initial'));
    }

    public function postShowEdit(TeamAddEditRequest $request, $name)
    {
        $team = Team::fetchByName($name);
        $input = $request->all();
        $input['captains'] = explode(',', $input['captains']);

        if (!$request->hasFile('logo') && isset($input['logo_remove']) && $input['logo_remove'] == 1) {
            $filePath = public_path().$team->logo;
            if ($team->logo != '/data/users/default.png' && file_exists($filePath)) {
                unlink($filePath);
            }
            $team->logo = '/data/users/default.png';
        } elseif ($request->hasFile('logo')) {
            $filePath = public_path().'/data/area_teams/'.time().'-'.$team->id.'.jpg';
            $img = Image::cache(function ($image) use ($filePath) {
                return $image->make($request->file('logo')->getRealPath())->resize(400, 400)->orientate()->save($filePath);
            });
            $team->logo = str_replace(public_path(), '', $filePath);
        }

        $team->name = str_slug($input['display_name']);
        $team->display_name = $input['display_name'];
        $team->menu = $input['menu'];
        $team->type = $input['type'];
        $team->override_email = (empty($input['override_email'])) ? null : $input['override_email'];
        $team->facebook = (empty($input['facebook'])) ? null : $input['facebook'];
        $team->twitter = (empty($input['twitter'])) ? null : $input['twitter'];
        $team->website = (empty($input['website'])) ? null : $input['website'];
        $team->begin = $input['begin'];
        $team->end = (empty($input['end'])) ? null : $input['end'];
        $team->description = $input['description'];
        $team->updated_by = Auth::user()->id;
        $team->save();

        $result = TeamMember::updateMembers($team->id, $input['captains'], 'captain');
        if ($result !== null) {
            $errors = new MessageBag();
            $errors->add('captains', $result.' is not a valid user');

            return redirect()->route('teams_show_edit')->withInput()->withErrors($errors);
        }

        Session::flash('msg-success', 'Team `'.$team->display_name.'` updated');

        return redirect()->route('teams_show', [$team->name]);
    }
}
