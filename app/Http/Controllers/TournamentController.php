<?php

namespace Cupa\Http\Controllers;

use Cupa\Location;
use Cupa\Tournament;
use Cupa\TournamentFeed;
use Cupa\TournamentTeam;
use Cupa\TournamentMember;
use Cupa\TournamentLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use Cupa\Http\Requests\TournamentBidRequest;
use Cupa\Http\Requests\TournamentFeedRequest;
use Cupa\Http\Requests\TournamentTeamRequest;
use Cupa\Http\Requests\TournamentAdminRequest;
use Cupa\Http\Requests\TournamentBidEditRequest;
use Cupa\Http\Requests\TournamentContactRequest;
use Cupa\Http\Requests\TournamentLocationRequest;
use Cupa\Http\Requests\TournamentScheduleRequest;
use Cupa\Http\Requests\TournamentDescriptionRequest;
use Cupa\Http\Requests\TournamentLocationMapRequest;

class TournamentController extends Controller
{
    private function fetchTournament($name, $year)
    {
        $tournament = Tournament::fetchTournament($name, $year);
        if (!$tournament || $tournament->is_visible == 0 && Gate::denies('show', $tournament)) {
            abort(404);
        }

        return $tournament;
    }

    public function tournament($name, $year = null)
    {
        $tournament = $this->fetchTournament($name, $year);

        return view('tournament.tournament', compact('tournament'));
    }

    public function admin($name, $year)
    {
        $tournament = $this->fetchTournament($name, $year);

        return view('tournament.admin', compact('tournament'));
    }

    public function postAdmin($name, $year, TournamentAdminRequest $request)
    {
        $tournament = $this->fetchTournament($name, $year);
        $input = $request->all();
        $input['header'] = $request->file('header');

        $tournament->display_name = $input['display_name'];
        $tournament->override_email = (empty($input['override_email'])) ? null : $input['override_email'];
        $tournament->use_bid = (isset($input['use_bid'])) ? 1 : 0;
        $tournament->is_visible = (isset($input['is_visible'])) ? 1 : 0;
        $tournament->has_teams = (isset($input['has_teams'])) ? 1 : 0;

        $tournament->divisions = json_encode($input['divisions']);
        $tournament->tenative_date = (isset($input['tenative_date'])) ? 1 : 0;
        $tournament->start = convertDate($input['start'], 'Y-m-d');
        $tournament->end = convertDate($input['end'], 'Y-m-d');

        if ($request->hasFile('header')) {
            $filePath = public_path().'/data/tournaments/'.time().'-'.$tournament->id.'.jpg';
            $img = Image::cache(function ($image) use ($filePath) {
                return $image->make($request->file('header')->getRealPath())->resize(1200, 300)->orientate()->save($filePath);
            });
            $tournament->image = str_replace(public_path(), '', $filePath);
        } elseif (isset($input['header_remove'])) {
            if ($tournament->image !== '/data/tournaments/default.jpg') {
                if (file_exists(public_path().$tournament->image)) {
                    unlink(public_path().$tournament->image);
                }
                $tournament->image = '/data/tournaments/default.jpg';
            }
        }

        $tournament->save();

        Session::flash('msg-success', 'Tournament settings updated');

        return redirect()->route('tournament', [$tournament->name, $tournament->year]);
    }

    public function bid($name, $year)
    {
        $tournament = $this->fetchTournament($name, $year);
        $divisions = array_combine(json_decode($tournament->divisions, true), json_decode($tournament->divisions, true));

        return view('tournament.bid', compact('tournament', 'divisions'));
    }

    public function postBid($name, $year, TournamentBidRequest $request)
    {
        $input = $request->all();
        $tournament = $this->fetchTournament($name, $year);

        $team = new TournamentTeam();
        $team->tournament_id = $tournament->id;
        $team->division = $input['division'];
        $team->name = $input['name'];
        $team->city = $input['city'];
        $team->state = $input['state'];
        $team->contact_name = $input['contact_name'];
        $team->contact_phone = $input['contact_phone'];
        $team->contact_email = $input['contact_email'];
        $team->accepted = 0;
        $team->paid = 0;
        $team->comments = (empty($input['comments'])) ? null : $input['comments'];
        $team->save();

        // Send Mail to submitter
        Mail::send('emails.tournament_bid', ['tournament' => $tournament, 'team' => $team], function ($m) use ($tournament, $team) {
            if (App::environment() == 'local') {
                $m->to('kcin1018@gmail.com', 'Nick Felicelli');
            } else {
                $m->to($team->contact_email, $team->contact_name);
            }

            $m->subject('['.$tournament->display_name.'] Bid Submission');
        });

        // Send Mail to directors
        Mail::send('emails.tournament_bid_director', ['tournament' => $tournament, 'team' => $team], function ($m) use ($tournament) {
            if (App::environment() == 'prod') {
                foreach ($tournament->contacts as $contact) {
                    $m->to($contact->user->email, $contact->user->fullname());
                }
                $m->bcc('webmaster@cincyultimate.org', 'CUPA Webmaster');
            } else {
                $m->to('kcin1018@gmail.com', 'Nick Felicelli');
            }

            $m->subject('['.$tournament->display_name.'] Bid Submission');
        });

        Session::flash('msg-success', 'Team `'.$team->name.'` bid submitted');

        return redirect()->route('tournament_payment', [$tournament->name, $tournament->year, $team->division]);
    }

