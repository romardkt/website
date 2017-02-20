<?php

namespace Cupa\Http\Controllers;

use Cupa\Http\Requests\PickupAddEditRequest;
use Cupa\Http\Requests\TournamentAddRequest;
use Cupa\Models\Location;
use Cupa\Models\Page;
use Cupa\Models\Pickup;
use Cupa\Models\PickupContact;
use Cupa\Models\Tournament;
use Cupa\Models\TournamentMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;

class AroundController extends Controller
{
    public function around(Request $request)
    {
        return redirect()->route('around_pickups');
    }

    public function pickups()
    {
        $page = Page::fetchBy('route', 'around_pickups');
        $actions = 'add';
        $pickups = Pickup::fetchAllPickups();

        return view('around.pickups', compact('actions', 'page', 'pickups'));
    }

    public function pickupsAdd(Request $request)
    {
        $this->authorize('is-editor', null);

        $page = Page::fetchBy('route', 'around_pickups');
        $locations = Location::fetchForSelect();
        $initial = $request->old('contacts');

        return view('around.pickups_add', compact('page', 'locations', 'initial'));
    }

    public function postPickupsAdd(PickupAddEditRequest $request)
    {
        $input = $request->all();
        $input['contacts'] = explode(',', $input['contacts']);

        $pickup = Pickup::create([
            'title' => $input['title'],
            'day' => $input['day'],
            'time' => $input['time'],
            'email_override' => (empty($input['email_override'])) ? null : $input['email_override'],
            'location_id' => $input['location_id'],
            'info' => $input['info'],
        ]);

        $result = PickupContact::updateContacts($pickup->id, $input['contacts']);
        if ($result !== null) {
            $errors = new MessageBag();
            $errors->add('contacts', $result.' is not a valid user');

            return redirect()->route('around_pickups_add')->withInput()->withErrors($errors);
        }

        Session::flash('msg-success', 'Pickup `'.$pickup->title.'` created');

        return redirect()->route('around_pickups');
    }

    public function pickupsEdit(Request $request, Pickup $pickup)
    {
        $this->authorize('edit', $pickup);

        $page = Page::fetchBy('route', 'around_pickups');
        $locations = Location::fetchForSelect();
        if ($request->old('contacts')) {
            $initial = $request->old('contacts');
        } else {
            $contacts = [];
            foreach ($pickup->contacts as $contact) {
                $contacts[] = $contact->user->id;
            }
            $initial = implode(',', $contacts);
        }

        return view('around.pickups_edit', compact('page', 'locations', 'initial', 'pickup'));
    }

    public function postPickupsEdit(PickupAddEditRequest $request, Pickup $pickup)
    {
        $input = $request->all();
        $input['contacts'] = explode(',', $input['contacts']);

        $pickup->title = $input['title'];
        $pickup->day = $input['day'];
        $pickup->time = $input['time'];
        $pickup->email_override = (empty($input['email_override'])) ? null : $input['email_override'];
        $pickup->is_visible = (empty($input['is_visible'])) ? 0 : 1;
        $pickup->location_id = $input['location_id'];
        $pickup->info = $input['info'];
        unset($pickup->contacts);
        $pickup->save();

        $result = PickupContact::updateContacts($pickup->id, $input['contacts']);
        if ($result !== null) {
            $errors = new MessageBag();
            $errors->add('contacts', $result.' is not a valid user');

            return redirect()->route('around_pickups_edit', [$pickupId])->withInput()->withErrors($errors);
        }

        Session::flash('msg-success', 'Pickup `'.$pickup->title.'` updated');

        return redirect()->route('around_pickups');
    }

    public function pickupsRemove(Pickup $pickup)
    {
        $this->authorize('delete', $pickup);

        $pickup->delete();
        Session::flash('msg-success', 'Pickup removed');

        return redirect()->route('around_pickups');
    }

    public function tournaments()
    {
        $page = Page::fetchBy('route', 'around_tournaments');
        $actions = 'add';
        $tournaments = Tournament::fetchAllCurrent();

        return view('around.tournaments', compact('actions', 'page', 'tournaments'));
    }

    public function tournamentsAdd(Request $request)
    {
        $this->authorize('is-manager', null);

        $page = Page::fetchBy('route', 'around_tournaments');
        $tournamentList = Tournament::fetchDistinctTournaments();
        $locations = Location::fetchForSelect();
        $initial = $request->old('directors');
        $divisions = array_combine(Config::get('cupa.divisions'), Config::get('cupa.divisions'));

        return view('around.tournaments_add', compact('page', 'tournamentList', 'locations', 'initial', 'divisions'));
    }

    public function postTournamentsAdd(TournamentAddRequest $request)
    {
        $input = $request->all();
        $input['directors'] = explode(',', $input['directors']);

        $tournament = Tournament::create([
            'name' => (empty($input['name'])) ? $input['new_name'] : $input['name'],
            'year' => $input['year'],
            'display_name' => $input['display_name'],
            'override_name' => (empty($input['override_name'])) ? null : $input['override_name'],
            'divisions' => json_encode($input['divisions']),
            'location_id' => $input['location_id'],
            'start' => convertDate($input['start_date'].' '.$input['start_time']),
            'end' => convertDate($input['end_date'].' '.$input['end_time']),
            'description' => $input['description'],
            'schedule' => (empty($input['schedule'])) ? null : $input['schedule'],
            'tenative_date' => (empty($input['tenative_date'])) ? 0 : 1,
            'cost' => $input['cost'],
            'use_bid' => (empty($input['use_bid'])) ? 0 : 1,
            'bid_due' => convertDate($input['end_date'].' '.$input['end_time']),
            'use_paypal' => (empty($input['use_paypal'])) ? 0 : 1,
            'paypal' => (empty($input['paypal'])) ? null : $input['paypal'],
            'is_visible' => (empty($input['is_visible'])) ? 0 : 1,
        ]);

        $result = TournamentMember::updateMembers($tournament->id, $input['directors'], 'director');
        if ($result !== null) {
            $errors = new MessageBag();
            $errors->add('directors', $result.' is not a valid user');

            return redirect()->route('around_tournaments_add')->withInput()->withErrors($errors);
        }

        Session::flash('msg-success', 'Tournament `'.$tournament->display_name.'` created');

        return redirect()->route('around_tournaments');
    }

    public function discgolf()
    {
        return redirect()->to('http://www.pdga.com/course_directory/zipcode?filter0=45201');
    }

    public function fields()
    {
        return redirect()->to('https://maps.google.com/maps?q=https:%2F%2Fdocs.google.com%2Fspreadsheet%2Fpub%3Fkey%3DtlpYVcgrRUJMdxGH2q-VAaQ%26single%3Dtrue%26gid%3D0%26output%3Dtxt');
    }
}
