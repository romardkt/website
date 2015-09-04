<?php

namespace Cupa\Http\Controllers;

use Cupa\Http\Requests\PageEditRequest;
use Cupa\Http\Requests\BoardAddEditRequest;
use Cupa\Http\Requests\MinuteAddEditRequest;
use Illuminate\Http\Request;
use Cupa\Officer;
use Cupa\OfficerPosition;
use Cupa\Page;
use Cupa\Minute;
use Cupa\Location;
use Session;
use Image;
use DateTime;

class AboutController extends Controller
{
    public function about()
    {
        return redirect()->route('about_mission');
    }

    public function mission()
    {
        $page = Page::fetchBy('route', 'about_mission');
        $actions = 'edit';

        return view('about.mission', compact('page', 'actions'));
    }

    public function missionEdit()
    {
        $page = Page::fetchBy('route', 'about_mission');

        return view('about.mission_edit', compact('page'));
    }

    public function postMissionEdit(PageEditRequest $request)
    {
        $page = Page::fetchBy('route', 'about_mission');

        // get the posted data
        $input = $request->all();
        $page->display = $input['display'];
        $page->content = $input['content'];
        $page->save();

        Session::flash('msg-success', 'Mission statement updated.');

        return redirect()->route('about_mission');
    }

    public function board()
    {
        $page = Page::fetchBy('route', 'about_board');
        $actions = 'add';

        $positions = OfficerPosition::fetchAll();
        $members = Officer::fetchAllCurrent();

        return view('about.board', compact('page', 'actions', 'positions', 'members'));
    }

    public function boardAdd(Request $request)
    {
        $officerPositions = OfficerPosition::fetchForSelect();
        $initial = $request->old('user_id');

        return view('about.board_add', compact('officerPositions', 'initial'));
    }

    public function postBoardAdd(BoardAddEditRequest $request)
    {
        $officerPositions = OfficerPosition::fetchForSelect();
        $input = $request->all();

        // create officer
        $officer = Officer::create([
            'user_id' => $input['user_id'],
            'officer_position_id' => $input['position'],
            'started' => (empty($input['started'])) ? null : convertDate($input['started'], 'Y-m-d'),
            'stopped' => (empty($input['stopped'])) ? null : convertDate($input['stopped'], 'Y-m-d'),
            'description' => $input['description'],
        ]);

        if (!$request->hasFile('avatar') && isset($input['avatar_remove'])) {
            $filePath = public_path().$officer->image;
            if ($officer->image != '/data/users/default.png' && file_exists($filePath)) {
                unlink($filePath);
            }
            $officer->image = '/data/users/default.png';
            $officer->save();
        } elseif ($request->hasFile('avatar')) {
            $filePath = public_path().'/data/officers/'.time().'-'.$officer->id.'.jpg';
            $img = Image::cache(function ($image) use ($filePath, $request) {
                return $image->make($request->file('avatar')->getRealPath())->resize(400, 400)->save($filePath);
            });
            $officer->image = str_replace(public_path(), '', $filePath);
            $officer->save();
        }

        Session::flash('msg-success', 'Officer '.$officerPositions[$officer->officer_position_id].' created');

        return redirect()->route('about_board');
    }

    public function boardEdit(Request $request, $officerId)
    {
        $officerPositions = OfficerPosition::fetchForSelect();
        $officer = Officer::find($officerId);
        $initial = ($request->has('user_id')) ? $request->old('user_id') : $officer->user_id;

        return view('about.board_edit', compact('officerPositions', 'officer', 'initial'));
    }

    public function postBoardEdit(BoardAddEditRequest $request, $officerId)
    {
        $officerPositions = OfficerPosition::fetchForSelect();
        $officer = Officer::find($officerId);
        $input = $request->all();

        if (!$request->hasFile('avatar') && isset($input['avatar_remove'])) {
            $filePath = public_path().$officer->image;
            if ($officer->image != '/data/users/default.png' && file_exists($filePath)) {
                unlink($filePath);
            }
            $officer->image = '/data/users/default.png';
        } elseif ($request->hasFile('avatar')) {
            $filePath = public_path().'/data/officers/'.time().'-'.$officer->id.'.jpg';
            $img = Image::cache(function ($image) use ($filePath, $request) {
                return $image->make($request->file('avatar')->getRealPath())->resize(400, 400)->save($filePath);
            });
            $officer->image = str_replace(public_path(), '', $filePath);
        }

        $officer->user_id = $input['user_id'];
        $officer->officer_position_id = $input['position'];
        $officer->started = (empty($input['started'])) ? null : convertDate($input['started'], 'Y-m-d');
        $officer->stopped = (empty($input['stopped'])) ? null : convertDate($input['stopped'], 'Y-m-d');
        $officer->description = $input['description'];
        $officer->save();

        Session::flash('msg-success', 'Officer '.$officerPositions[$officer->officer_position_id].' updated');

        return redirect()->route('about_board');
    }

