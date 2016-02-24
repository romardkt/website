<?php

namespace Cupa\Http\Controllers;

use Cupa\Clinic;
use Cupa\Http\Requests\ClinicEditRequest;
use Cupa\Http\Requests\PageEditRequest;
use Cupa\League;
use Cupa\Page;
use Cupa\Tournament;
use Illuminate\Support\Facades\Session;

class YouthController extends Controller
{
    public function youth()
    {
        return redirect()->route('youth_about');
    }

    public function about()
    {
        $page = Page::fetchBy('route', 'youth_about');
        $actions = 'edit';

        return view('youth.about', compact('page', 'actions'));
    }

    public function aboutEdit()
    {
        $page = Page::fetchBy('route', 'youth_about');

        return view('youth.about_edit', compact('page'));
    }

    public function postAboutEdit(PageEditRequest $request)
    {
        // get the posted data
        $input = $request->all();

        $page = Page::fetchBy('route', 'youth_about');
        $page->content = $input['content'];
        $page->save();

        Session::flash('msg-success', 'Youth about page updated.');

        return redirect()->route('youth_about');
    }

    public function yuc()
    {
        $page = Page::fetchBy('route', 'youth_yuc');
        $actions = 'edit';

        Session::put('waiver_redirect', route('youth_yuc'));

        return view('youth.yuc', compact('page', 'actions'));
    }

    public function yucEdit()
    {
        $page = Page::fetchBy('route', 'youth_yuc');

        return view('youth.yuc_edit', compact('page'));
    }

    public function postYucEdit(PageEditRequest $request)
    {
        // get the posted data
        $input = $request->all();

        $page = Page::fetchBy('route', 'youth_yuc');
        $page->content = $input['content'];
        $page->save();

        Session::flash('msg-success', 'Youth about page updated.');

        return redirect()->route('youth_yuc');
    }

    public function ycc()
    {
        return redirect()->to('http://www.cincyselectultimate.com/Home');
    }

    public function leagues()
    {
        $page = Page::fetchBy('route', 'youth_leagues');
        $actions = 'league_add';
        $season = 'youth';

        $leagues = League::fetchAllLeagues('youth');

        return view('youth.leagues', compact('page', 'actions', 'leagues', 'season'));
    }

    public function league($slug)
    {
        $page = Page::fetchBy('route', 'youth_leagues');
        $actions = null;

        $league = League::fetchBySlug($slug);
        $registration = $league->getRegistrationData();

        return view('youth.league', compact('page', 'actions', 'league', 'registration'));
    }

    public function tournaments()
    {
        $page = Page::fetchBy('route', 'youth_tournaments');
        $actions = 'add';

        $tournaments = Tournament::fetchAllCurrent('youth');

        return view('youth.tournaments', compact('page', 'actions', 'tournaments'));
    }

    public function tournamentsAdd()
    {
        return redirect()->route('around_tournaments_add');
    }

    public function clinics()
    {
        $page = Page::fetchBy('route', 'youth_clinics');
        $actions = 'add';
        $clinics = Clinic::fetchAllClinics('youth');

        return view('youth.clinics', compact('page', 'actions', 'clinics'));
    }

    public function clinic($name)
    {
        $page = Page::fetchBy('route', 'youth_clinics');
        $clinic = Clinic::fetchClinic($name);
        $actions = 'youth_clinics_edit,'.$clinic->name;

        return view('youth.clinic', compact('page', 'actions', 'clinic'));
    }

    public function clinicAdd()
    {
        return view('youth.clinic_add');
    }

    public function postClinicAdd(ClinicEditRequest $request)
    {
        // get the posted data
        $input = $request->all();

        $clinic = new Clinic();
        $clinic->type = 'youth';
        $clinic->name = $input['name'];
        $clinic->display = $input['display'];
        $clinic->content = $input['content'];
        $clinic->save();

        Session::flash('msg-success', 'Youth clinic created.');

        return redirect()->route('youth_clinic', [$clinic->name]);
    }

    public function clinicEdit($name)
    {
        $clinic = Clinic::fetchClinic($name);

        return view('youth.clinic_edit', compact('clinic'));
    }

    public function postClinicEdit($name, ClinicEditRequest $request)
    {
        // get the posted data
        $input = $request->all();

        $clinic = Clinic::fetchClinic($name);
        $clinic->name = $input['name'];
        $clinic->display = $input['display'];
        $clinic->content = $input['content'];
        $clinic->save();

        Session::flash('msg-success', 'Youth clinic updated.');

        return redirect()->route('youth_clinic', [$clinic->name]);
    }

    public function coachingRequirements()
    {
        return redirect()->route('youth_coaching');
    }

    public function coaching()
    {
        $page = Page::fetchBy('route', 'youth_coaching');
        $actions = 'edit';

        return view('youth.coaching', compact('page', 'actions', 'requirements'));
    }

    public function coachingEdit()
    {
        $page = Page::fetchBy('route', 'youth_coaching');

        return view('youth.coaching_edit', compact('page'));
    }

    public function postCoachingEdit(PageEditRequest $request)
    {
        // get the posted data
        $input = $request->all();

        $page = Page::fetchBy('route', 'youth_coaching');
        $page->content = $input['content'];
        $page->save();

        Session::flash('msg-success', 'Youth coaching requirements updated.');

        return redirect()->route('youth_coaching');
    }

    public function yucParents()
    {
        $page = Page::fetchBy('route', 'yuc_parents');
        $actions = 'edit';

        return view('youth.page_view', compact('page', 'actions'));
    }

    public function yucParentsEdit()
    {
        $page = Page::fetchBy('route', 'yuc_parents');

        return view('youth.page_edit', compact('page'));
    }

    public function postYucParentsEdit(PageEditRequest $request)
    {
        // get the posted data
        $input = $request->all();

        $page = Page::fetchBy('route', 'yuc_parents');
        $page->display = $input['display'];
        $page->content = $input['content'];
        $page->save();

        Session::flash('msg-success', $page->display.' updated.');

        return redirect()->route('yuc_parents');
    }

    public function yucCoaches()
    {
        $page = Page::fetchBy('route', 'yuc_coaches');
        $actions = 'edit';

        return view('youth.page_view', compact('page', 'actions'));
    }

    public function yucCoachesEdit()
    {
        $page = Page::fetchBy('route', 'yuc_coaches');

        return view('youth.page_edit', compact('page'));
    }

    public function postYucCoachesEdit(PageEditRequest $request)
    {
        // get the posted data
        $input = $request->all();

        $page = Page::fetchBy('route', 'yuc_coaches');
        $page->display = $input['display'];
        $page->content = $input['content'];
        $page->save();

        Session::flash('msg-success', $page->display.' updated.');

        return redirect()->route('yuc_coaches');
    }
}
