<?php

namespace Cupa\Http\Controllers;

use Illuminate\Http\Request;
use Cupa\User;
use Cupa\LeagueMember;

class TypeaheadController extends Controller
{
    public function users(Request $request)
    {
        $input = $request->all();
        if (isset($input['term'])) {
            $users = User::typeahead($input['term'], false);
        } elseif (isset($input['ids'])) {
            $users = User::typeahead($input['ids'], true);
        } else {
            $users = [];
        }

        return response()->json($users);
    }

    public function members(Request $request, $leagueId)
    {
        $term = $request->get('term', '');
        $members = LeagueMember::typeahead($leagueId, $term);

        return response()->json($members);
    }
}