    public function bidEdit($name, $year)
    {
        $tournament = $this->fetchTournament($name, $year);

        return view('tournament.bid_edit', compact('tournament'));
    }

    public function postBidEdit($name, $year, TournamentBidEditRequest $request)
    {
        $input = $request->all();
        $input['bid_due'] = convertDate($input['bid_due_date'].' '.$input['bid_due_time']);
        $tournament = $this->fetchTournament($name, $year);

        $tournament->cost = $input['cost'];
        $tournament->bid_due = $input['bid_due'];
        switch ($input['paypal_type']) {
            case 0:
            case 1:
                $tournament->use_paypal = 1;
                break;
            case 2:
                $tournament->use_paypal = 0;
                break;
        }

        $tournament->paypal = (empty($input['paypal']) || $tournament->use_paypal == 0 || $input['paypal_type'] == 0) ? null : $input['paypal'];
        $tournament->mail = (empty($input['mail'])) ? null : $input['mail'];
        $tournament->save();

        Session::flash('msg-success', 'Bid information updated');

        return redirect()->route('tournament_bid', [$tournament->name, $tournament->year]);
    }

    public function teams($name, $year, $currentDivision = null)
    {
        $tournament = $this->fetchTournament($name, $year);
        if ($currentDivision === null) {
            $currentDivision = json_decode($tournament->divisions, true)[0];
        }

        return view('tournament.teams', compact('tournament', 'currentDivision'));
    }

    public function teamsAdd($name, $year)
    {
        $tournament = $this->fetchTournament($name, $year);
        $this->authorize('edit', $tournament);
        $divisions = array_combine(json_decode($tournament->divisions, true), json_decode($tournament->divisions, true));

        return view('tournament.teams_add', compact('tournament', 'divisions'));
    }

    public function postTeamsAdd($name, $year, TournamentTeamRequest $request)
    {
        $input = $request->all();
        $tournament = $this->fetchTournament($name, $year);

        $team = new TournamentTeam();
        $team->tournament_id = $tournament->id;
        $team->division = $input['division'];
        $team->name = $input['name'];
        $team->city = $input['city'];
        $team->state = $input['state'];
        $team->contact_name = $input['contact_name'];
        $team->contact_phone = $input['contact_phone'];
        $team->contact_email = $input['contact_email'];
        $team->accepted = (isset($input['accepted'])) ? 1 : 0;
        $team->paid = (isset($input['paid'])) ? 1 : 0;
        $team->comments = (empty($input['comments'])) ? null : $input['comments'];
        $team->save();

        Session::flash('msg-success', 'Team `'.$team->name.'` created');

        return redirect()->route('tournament_teams', [$tournament->name, $tournament->year, $team->division]);
    }

    public function teamsRemove(TournamentTeam $team)
    {
        $this->authorize('delete', $team->tournament);
        $name = $team->name;
        $team->delete();

        Session::flash('msg-success', 'Team `'.$name.'` removed');

        return redirect()->route('tournament_teams', [$team->tournament->name, $team->tournament->year, $team->division]);
    }

    public function teamsEdit(TournamentTeam $team)
    {
        $this->authorize('edit', $team->tournament);
        $tournament = $team->tournament;
        $divisions = array_combine(json_decode($tournament->divisions, true), json_decode($tournament->divisions, true));

        return view('tournament.teams_edit', compact('tournament', 'divisions', 'team'));
    }

    public function postTeamsEdit(TournamentTeamRequest $request, TournamentTeam $team)
    {
        $input = $request->all();

        $team->division = $input['division'];
        $team->name = $input['name'];
        $team->city = $input['city'];
        $team->state = $input['state'];
        $team->contact_name = $input['contact_name'];
        $team->contact_phone = $input['contact_phone'];
        $team->contact_email = $input['contact_email'];
        $team->accepted = (isset($input['accepted'])) ? 1 : 0;
        $team->paid = (isset($input['paid'])) ? 1 : 0;
        $team->save();

        Session::flash('msg-success', 'Team `'.$team->name.'` updated');

        return redirect()->route('tournament_teams', [$team->tournament->name, $team->tournament->year, $team->division]);
    }

