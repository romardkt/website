<?php

namespace Cupa\Http\Controllers;

use Cupa\Http\Requests\PageEditRequest;
use Cupa\Http\Requests\VolunteerShowRequest;
use Cupa\Http\Requests\VolunteerShowSignupRequest;
use Cupa\Http\Requests\VolunteerSignupRequest;
use Cupa\Location;
use Cupa\Page;
use Cupa\Volunteer;
use Cupa\VolunteerEvent;
use Cupa\VolunteerEventCategory;
use Cupa\VolunteerEventContact;
use Cupa\VolunteerEventSignUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;

class VolunteerController extends Controller
{
    public function volunteer(Request $request)
    {
        return redirect()->route('volunteer_about');
    }

    public function about()
    {
        $page = Page::fetchBy('route', 'volunteer_about');
        $actions = 'edit';

        return view('volunteer.about', compact('page', 'actions'));
    }

    public function aboutEdit()
    {
        $page = Page::fetchBy('route', 'volunteer_about');

        return view('volunteer.about_edit', compact('page'));
    }

    public function postAboutEdit(PageEditRequest $request)
    {
        $page = Page::fetchBy('route', 'volunteer_about');

        // get the posted data
        $input = $request->all();
        $page->display = $input['display'];
        $page->content = $input['content'];
        $page->save();

        Session::flash('msg-success', 'Volunteer info updated.');

        return redirect()->route('volunteer_about');
    }

    public function signup()
    {
        $page = Page::fetchBy('route', 'volunteer_signup');
        $actions = null;
        $user = Auth::user();
        $volunteer = $user->volunteer;
        $isVolunteer = Auth::check() && $user->isVolunteer();
        $volunteerChoices = Config::get('cupa.volunteer');
        $primaryInterest = null;
        if ($user && $volunteer) {
            $primaryInterest = [];
            foreach (explode(', ', $volunteer->primary_interest) as $interest) {
                $primaryInterest[$interest] = $interest;
            }
        }

        return view('volunteer.signup', compact('page', 'actions', 'isVolunteer', 'user', 'volunteerChoices', 'primaryInterest'));
    }

    public function postSignup(VolunteerSignupRequest $request)
    {
        // get the posted data
        $input = $request->all();
        $user = Auth::user();
        $volunteer = $user->volunteer;
        $new = false;
        if (!$volunteer) {
            $new = true;
            $volunteer = new Volunteer();
            $volunteer->user_id = $user->id;
        }

        $volunteer->involvement = $input['involvement'];
        $volunteer->primary_interest = implode(', ', $input['primary_interest']);
        $volunteer->other = (empty($input['other'])) ? null : $input['other'];
        $volunteer->experience = (empty($input['experience'])) ? 'None' : $input['experience'];
        $volunteer->save();

        $user->first_name = $input['first_name'];
        $user->last_name = $input['last_name'];
        $user->birthday = convertDate($input['birthday'], 'Y-m-d');
        $user->gender = $input['gender'];
        $user->save();

        $user->profile->phone = $input['phone'];
        $user->profile->save();

        if ($new) {
            Session::flash('msg-success', 'You have signed up to be a CUPA Volunteer');
        } else {
            Session::flash('msg-success', 'Volunteer information updated');
        }

        if (Session::has('volunteer_opportunity')) {
            return redirect()->route('volunteer_show_signup', Session::get('volunteer_opportunity'));
        } else {
            return redirect()->route('volunteer_signup');
        }
    }

    public function show()
    {
        $events = VolunteerEvent::fetchAllCurrentEvents();
        $page = Page::fetchBy('route', 'volunteer_show');
        $actions = 'add';

        return view('volunteer.show', compact('page', 'actions', 'events'));
    }

    public function showAdd(Request $request)
    {
        $locations = Location::fetchForSelect();
        $volunteerCategories = VolunteerEventCategory::fetchForSelect();
        $initial = $request->old('contacts');

        return view('volunteer.show_add', compact('volunteerCategories', 'locations', 'initial'));
    }

    public function postShowAdd(VolunteerShowRequest $request)
    {
        $this->authorize('is-volunteer');
        $input = $request->all();
        $input['contacts'] = explode(',', $input['contacts']);

        $volunteerEvent = VolunteerEvent::create([
            'volunteer_event_category_id' => $input['category'],
            'title' => $input['title'],
            'slug' => str_slug($input['title']),
            'email_override' => (empty($input['email_override'])) ? null : $input['email_override'],
            'start' => convertDate($input['start_date'].' '.$input['start_time']),
            'end' => convertDate($input['end_date'].' '.$input['end_time']),
            'num_volunteers' => $input['num_volunteers'],
            'information' => $input['information'],
            'location_id' => $input['location_id'],
        ]);

        $result = VolunteerEventContact::updateContacts($volunteerEvent->id, $input['contacts']);
        if ($result !== null) {
            $errors = new MessageBag();
            $errors->add('contacts', $result.' is not a valid user');

            return redirect()->route('volunteer_show_add')->withInput()->withErrors($errors);
        }

        Session::flash('msg-success', 'Volunteer Opportunity `'.$volunteerEvent->title.'` created');

        return redirect()->route('volunteer_show');
    }

    public function showEdit(VolunteerEvent $event, Request $request)
    {
        $this->authorize('is-volunteer');
        $locations = Location::fetchForSelect();
        $volunteerCategories = VolunteerEventCategory::fetchForSelect();
        if ($request->has('hidden-contacts')) {
            $initial = $request->old('hidden-contacts');
        } else {
            $contacts = [];
            foreach ($event->contacts as $contact) {
                $contacts[] = $contact->user->id;
            }
            $initial = implode(',', $contacts);
        }

        return view('volunteer.show_edit', compact('volunteerCategories', 'locations', 'initial', 'event'));
    }

