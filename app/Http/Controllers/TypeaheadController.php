<?php

namespace Cupa\Http\Controllers;

use Cupa\Models\League;
use Cupa\Models\LeagueMember;
use Cupa\Models\User;
use Illuminate\Http\Request;

class TypeaheadController extends Controller
{
    public function users(Request $request)
    {
        $input = $request->all();
        $withEmail = isset($input['with_email']);

        if (isset($input['term'])) {
            $users = User::typeahead($input['term'], false, $withEmail);
        } elseif (isset($input['ids'])) {
            $users = User::typeahead($input['ids'], true, $withEmail);
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