    public function boardRemove($officerId)
    {
        $officer = Officer::find($officerId);
        $position = $officer->position()->first()->name;

        // remove image if present
        if ($officer->image != '/data/users/default.png' && file_exists(public_path().$officer->image)) {
            unlink(public_path().$officer->image);
        }

        $officer->delete();
        Session::flash('msg-success', 'Officer '.$position.' removed');

        return redirect()->route('about_board');
    }

    public function minutes()
    {
        $page = Page::fetchBy('route', 'about_minutes');
        $actions = 'add';

        $minutes = Minute::fetchMinutes();

        return view('about.minutes', compact('page', 'actions', 'minutes'));
    }

    public function minutesAdd()
    {
        $locations = Location::fetchForSelect();

        return view('about.minutes_add', compact('locations'));
    }

    public function postMinutesAdd(MinuteAddEditRequest $request)
    {
        $input = $request->all();

        $minute = new Minute();
        $minute->location_id = $input['location_id'];
        $minute->start = convertDate($input['start_date'].' '.$input['start_time'], 'Y-m-d H:i:s');
        $minute->end = convertDate($input['end_date'].' '.$input['end_time'], 'Y-m-d H:i:s');
        $minute->save();

        if ($request->hasFile('pdf')) {
            $filePath = public_path().'/data/minutes/'.$minute->id.'.pdf';
            $request->file('pdf')->move(public_path().'/data/minutes/', $minute->id.'.pdf');
            $minute->pdf = str_replace(public_path(), '', $filePath);
            $minute->save();
        }

        Session::flash('msg-success', 'Meeting minutes for '.convertDate($input['start_date'].' '.$input['start_time'], 'm/d/Y').' added');

        return redirect()->route('about_minutes');
    }

    public function minutesEdit($minuteId)
    {
        $minute = Minute::find($minuteId);
        $locations = Location::fetchForSelect();

        return view('about.minutes_edit', compact('minute', 'locations'));
    }

    public function postMinutesEdit(MinuteAddEditRequest $request, $minuteId)
    {
        $minute = Minute::find($minuteId);
        $input = $request->all();

        $minute->location_id = $input['location_id'];
        $minute->start = convertDate($input['start_date'].' '.$input['start_time'], 'Y-m-d H:i:s');
        $minute->end = convertDate($input['end_date'].' '.$input['end_time'], 'Y-m-d H:i:s');
        $minute->save();

        if ($request->hasFile('pdf')) {
            $request->file('pdf')->move(public_path().'/data/minutes', $minute->id.'.pdf');
            $filePath = public_path().'/data/minutes/'.$minute->id.'.pdf';
            $minute->pdf = str_replace(public_path(), '', $filePath);
            $minute->save();
        }

        Session::flash('msg-success', 'Meeting minutes for '.convertDate($input['start_date'].' '.$input['start_time'], 'm/d/Y').' updated');

        return redirect()->route('about_minutes');
    }

    public function minutesDownload($minuteId)
    {
        $minute = Minute::find($minuteId);

        return response()->download(public_path().$minute->pdf, (new DateTime($minute->start))->format('Y-m-d').'-minutes.pdf');
    }

    public function minutesRemove($minuteId)
    {
        $minute = Minute::find($minuteId);
        $date = convertDate($minute->start, 'm/d/Y');
        if ($minute->pdf !== null && file_exists(public_path().$minute->pdf)) {
            unlink(public_path().$minute->pdf);
        }
        $minute->delete();
        Session::flash('msg-success', 'Meeting minutes for '.$date.' deleted');

        return redirect()->route('about_minutes');
    }

    public function links()
    {
        $page = Page::fetchBy('route', 'about_links');
        $actions = 'edit';

        return view('about.links', compact('page', 'actions'));
    }

    public function linksEdit()
    {
        $page = Page::fetchBy('route', 'about_links');

        return view('about.links_edit', compact('page'));
    }

    public function postLinksEdit(PageEditRequest $request)
    {
        $page = Page::fetchBy('route', 'about_links');
        // get the posted data
        $input = $request->all();

        $page->display = $input['display'];
        $page->content = $input['content'];
        $page->save();

        Session::flash('msg-success', 'Helpful links updated.');

        return redirect()->route('about_links');
    }
}
