<?php

namespace Cupa\Http\Controllers;

use Cupa\Http\Requests\ManageUserRequest;
use Cupa\Http\Requests\LeaguePlayersRequest;
use Cupa\Http\Requests\LoadLeagueRequest;
use Cupa\Http\Requests\DuplicatesRequest;
use Cupa\User;
use Cupa\UserBalance;
use Cupa\League;
use Cupa\LeagueMember;
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
}
