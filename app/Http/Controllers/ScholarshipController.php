<?php

namespace Cupa\Http\Controllers;

use Cupa\Http\Requests\HoyScholarshipSubmitRequest;
use Cupa\Http\Requests\HoyScholarshipUpdateRequest;
use Cupa\Http\Requests\PageEditRequest;
use Cupa\Page;
use Cupa\Scholarship;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class ScholarshipController extends Controller
{
    public function hoy()
    {
        $page = Page::fetchBy('route', 'scholarship_hoy');

        return view('scholarship.hoy', compact('page'));
    }

    public function hoyEdit()
    {
        $page = Page::fetchBy('route', 'scholarship_hoy');

        return view('scholarship.hoy_edit', compact('page'));
    }

    public function postHoyEdit(PageEditRequest $request)
    {
        $page = Page::fetchBy('route', 'scholarship_hoy');

        // get the posted data
        $input = $request->all();

        $page->display = $input['display'];
        $page->content = $input['content'];
        $page->save();

        Session::flash('msg-success', 'Hoy Scholarship updated.');

        return redirect()->route('scholarship_hoy');
    }

    public function hoySubmit()
    {
        $page = Page::fetchBy('route', 'scholarship_hoy');

        return view('scholarship.hoy_submit', compact('page'));
    }

    public function postHoySubmit(HoyScholarshipSubmitRequest $request)
    {
        // get the posted data
        $input = $request->all();

        $scholarship = new Scholarship();
        $scholarship->scholarship = 'hoy';
        $scholarship->name = $input['name'];
        $scholarship->email = $input['email'];
        $scholarship->comments = (empty($input['comments'])) ? null : $input['comments'];
        $scholarship->updated_by = (Auth::check()) ? Auth::id() : null;
        $scholarship->save();

        if ($request->hasFile('document')) {
            $extension = $request->file('document')->getClientOriginalExtension();
            $filePath = public_path().'/data/scholarships/'.$scholarship->id.'.'.$extension;
            $request->file('document')->move(public_path().'/data/scholarships/', $scholarship->id.'.'.$extension);
            $scholarship->document = str_replace(public_path(), '', $filePath);
            $scholarship->save();
        }

        $data = $scholarship->toArray();
        unset($data['id']);
        unset($data['scholarship']);
        $data['document'] = asset($data['document']);
        unset($data['updated_by']);
        unset($data['updated_at']);

        Mail::send('emails.scholarship', ['route' => 'scholarship_hoy_manage', 'title' => 'Chris Hoy Scholarship', 'data' => $data], function ($m) {
            if (App::environment() == 'prod') {
                $m->to('hoy-scholarship@cincyultimate.org', 'Chris Hoy Scholarship');
            } elseif (Auth::check()) {
                $m->to(Auth::user()->email, Auth::user()->fullname());
            } else {
                $m->to('kcin1018@gmail.com', 'Nick Felicelli');
            }

            $m->subject('[CUPA] Chis Hoy Scholarship submission');
        });

        Session::flash('msg-success', 'Your scholarship submission was sent');

        return redirect()->route('scholarship_hoy');
    }

    public function hoyManage()
    {
        $page = Page::fetchBy('route', 'scholarship_hoy');
        $submissions = Scholarship::fetchSubmissions('hoy');

        return view('scholarship.hoy_manage', compact('page', 'submissions'));
    }

    public function hoyManageEdit(Scholarship $submission)
    {
        $page = Page::fetchBy('route', 'scholarship_hoy');

        return view('scholarship.hoy_manage_edit', compact('page', 'submission'));
    }

    public function postHoyManageEdit(HoyScholarshipUpdateRequest $request, Scholarship $submission)
    {
        // get the posted data
        $input = $request->all();

        $submission->comments = $input['comments'];
        $submission->accepted = (empty($input['accepted'])) ? 0 : 1;
        $submission->save();

        Session::flash('msg-success', 'Submission updated');

        return redirect()->route('scholarship_hoy_manage');
    }

    public function hoyManageDelete(Scholarship $submission)
    {
        // remove document file
        if (file_exists(public_path().$submission->document)) {
            unlink(public_path().$submission->document);
        }

        // delete the database entry
        $submission->delete();

        Session::flash('msg-success', 'Scholarship submission was removed');

        return redirect()->route('scholarship_hoy_manage');
    }
}