    public function schedule($name, $year)
    {
        $tournament = $this->fetchTournament($name, $year);

        return view('tournament.schedule', compact('tournament'));
    }

    public function location($name, $year)
    {
        $tournament = $this->fetchTournament($name, $year);

        return view('tournament.location', compact('tournament'));
    }

    public function contact($name, $year)
    {
        $tournament = $this->fetchTournament($name, $year);

        return view('tournament.contact', compact('tournament'));
    }

    public function descriptionEdit(Tournament $tournament)
    {
        $this->authorize('edit', $tournament);

        return view('tournament.description_edit', compact('tournament'));
    }

    public function postDescriptionEdit(TournamentDescriptionRequest $request, Tournament $tournament)
    {
        $input = $request->all();
        $tournament->description = $input['description'];
        $tournament->save();

        Session::flash('msg-success', 'Tournament description updated');

        return redirect()->route('tournament', [$tournament->name, $tournament->year]);
    }

    public function feedAdd($name, $year)
    {
        $tournament = $this->fetchTournament($name, $year);
        $this->authorize('edit', $tournament);

        return view('tournament.feed_add', compact('tournament'));
    }

    public function postFeedAdd($name, $year, TournamentFeedRequest $request)
    {
        $input = $request->all();
        $tournament = $this->fetchTournament($name, $year);

        $feed = new TournamentFeed();
        $feed->tournament_id = $tournament->id;
        $feed->title = $input['title'];
        $feed->content = $input['content'];
        $feed->save();

        Session::flash('msg-success', 'Tournament news post added');

        return redirect()->route('tournament', [$tournament->name, $tournament->year]);
    }

    public function feedEdit(TournamentFeed $feed)
    {
        $tournament = $feed->tournament;
        $this->authorize('edit', $tournament);

        return view('tournament.feed_edit', compact('feed', 'tournament'));
    }

    public function postFeedEdit(TournamentFeedRequest $request, TournamentFeed $feed)
    {
        $input = $request->all();
        $feed->title = $input['title'];
        $feed->content = $input['content'];
        $feed->save();

        Session::flash('msg-success', 'Tournament news post udpated');

        return redirect()->route('tournament', [$feed->tournament->name, $feed->tournament->year]);
    }

    public function feedRemove(TournamentFeed $feed)
    {
        $tournament = $feed->tournament;
        $this->authorize('delete', $tournament);
        $title = $feed->title;
        $feed->delete();

        Session::flash('msg-success', $title.' removed');

        return redirect()->route('tournament', [$feed->tournament->name, $feed->tournament->year]);
    }

    public function scheduleEdit(Tournament $tournament)
    {
        $this->authorize('edit', $tournament);

        return view('tournament.schedule_edit', compact('tournament'));
    }

    public function postScheduleEdit(TournamentScheduleRequest $request, Tournament $tournament)
    {
        $input = $request->all();

        $tournament->schedule = $input['schedule'];
        $tournament->save();

        Session::flash('msg-success', 'Tournament schedule updated');

        return redirect()->route('tournament_schedule', [$tournament->name, $tournament->year]);
    }

    public function contactAdd($name, $year, Request $request)
    {
        $tournament = $this->fetchTournament($name, $year);
        $this->authorize('edit', $tournament);
        $initial = $request->old('user_id');

        return view('tournament.contact_add', compact('tournament', 'initial'));
    }

    public function postContactAdd($name, $year, TournamentContactRequest $request)
    {
        $input = $request->all();
        $tournament = $this->fetchTournament($name, $year);

        $member = new TournamentMember();
        $member->tournament_id = $tournament->id;
        $member->user_id = $input['user_id'];
        $member->position = $input['position'];
        $member->weight = TournamentMember::getWeight($tournament->id) + 1;
        $member->save();

        Session::flash('msg-success', 'Tournament contact added');

        return redirect()->route('tournament_contact', [$tournament->name, $tournament->year]);
    }

