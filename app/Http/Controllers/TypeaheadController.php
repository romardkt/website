<?php

namespace Cupa\Http\Controllers;

use Cupa\League;
use Cupa\LeagueMember;
use Cupa\User;
use Illuminate\Http\Request;

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

    public function members(Request $request, League $league)
    {
        $term = $request->get('term', '');
        $members = LeagueMember::typeahead($league->id, $term);

        return response()->json($members);
    }
}