    public function postShowEdit(VolunteerEvent $event, VolunteerShowRequest $request)
    {
        $input = $request->all();
        $input['contacts'] = explode(',', $input['contacts']);

        $event->volunteer_event_category_id = $input['category'];
        $event->title = $input['title'];
        $event->slug = str_slug($input['title']);
        $event->email_override = (empty($input['email_override'])) ? null : $input['email_override'];
        $event->start = convertDate($input['start_date'].' '.$input['start_time']);
        $event->end = convertDate($input['end_date'].' '.$input['end_time']);
        $event->num_volunteers = $input['num_volunteers'];
        $event->information = $input['information'];
        $event->location_id = $input['location_id'];
        $event->save();

        $result = VolunteerEventContact::updateContacts($event->id, $input['contacts']);
        if ($result !== null) {
            $errors = new MessageBag();
            $errors->add('contacts', $result.' is not a valid user');

            return redirect()->route('volunteer_show_edit', [$eventId])->withInput()->withErrors($errors);
        }

        Session::flash('msg-success', 'Volunteer Opportunity `'.$event->title.'` updated');

        return redirect()->route('volunteer_show');
    }

    public function showSignup(VolunteerEvent $event)
    {
        $user = Auth::user();
        if (!$user->isVolunteer()) {
            Session::flash('msg-error', 'You must sign up to be a volunteer before signing up for the opportunity');
            Session::put('volunteer_opportunity', $event->id);

            return redirect()->route('volunteer_signup');
        }

        if ($user->hasSignedUpForVolunteerEvent($event->id)) {
            Session::flash('msg-success', 'You have already signed up for this event');

            return redirect()->route('volunteer_show');
        }

        return view('volunteer.show_signup', compact('event'));
    }

    public function postShowSignup(VolunteerEvent $event, VolunteerShowSignupRequest $request)
    {
        $input = $request->all();
        $user = Auth::user();

        // build answers
        $answers = [];
        foreach (json_decode($event->category->questions) as $question) {
            $answers[$question->name] = (empty($input[$question->name])) ? null : $input[$question->name];
        }

        VolunteerEventSignUp::create([
            'volunteer_event_id' => $event->id,
            'volunteer_id' => Auth::user()->volunteer()->first()->id,
            'answers' => json_encode($answers),
            'notes' => (empty($input['notes'])) ? null : $input['notes'],
        ]);

        // email information to user
        Mail::send('emails.volunteer_signup', array('event' => $event), function ($m) use ($user, $event) {
            $m->to($user->email, $user->fullname())->subject('[CUPA] '.$event->title.' Signup');
        });

        Session::flash('msg-success', 'You have signed up for the event `'.$event->title.'`');

        return redirect()->route('volunteer_show');
    }

    public function pool()
    {
        $this->authorize('is-volunteer');
        $volunteers = Volunteer::fetchAllVolunteers();
        $page = Page::fetchBy('route', 'volunteer_list');
        $actions = null;

        return view('volunteer.pool', compact('page', 'actions', 'volunteers'));
    }

    public function poolDownload()
    {
        $this->authorize('is-volunteer');
        $volunteers = Volunteer::fetchAllVolunteersForDownload();
        $date = date('Y-m-d');
        $file = storage_path().'/app/'.$date.'-volunteers_list.csv';

        $fp = fopen($file, 'w');
        if ($fp) {
            foreach ($volunteers as $line) {
                fputcsv($fp, $line);
            }
            fclose($fp);

            return response()->download($file);
        }

        Session::flash('msg-error', 'Error downloading file');

        return redirect()->route('volunteer_list');
    }

    public function showMembers(VolunteerEvent $event)
    {
        $page = Page::fetchBy('route', 'volunteer_show');
        $actions = null;
        $event = VolunteerEvent::with(array('members', 'members.volunteer', 'members.volunteer.user', 'members.volunteer.user.profile'))->find($event->id);

        return view('volunteer.show_members', compact('page', 'actions', 'event'));
    }

    public function showMembersExport(VolunteerEvent $event)
    {
        $event = VolunteerEvent::with(array('members', 'members.volunteer', 'members.volunteer.user', 'members.volunteer.user.profile'))->find($event->id);

        $date = date('Y-m-d');
        $eventName = str_slug($event->title);
        $file = storage_path().'/app/'.$date.'-'.$eventName.'.csv';

        $fp = fopen($file, 'w');
        if ($fp) {
            $line = [
                'Volunteer',
                'Email',
                'Phone',
                'CUPA Involvement',
                'Past CUPA Experience',
                'Primary Interests',
            ];

            $questions = json_decode($event->category->questions);
            foreach ($questions as $question) {
                $line[] = ucwords(str_replace('_', ' ', $question->name));
            }

            fputcsv($fp, $line);

            foreach ($event->members as $member) {
                $line = [
                    $member->volunteer->user->fullname(),
                    $member->volunteer->user->email,
                    $member->volunteer->user->profile->phone,
                    $member->volunteer->involvement,
                    $member->volunteer->experience,
                    $member->volunteer->primary_interest,
                ];

                $answers = json_decode($member->answers);
                foreach ($questions as $question) {
                    if (is_array($answers->{$question->name})) {
                        $line[] = implode(' & ', $answers->{$question->name});
                    } else {
                        $line[] = $answers->{$question->name};
                    }
                }

                fputcsv($fp, $line);
            }

            fclose($fp);

            return response()->download($file);
        }

        Session::flash('msg-error', 'Error downloading file');

        return redirect()->route('volunteer_show_members', array($eventId));
    }
}