    public function contactOrder(TournamentMember $member, $direction)
    {
        $mod = ($direction == 'up') ? -1 : 1;
        $tournament = $member->tournament;
        $this->authorize('edit', $tournament);

        $prev = null;
        $flag = false;
        foreach ($tournament->contacts as $contact) {
            if ($flag && $direction == 'down') {
                $contact->weight -= $mod;
                $contact->save();
                break;
            }

            if ($contact->id == $member->id) {
                if ($contact->weight > 0 || $direction == 'down') {
                    $contact->weight += $mod;
                    $contact->save();

                    if ($direction == 'up' && $prev !== null) {
                        $mod = $mod * -1;
                        $prev->weight += $mod;
                        $prev->save();
                        break;
                    }
                }

                if ($direction == 'down') {
                    $flag = true;
                }
            }

            $prev = $contact;
        }

        Session::flash('msg-success', 'Tournament contact order update');

        return redirect()->route('tournament_contact', [$tournament->name, $tournament->year]);
    }

    public function contactRemove(TournamentMember $member)
    {
        $tournament = $member->tournament;
        $this->authorize('edit', $tournament);
        $member->delete();

        Session::flash('msg-success', 'Tournament contact removed');

        return redirect()->route('tournament_contact', [$tournament->name, $tournament->year]);
    }

    public function nationals2014Fans()
    {
        $tournament = $this->fetchTournament('nationals', 2014);

        return view('tournament.nationals_2014_fans', compact('tournament'));
    }

    public function locationMapEdit($name, $year)
    {
        $tournament = $this->fetchTournament($name, $year);
        $this->authorize('edit', $tournament);
        $locations = Location::fetchForSelect();

        return view('tournament.location_map_edit', compact('tournament', 'locations'));
    }

    public function postLocationMapEdit($name, $year, TournamentLocationMapRequest $request)
    {
        $input = $request->all();
        $tournament = $this->fetchTournament($name, $year);
        $tournament->location_id = $input['location_id'];
        $tournament->save();

        Session::flash('msg-success', 'Tournament map updated');

        return redirect()->route('tournament_location', [$tournament->name, $tournament->year]);
    }

    public function locationAdd($name, $year)
    {
        $tournament = $this->fetchTournament($name, $year);

        return view('tournament.location_add', compact('tournament'));
    }

    public function postLocationAdd($name, $year, TournamentLocationRequest $request)
    {
        $tournament = $this->fetchTournament($name, $year);
        $input = $request->all();

        $location = new TournamentLocation();
        $location->tournament_id = $tournament->id;
        $location->title = $input['title'];
        $location->link = (empty($input['link'])) ? null : $input['link'];
        $location->street = (empty($input['street'])) ? null : $input['street'];
        $location->city = (empty($input['city'])) ? null : $input['city'];
        $location->state = (empty($input['state'])) ? null : $input['state'];
        $location->zip = (empty($input['zip'])) ? null : $input['zip'];
        $location->phone = (empty($input['phone'])) ? null : $input['phone'];
        $location->other = (empty($input['other'])) ? null : $input['other'];
        $location->save();

        Session::flash('msg-success', 'Tournament lodging/information added');

        return redirect()->route('tournament_location', [$tournament->name, $tournament->year]);
    }

    public function locationEdit(TournamentLocation $location)
    {
        $tournament = $location->tournament;

        return view('tournament.location_edit', compact('tournament', 'location'));
    }

    public function postLocationEdit(TournamentLocation $location, TournamentLocationRequest $request)
    {
        $input = $request->all();

        $location->title = $input['title'];
        $location->link = (empty($input['link'])) ? null : $input['link'];
        $location->street = (empty($input['street'])) ? null : $input['street'];
        $location->city = (empty($input['city'])) ? null : $input['city'];
        $location->state = (empty($input['state'])) ? null : $input['state'];
        $location->zip = (empty($input['zip'])) ? null : $input['zip'];
        $location->phone = (empty($input['phone'])) ? null : $input['phone'];
        $location->other = (empty($input['other'])) ? null : $input['other'];
        $location->save();

        Session::flash('msg-success', 'Tournament lodging/information updated');

        return redirect()->route('tournament_location', [$location->tournament->name, $location->tournament->year]);
    }

    public function locationRemove(TournamentLocation $location)
    {
        $location->delete();
        Session::flash('msg-success', 'Lodging/Information removed');

        return redirect()->route('tournament_location', [$location->tournament->name, $location->tournament->year]);
    }

    public function payment($name, $year)
    {
        $tournament = $this->fetchTournament($name, $year);
        $teams = TournamentTeam::fetchUnpaidTeamsByDivision($tournament->id);

        return view('tournament.payment', compact('tournament', 'teams'));
    }

    public function masters2014()
    {
        $tournament = $this->fetchTournament('scinny', 2014);

        return view('tournament.masters_2014', compact('tournament'));
    }

    public function masters2015()
    {
        $tournament = $this->fetchTournament('scinny', 2015);

        return view('tournament.masters_2015', compact('tournament'));
    }
}
