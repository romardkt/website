<?php

namespace Cupa\Http\Controllers;

use Cupa\Http\Requests\ProfileContactRequest;
use Cupa\Http\Requests\ProfileMinorRequest;
use Cupa\Http\Requests\ProfilePasswordRequest;
use Cupa\Http\Requests\ProfileRequest;
use Cupa\User;
use Cupa\UserContact;
use Cupa\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function __construct()
    {
        if (Auth::check()) {
            View::share('user', Auth::user());
            View::share('leagues', Auth::user()->fetchAllLeagues());
            View::share('minors', Auth::user()->children);
            View::share('contacts', Auth::user()->contacts);
        }
    }

    public function profile(Request $request)
    {
        if (Auth::guest()) {
            Session::flash('msg-error', 'Please login to view your profile');
            
            return redirect()->route('home');
        }

        $user = Auth::user();
        $data = $user->toArray() + $user->profile->toArray();
        $data['birthday'] = convertDate($data['birthday'], 'm/d/Y');

        return view('profile.profile', compact('data'));
    }

    public function postProfile(ProfileRequest $request)
    {
        $input = $request->all();
        $input['avatar'] = $request->file('avatar');

        $user = Auth::user();
        $user->email = $input['email'];
        $user->first_name = $input['first_name'];
        $user->last_name = $input['last_name'];
        $user->birthday = convertDate($input['birthday'], 'Y-m-d');
        $user->gender = $input['gender'];
        $user->save();

        $profile = $user->profile;
        $profile->phone = $input['phone'];
        $profile->nickname = (empty($input['nickname'])) ? null : $input['nickname'];
        $profile->height = (empty($input['height'])) ? null : $input['height'];
        $profile->level = (empty($input['level'])) ? null : $input['level'];
        $profile->experience = (empty($input['experience'])) ? null : $input['experience'];
        $profile->save();

        if (!$request->hasFile('avatar') && isset($input['avatar_remove'])) {
            $filePath = public_path().$user->avatar;
            if ($user->avatar != '/data/users/default.png' && file_exists($filePath)) {
                unlink($filePath);
            }
            $user->avatar = '/data/users/default.png';
        } elseif ($request->hasFile('avatar')) {
            $filePath = public_path().'/data/users/'.time().'-'.$user->id.'.jpg';
            $img = Image::cache(function ($image) use ($filePath) {
                return $image->make($request->file('avatar')->getRealPath())->resize(400, 400)->orientate()->save($filePath);
            });
            $user->avatar = str_replace(public_path(), '', $filePath);
        }
        $user->save();

        Session::flash('msg-success', 'Profile updated');

        return redirect()->route('profile');
    }

    public function publicProfile($userId)
    {
        $user = User::find($userId);

        return view('profile.public', compact('user'));
    }

    public function password()
    {
        return view('profile.password');
    }

    public function postPassword(ProfilePasswordRequest $request)
    {
        $input = $request->all();

        $user = Auth::user();
        $user->password = Hash::make($input['password']);
        $user->save();

        Session::flash('msg-success', 'Password changed');

        return redirect()->route('profile_password');
    }

    public function leagues()
    {
        return view('profile.leagues');
    }

    public function minors()
    {
        return view('profile.minors');
    }

    public function minorAdd()
    {
        return view('profile.minor_add');
    }

    public function postMinorAdd(ProfileMinorRequest $request)
    {
        $input = $request->all();

        $minor = new User();
        $minor->parent = Auth::id();
        $minor->password = Hash::make(Hash::make('testing'));
        $minor->activation_code = User::generateCode('activation_code');
        $minor->first_name = $input['first_name'];
        $minor->last_name = $input['last_name'];
        $minor->birthday = convertDate($input['birthday'], 'Y-m-d');
        $minor->gender = $input['gender'];
        $minor->save();

        $minorProfile = new UserProfile();
        $minorProfile->user_id = $minor->id;
        $minorProfile->nickname = (empty($input['nickname'])) ? null : $input['nickname'];
        $minorProfile->height = $input['height'];
        $minorProfile->level = $input['level'];
        $minorProfile->experience = $input['experience'];
        $minorProfile->save();

        Session::flash('msg-success', 'New minor created');

        return redirect()->route('profile_minors');
    }

    public function minorEdit(User $minor)
    {
        $data = $minor->toArray() + $minor->profile->toArray();
        $data['birthday'] = convertDate($data['birthday'], 'm/d/Y');

        return view('profile.minor_edit', compact('minor', 'data'));
    }

    public function postMinorEdit(ProfileMinorRequest $request, User $minor)
    {
        $input = $request->all();

        $minor->first_name = $input['first_name'];
        $minor->last_name = $input['last_name'];
        $minor->birthday = convertDate($input['birthday'], 'Y-m-d');
        $minor->gender = $input['gender'];
        $minor->save();

        $minorProfile = $minor->profile;
        $minorProfile->nickname = (empty($input['nickname'])) ? null : $input['nickname'];
        $minorProfile->height = $input['height'];
        $minorProfile->level = $input['level'];
        $minorProfile->experience = $input['experience'];
        $minorProfile->save();

        Session::flash('msg-success', 'Minor updated');

        return redirect()->route('profile_minors');
    }

    public function minorRemove(User $minor)
    {
        $minor->delete();

        Session::flash('msg-success', 'Minor removed');

        return redirect()->route('profile_minors');
    }

    public function contacts()
    {
        return view('profile.contacts');
    }

    public function contactAdd()
    {
        return view('profile.contact_add');
    }

    public function postContactAdd(ProfileContactRequest $request, UserContact $contact)
    {
        $input = $request->all();

        $contact = new UserContact();
        $contact->user_id = Auth::id();
        $contact->name = $input['name'];
        $contact->phone = $input['phone'];
        $contact->save();

        Session::flash('msg-success', 'New contact created');

        if ($request->ajax()) {
            return response()->json(['status' => 'success']);
        } else {
            return redirect()->route('profile_contacts');
        }
    }

    public function contactEdit(UserContact $contact)
    {
        return view('profile.contact_edit', compact('contact'));
    }

    public function postContactEdit(ProfileContactRequest $request, UserContact $contact)
    {
        $input = $request->all();
        $contact->name = $input['name'];
        $contact->phone = $input['phone'];
        $contact->save();

        Session::flash('msg-success', 'Contact updated');

        return redirect()->route('profile_contacts');
    }

    public function contactRemove(UserContact $contact, Request $request)
    {
        $contact->delete();
        Session::flash('msg-success', 'Minor removed');

        if ($request->ajax()) {
            return response()->json(['status' => 'success']);
        } else {
            return redirect()->route('profile_contacts');
        }
    }
}
