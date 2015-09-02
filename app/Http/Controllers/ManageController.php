<?php

namespace Cupa\Http\Controllers;

use Cupa\Http\Requests\ManageUserRequest;
use Cupa\User;
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
}
