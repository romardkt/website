<?php

namespace Cupa\Http\Controllers;

use Auth;
use Hash;
use View;
use Mail;
use Route;
use Config;
use Session;
use Cupa\User;
use Cupa\UserContact;
use Cupa\UserProfile;
use Cupa\UserRequirement;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Cupa\Http\Requests\ProfileRequest;
use Cupa\Http\Requests\ProfileMinorRequest;
use Cupa\Http\Requests\ProfileContactRequest;
use Cupa\Http\Requests\ProfileConvertRequest;
use Cupa\Http\Requests\ProfileCoachingRequest;
use Cupa\Http\Requests\ProfilePasswordRequest;

class ProfileController extends Controller
{
    private function setupData()
    {
        if (Auth::check()) {
            $user = Auth::user();
            View::share('user', $user);
            View::share('leagues', $user->fetchAllLeagues());
            View::share('minors', $user->children);
            View::share('contacts', $user->contacts);
            View::share('coaching', $user->coachingRequirements(date('Y')));

            $signups = [];
            if ($user->volunteer) {
                $signups = $user->volunteer->signups;
            }
            View::share('signups', $signups);
        }
    }

    public function profile(Request $request)
    {
        $this->setupData();

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
            $img = Image::cache(function ($image) use ($filePath, $request) {
                return $image->make($request->file('avatar')->getRealPath())->resize(400, 400)->orientate()->save($filePath);
            });
            $user->avatar = str_replace(public_path(), '', $filePath);
        }
        $user->save();

        Session::flash('msg-success', 'Profile updated');

        return redirect()->route('profile');
    }

    public function publicProfile($slug)
    {
        $user = User::fetchBySlug($slug);
        if (!$user) {
            abort(404);
        }

        $signups = [];
        if ($user->volunteer) {
            $signups = $user->volunteer->signups;
        }

        return view('profile.public', compact('user', 'signups'));
    }

    public function password()
    {
        $this->setupData();

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
        $this->setupData();

        return view('profile.leagues');
    }

    public function minors()
    {
        $this->setupData();

        return view('profile.minors');
    }

    public function minorAdd()
    {
        $this->setupData();

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
        $this->setupData();

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
        $this->setupData();

        return view('profile.contacts');
    }

    public function contactAdd()
    {
        $this->setupData();

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
        $this->setupData();

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

    public function volunteer(Request $request)
    {
        $this->setupData();

        return view('profile.volunteer');
    }

    public function coaching(Request $request)
    {
        $this->setupData();

        Session::put('waiver_redirect', route('profile_coaching'));
        $requirements = json_decode(UserRequirement::fetchOrCreateRequirements(Auth::id(), date('Y'))->requirements, true);
        $hiddenReqs = Config::get('cupa.coachingRequirements');
        unset($hiddenReqs['manual']);
        unset($hiddenReqs['rules']);

        return view('profile.coaching', compact('requirements', 'hiddenReqs'));
    }

    public function postCoaching(ProfileCoachingRequest $request)
    {
        $input = $request->all();
        $requirements = json_decode(UserRequirement::fetchOrCreateRequirements(Auth::id(), date('Y'))->requirements, true);
        $hiddenReqs = Config::get('cupa.coachingRequirements');
        unset($hiddenReqs['manual']);
        unset($hiddenReqs['rules']);

        foreach (Config::get('cupa.coachingRequirements') as $req => $text) {
            if (!in_array($req, array_keys($hiddenReqs))) {
                $requirements[$req] = (isset($input[$req])) ? 1 : 0;
            }
        }

        UserRequirement::updateRequirements(Auth::id(), date('Y'), $requirements);
        Session::flash('msg-success', 'Coaching requirements updated');

        return redirect()->route('profile_coaching');
    }

    public function minorsConvert(User $minor)
    {
        $this->setupData();

        if ($minor->getAge() < 18) {
            Session::flash('msg-error', 'Minor must be 18 years or older to convert');

            return redirect()->route('profile_minors');
        }

        return view('profile.minors_convert', compact('minor'));
    }

    public function postMinorsConvert(ProfileConvertRequest $request, User $minor)
    {
        if ($minor->getAge() < 18) {
            Session::flash('msg-error', 'Cannot convert a minor that is younger than 18 years old');

            return redirect()->route('profile_minors');
        }

        // update the required fields
        $minor->parent = null;
        $minor->email = $request->input('email');
        $minor->password = Hash::make(md5($request->input('email')));
        $minor->activated_at = new \DateTime();
        $minor->last_login_at = null;
        $minor->reason = 'was converted from a minor account';
        $minor->save();

        // send the email
        Mail::send('emails.reset', ['code' => $minor->fetchPasswordResetCode(), 'email' => $minor->email], function ($m) use ($minor) {
            // send email to the user
            $m->to($minor->email, $minor->fullname())
                ->replyTo('webmaster@cincyultimate.org')
                ->subject('[CUPA] Password Reset');
        });

        Session::flash('msg-success', 'Minor account converted to regular account, Password reset link sent');

        return redirect()->route('profile_minors');
    }
}
