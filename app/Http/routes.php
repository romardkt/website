<?php

Route::get('/', ['as' => 'home', 'uses' => 'PageController@home']);
Route::post('location/add', ['as' => 'location_add', 'uses' => 'PageController@locationAdd']);
Route::get('contact', ['as' => 'contact', 'uses' => 'PageController@contact']);
Route::post('contact', ['as' => 'contact_handle', 'uses' => 'PageController@postContact']);
Route::get('waiver/{year}/download/{type?}', ['as' => 'waiver_download', 'uses' => 'PageController@waiverDownload']);
Route::get('waiver/{year}/{user?}', ['as' => 'waiver', 'uses' => 'PageController@waiver']);
Route::post('waiver/{year}/{user?}', ['as' => 'waiver_post', 'uses' => 'PageController@postWaiver']);
Route::get('paypal/success/{paypal}', ['as' => 'paypal_success', 'uses' => 'PageController@paypalSuccess']);
Route::get('paypal/fail/{paypal}', ['as' => 'paypal_fail', 'uses' => 'PageController@paypalFail']);
Route::get('paypal/{id}/{type}/{paypal_user?}/{team?}', ['as' => 'paypal', 'uses' => 'PageController@paypal']);
Route::get('/scholarship/hoy', ['as' => 'scholarship_hoy', 'uses' => 'ScholarshipController@hoy']);
Route::get('/scholarship/hoy/edit', ['as' => 'scholarship_hoy_edit', 'uses' => 'ScholarshipController@hoyEdit']);
Route::post('/scholarship/hoy/edit', ['as' => 'scholarship_hoy_edit_post', 'uses' => 'ScholarshipController@postHoyEdit']);
Route::get('/scholarship/hoy/submit', ['as' => 'scholarship_hoy_submit', 'uses' => 'ScholarshipController@hoySubmit']);
Route::post('/scholarship/hoy/submit', ['as' => 'scholarship_hoy_submit_post', 'uses' => 'ScholarshipController@postHoySubmit']);
Route::get('/scholarship/hoy/manage', ['as' => 'scholarship_hoy_manage', 'uses' => 'ScholarshipController@hoyManage']);
Route::get('/scholarship/hoy/manage/{scholarship}', ['as' => 'scholarship_hoy_manage_edit', 'uses' => 'ScholarshipController@hoyManageEdit']);
Route::post('/scholarship/hoy/manage/{scholarship}', ['as' => 'scholarship_hoy_manage_edit_post', 'uses' => 'ScholarshipController@postHoyManageEdit']);
Route::get('/scholarship/hoy/manage/{scholarship}/delete', ['as' => 'scholarship_hoy_manage_delete', 'uses' => 'ScholarshipController@hoyManageDelete']);
Route::post('login', ['as' => 'login', 'uses' => 'AuthController@login']);
Route::get('logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
Route::get('register', ['as' => 'register', 'uses' => 'AuthController@register']);
Route::post('register', ['as' => 'register_handle', 'uses' => 'AuthController@postRegister']);
Route::get('activate/{code}', ['as' => 'activate', 'uses' => 'AuthController@activate']);
Route::get('reset', ['as' => 'reset', 'uses' => 'AuthController@reset']);
Route::post('reset', ['as' => 'reset_post', 'uses' => 'AuthController@postReset']);
Route::get('reset/{code}', ['as' => 'do_reset', 'uses' => 'AuthController@doReset']);
Route::post('reset/{code}', ['as' => 'do_reset_post', 'uses' => 'AuthController@postDoReset']);
Route::get('daytonultimate', ['as' => 'leagues_dayton', 'uses' => 'PageController@dayton']);
Route::get('daytonultimate/edit', ['as' => 'leagues_dayton_edit', 'uses' => 'PageController@daytonEdit']);
Route::post('daytonultimate/edit', ['as' => 'leagues_dayton_edit_post', 'uses' => 'PageController@postDaytonEdit']);

Route::group(['prefix' => 'post'], function () {
    Route::get('all', ['as' => 'posts', 'uses' => 'PostController@all']);
    Route::get('add', ['as' => 'posts_add', 'uses' => 'PostController@add']);
    Route::post('add', ['as' => 'posts_add_post', 'uses' => 'PostController@postAdd']);
    Route::get('{slug}', ['as' => 'post_view', 'uses' => 'PostController@view']);
    Route::get('{slug}/edit', ['as' => 'post_edit', 'uses' => 'PostController@edit']);
    Route::post('{slug}/edit', ['as' => 'post_edit_post', 'uses' => 'PostController@postEdit']);
});

Route::group(['prefix' => 'profile'], function () {
    Route::get('/', ['as' => 'profile', 'uses' => 'ProfileController@profile', 'middleware' => 'auth']);
    Route::post('/', ['as' => 'profile_post', 'uses' => 'ProfileController@postProfile', 'middleware' => 'auth']);
    Route::get('password', ['as' => 'profile_password', 'uses' => 'ProfileController@password', 'middleware' => 'auth']);
    Route::post('password', ['as' => 'profile_password_post', 'uses' => 'ProfileController@postPassword', 'middleware' => 'auth']);
    Route::get('minors', ['as' => 'profile_minors', 'uses' => 'ProfileController@minors', 'middleware' => 'auth']);
    Route::get('minors/add', ['as' => 'profile_minor_add', 'uses' => 'ProfileController@minorAdd', 'middleware' => 'auth']);
    Route::post('minors/add', ['as' => 'profile_minor_add_post', 'uses' => 'ProfileController@postMinorAdd', 'middleware' => 'auth']);
    Route::get('minors/{minor}/edit', ['as' => 'profile_minor_edit', 'uses' => 'ProfileController@minorEdit', 'middleware' => 'auth']);
    Route::post('minors/{minor}/edit', ['as' => 'profile_minor_edit_post', 'uses' => 'ProfileController@postMinorEdit', 'middleware' => 'auth']);
    Route::get('minors/{minor}/remove', ['as' => 'profile_minor_remove', 'uses' => 'ProfileController@minorRemove', 'middleware' => 'auth']);
    Route::get('leagues', ['as' => 'profile_leagues', 'uses' => 'ProfileController@leagues', 'middleware' => 'auth']);
    Route::get('teams', ['as' => 'profile_teams', 'uses' => 'ProfileController@teams', 'middleware' => 'auth']);
    Route::get('contacts', ['as' => 'profile_contacts', 'uses' => 'ProfileController@contacts', 'middleware' => 'auth']);
    Route::get('contacts/add', ['as' => 'profile_contact_add', 'uses' => 'ProfileController@contactAdd', 'middleware' => 'auth']);
    Route::post('contacts/add', ['as' => 'profile_contact_add_post', 'uses' => 'ProfileController@postContactAdd', 'middleware' => 'auth']);
    Route::get('contacts/{contact}/edit', ['as' => 'profile_contact_edit', 'uses' => 'ProfileController@contactEdit', 'middleware' => 'auth']);
    Route::post('contacts/{contact}/edit', ['as' => 'profile_contact_edit_post', 'uses' => 'ProfileController@postContactEdit', 'middleware' => 'auth']);
    Route::get('contacts/{contact}/remove', ['as' => 'profile_contact_remove', 'uses' => 'ProfileController@contactRemove', 'middleware' => 'auth']);
    Route::get('volunteer', ['as' => 'profile_volunteer', 'uses' => 'ProfileController@volunteer', 'middleware' => 'auth']);
    Route::get('{slug}', ['as' => 'profile_public', 'uses' => 'ProfileController@publicProfile']);
});

Route::group(['prefix' => 'form'], function () {
    Route::get('{slug}', ['as' => 'form_view', 'uses' => 'FormController@view']);
});

Route::group(['prefix' => 'about'], function () {
    Route::get('/', ['as' => 'about', 'uses' => 'AboutController@about']);
    Route::get('mission', ['as' => 'about_mission', 'uses' => 'AboutController@mission']);
    Route::get('mission/edit', ['as' => 'about_mission_edit', 'uses' => 'AboutController@missionEdit']);
    Route::post('mission/edit', ['as' => 'about_mission_edit_post', 'uses' => 'AboutController@postMissionEdit']);
    Route::get('board', ['as' => 'about_board', 'uses' => 'AboutController@board']);
    Route::get('board/add', ['as' => 'about_board_add', 'uses' => 'AboutController@boardAdd']);
    Route::post('board/add', ['as' => 'about_board_add_post', 'uses' => 'AboutController@postBoardAdd']);
    Route::get('board/edit/{officer}', ['as' => 'about_board_edit', 'uses' => 'AboutController@boardEdit']);
    Route::post('board/edit/{officer}', ['as' => 'about_board_edit_post', 'uses' => 'AboutController@postBoardEdit']);
    Route::get('board/remove/{officer}', ['as' => 'about_board_remove', 'uses' => 'AboutController@boardRemove']);
    Route::get('minutes', ['as' => 'about_minutes', 'uses' => 'AboutController@minutes']);
    Route::get('minutes/add', ['as' => 'about_minutes_add', 'uses' => 'AboutController@minutesAdd']);
    Route::post('minutes/add', ['as' => 'about_minutes_add_post', 'uses' => 'AboutController@postMinutesAdd']);
    Route::get('minutes/edit/{minute}', ['as' => 'about_minutes_edit', 'uses' => 'AboutController@minutesEdit']);
    Route::post('minutes/edit/{minute}', ['as' => 'about_minutes_edit_post', 'uses' => 'AboutController@postMinutesEdit']);
    Route::get('minutes/download/{minute}', ['as' => 'about_minutes_download', 'uses' => 'AboutController@minutesDownload']);
    Route::get('minutes/remove/{minute}', ['as' => 'about_minutes_remove', 'uses' => 'AboutController@minutesRemove']);
    Route::get('links', ['as' => 'about_links', 'uses' => 'AboutController@links']);
    Route::get('links/edit', ['as' => 'about_links_edit', 'uses' => 'AboutController@linksEdit']);
    Route::post('links/edit', ['as' => 'about_links_edit_post', 'uses' => 'AboutController@postLinksEdit']);
});

Route::group(['prefix' => 'volunteer'], function () {
    Route::get('/', ['as' => 'volunteer', 'uses' => 'VolunteerController@volunteer']);
    Route::get('about', ['as' => 'volunteer_about', 'uses' => 'VolunteerController@about']);
    Route::get('about/edit', ['as' => 'volunteer_about_edit', 'uses' => 'VolunteerController@aboutEdit']);
    Route::post('about/edit', ['as' => 'volunteer_about_edit_post', 'uses' => 'VolunteerController@postAboutEdit']);
    Route::get('signup', ['as' => 'volunteer_signup', 'uses' => 'VolunteerController@signup']);
    Route::post('signup', ['as' => 'volunteer_signup_post', 'uses' => 'VolunteerController@postSignup']);
    Route::get('show', ['as' => 'volunteer_show', 'uses' => 'VolunteerController@show']);
    Route::get('show/past', ['as' => 'volunteer_show_past', 'uses' => 'VolunteerController@showPast']);
    Route::get('show/add', ['as' => 'volunteer_show_add', 'uses' => 'VolunteerController@showAdd']);
    Route::post('show/add', ['as' => 'volunteer_show_add_post', 'uses' => 'VolunteerController@postShowAdd']);
    Route::get('show/edit/{event}', ['as' => 'volunteer_show_edit', 'uses' => 'VolunteerController@showEdit']);
    Route::post('show/edit/{event}', ['as' => 'volunteer_show_edit_post', 'uses' => 'VolunteerController@postShowEdit']);
    Route::get('show/members/{event}', ['as' => 'volunteer_show_members', 'uses' => 'VolunteerController@showMembers']);
    Route::get('show/members/export/{event}', ['as' => 'volunteer_show_members_export', 'uses' => 'VolunteerController@showMembersExport']);
    Route::get('show/signup/{event}', ['as' => 'volunteer_show_signup', 'uses' => 'VolunteerController@showSignup']);
    Route::post('show/signup/{event}', ['as' => 'volunteer_show_signup_post', 'uses' => 'VolunteerController@postShowSignup']);
    Route::get('list', ['as' => 'volunteer_list', 'uses' => 'VolunteerController@pool']);
    Route::get('list/download', ['as' => 'volunteer_list_download', 'uses' => 'VolunteerController@poolDownload']);
});

Route::group(['prefix' => 'youth'], function () {
    Route::get('/', ['as' => 'youth', 'uses' => 'YouthController@youth']);
    Route::get('about', ['as' => 'youth_about', 'uses' => 'YouthController@about']);
    Route::get('about/edit', ['as' => 'youth_about_edit', 'uses' => 'YouthController@aboutEdit']);
    Route::post('about/edit', ['as' => 'youth_about_edit_post', 'uses' => 'YouthController@postAboutEdit']);
    Route::get('yuc', ['as' => 'youth_yuc', 'uses' => 'YouthController@yuc']);
    Route::get('yuc/parents', ['as' => 'yuc_parents', 'uses' => 'YouthController@yucParents']);
    Route::get('yuc/coaches', ['as' => 'yuc_coaches', 'uses' => 'YouthController@yucCoaches']);
    Route::get('yuc/edit', ['as' => 'youth_yuc_edit', 'uses' => 'YouthController@yucEdit']);
    Route::post('yuc/edit', ['as' => 'youth_yuc_edit_post', 'uses' => 'YouthController@postYucEdit']);
    Route::get('yuc/parents/edit', ['as' => 'yuc_parents_edit', 'uses' => 'YouthController@yucParentsEdit']);
    Route::post('yuc/parents/edit', ['as' => 'yuc_parents_edit_post', 'uses' => 'YouthController@postYucParentsEdit']);
    Route::get('yuc/coaches/edit', ['as' => 'yuc_coaches_edit', 'uses' => 'YouthController@yucCoachesEdit']);
    Route::post('yuc/coaches/edit', ['as' => 'yuc_coaches_edit_post', 'uses' => 'YouthController@postYucCoachesEdit']);
    Route::get('ycc', ['as' => 'youth_ycc', 'uses' => 'YouthController@ycc']);
    Route::get('leagues', ['as' => 'youth_leagues', 'uses' => 'YouthController@leagues']);
    Route::get('tournaments', ['as' => 'youth_tournaments', 'uses' => 'YouthController@tournaments']);
    Route::get('tournaments/add', ['as' => 'youth_tournaments_add', 'uses' => 'YouthController@tournamentsAdd']);
    Route::get('clinics', ['as' => 'youth_clinics', 'uses' => 'YouthController@clinics']);
    Route::get('clinics/add', ['as' => 'youth_clinics_add', 'uses' => 'YouthController@clinicAdd']);
    Route::post('clinics/add', ['as' => 'youth_clinics_add_post', 'uses' => 'YouthController@postClinicAdd']);
    Route::get('clinic/{name}', ['as' => 'youth_clinic', 'uses' => 'YouthController@clinic']);
    Route::get('clinic/{name}/edit', ['as' => 'youth_clinics_edit', 'uses' => 'YouthController@clinicEdit']);
    Route::post('clinic/{name}/edit', ['as' => 'youth_clinics_edit_post', 'uses' => 'YouthController@postClinicEdit']);
    Route::get('coaching/requirements', ['as' => 'youth_coaching_requirements', 'uses' => 'YouthController@coachingRequirements']);
    Route::get('coaching', ['as' => 'youth_coaching', 'uses' => 'YouthController@coaching']);
    Route::get('coaching/edit', ['as' => 'youth_coaching_edit', 'uses' => 'YouthController@coachingEdit']);
    Route::post('coaching/edit', ['as' => 'youth_coaching_edit_post', 'uses' => 'YouthController@postCoachingEdit']);
});

Route::group(['prefix' => 'leagues'], function () {
    Route::get('/', ['as' => 'leagues', 'uses' => 'League\PageController@leagues']);
    Route::get('winter', ['as' => 'leagues_winter', 'uses' => 'League\PageController@leagues']);
    Route::get('spring', ['as' => 'leagues_spring', 'uses' => 'League\PageController@leagues']);
    Route::get('summer', ['as' => 'leagues_summer', 'uses' => 'League\PageController@leagues']);
    Route::get('fall', ['as' => 'leagues_fall', 'uses' => 'League\PageController@leagues']);
    Route::get('team_players', ['as' => 'league_team_players', 'uses' => 'League\PageController@teamPlayers']);
    Route::get('team_record', ['as' => 'league_team_record', 'uses' => 'League\PageController@teamRecord']);
    Route::get('{slug}', ['as' => 'league', 'uses' => 'League\PageController@league']);
    Route::get('{season}/{slug}', ['as' => 'leagues_old', 'uses' => 'League\PageController@leagueOld'])
        ->where('season', '(winter|spring|summer|fall)');
    Route::get('{slug}/teams', ['as' => 'league_teams', 'uses' => 'League\PageController@teams']);
    Route::get('{slug}/schedule', ['as' => 'league_schedule', 'uses' => 'League\PageController@schedule']);
    Route::get('{slug}/email', ['as' => 'league_email', 'uses' => 'League\PageController@email']);
    Route::post('{slug}/email', ['as' => 'league_email_post', 'uses' => 'League\PageController@postEmail']);
    Route::get('{slug}/coaches', ['as' => 'league_coaches', 'uses' => 'League\PageController@coaches']);
    Route::get('{slug}/coaches/download', ['as' => 'league_coaches_download', 'uses' => 'League\PageController@coachesDownload']);
    Route::get('{slug}/coaches/email', ['as' => 'league_coaches_email', 'uses' => 'League\EditController@coachesEmail']);
    Route::post('{slug}/coaches/email', ['as' => 'league_coaches_email_post', 'uses' => 'League\EditController@postCoachesEmail']);

    // registration
    Route::get('{slug}/register/success', ['as' => 'league_success', 'uses' => 'League\RegistrationController@success']);
    Route::post('{slug}/register/success', ['as' => 'league_success_post', 'uses' => 'League\RegistrationController@postSuccess']);
    Route::any('{slug}/register/{state?}', ['as' => 'league_register', 'uses' => 'League\RegistrationController@register']);

    // Management
    Route::get('{slug}/team/add', ['as' => 'league_team_add', 'uses' => 'League\ManageController@teamAdd']);
    Route::post('{slug}/team/add', ['as' => 'league_team_add_post', 'uses' => 'League\ManageController@postTeamAdd']);
    Route::get('{slug}/team/{team}/edit', ['as' => 'league_team_edit', 'uses' => 'League\ManageController@teamEdit']);
    Route::post('{slug}/team/{team}/edit', ['as' => 'league_team_edit_post', 'uses' => 'League\ManageController@postTeamEdit']);
    Route::get('{slug}/team/{team}/remove', ['as' => 'league_team_remove', 'uses' => 'League\ManageController@teamRemove']);

    Route::get('{slug}/schedule/add', ['as' => 'league_schedule_add', 'uses' => 'League\ManageController@scheduleAdd']);
    Route::post('{slug}/schedule/add', ['as' => 'league_schedule_add_post', 'uses' => 'League\ManageController@postScheduleAdd']);

    Route::get('{slug}/schedule/edit/{game}', ['as' => 'league_schedule_edit', 'uses' => 'League\ManageController@scheduleEdit']);
    Route::post('{slug}/schedule/edit/{game}', ['as' => 'league_schedule_edit_post', 'uses' => 'League\ManageController@postScheduleEdit']);
    Route::get('{slug}/schedule/remove/{game}', ['as' => 'league_schedule_remove', 'uses' => 'League\ManageController@scheduleRemove']);

    //Route::any('{slug}/schedule/generate', ['as' => 'league_schedule_generate', 'uses' => 'LeagueController@schedule_generate']);

    Route::any('{slug}/edit/{type}', ['as' => 'league_edit', 'uses' => 'League\EditController@handle']);

    Route::get('{slug}/coaches/{member}/edit', ['as' => 'league_coaches_edit', 'uses' => 'League\EditController@coachesEdit']);
    Route::post('{slug}/coaches/{member}/edit', ['as' => 'league_coaches_edit_post', 'uses' => 'League\EditController@postCoachesEdit']);
    Route::get('{slug}/shirts', ['as' => 'league_shirts', 'uses' => 'League\ManageController@shirts']);
    Route::get('{slug}/shirts/download', ['as' => 'league_shirts_download', 'uses' => 'League\ManageController@shirtsDownload']);
    Route::get('{slug}/emergency', ['as' => 'league_emergency', 'uses' => 'League\ManageController@emergency']);
    Route::get('{slug}/emergency/download', ['as' => 'league_emergency_download', 'uses' => 'League\ManageController@emergencyDownload']);
    Route::get('{slug}/requests', ['as' => 'league_requests', 'uses' => 'League\ManageController@requests']);
    Route::get('{slug}/requests/accept/{memberId?}', ['as' => 'league_requests_accept', 'uses' => 'League\ManageController@requestsAccept']);
    Route::get('{slug}/players', ['as' => 'league_players', 'uses' => 'League\ManageController@players']);
    Route::get('{slug}/players/download', ['as' => 'league_players_download', 'uses' => 'League\ManageController@playersDownload']);
    Route::get('{slug}/status/{all}/download', ['as' => 'league_status_download', 'uses' => 'League\ManageController@statusDownload']);
    Route::get('{slug}/status/{all?}', ['as' => 'league_status', 'uses' => 'League\ManageController@status']);
    Route::post('{slug}/status/toggle', ['as' => 'league_status_toggle', 'uses' => 'League\ManageController@statusToggle']);
    Route::get('{slug}/manage', ['as' => 'league_manage', 'uses' => 'League\ManageController@manage']);
    Route::post('{slug}/manage', ['as' => 'league_manage_post', 'uses' => 'League\ManageController@postManage']);
    Route::get('{slug}/waitlist', ['as' => 'league_waitlist', 'uses' => 'League\ManageController@waitlist']);
    Route::get('{slug}/waitlist/download', ['as' => 'league_waitlist_download', 'uses' => 'League\ManageController@waitlistDownload']);
    Route::get('{slug}/waitlist/accept/{member}', ['as' => 'league_waitlist_accept', 'uses' => 'League\ManageController@waitlistAccept']);

    // Managing
    Route::get('{slug}/archive', ['as' => 'league_archive', 'uses' => 'League\AdminController@archive']);
    Route::get('add/{season}', ['as' => 'league_add', 'uses' => 'League\AdminController@add']);
    Route::post('add/{season}', ['as' => 'league_add_post', 'uses' => 'League\AdminController@postAdd']);
});

Route::group(['prefix' => 'around'], function () {
    Route::get('/', ['as' => 'around', 'uses' => 'AroundController@around']);
    Route::get('pickups', ['as' => 'around_pickups', 'uses' => 'AroundController@pickups']);
    Route::get('pickups/add', ['as' => 'around_pickups_add', 'uses' => 'AroundController@pickupsAdd']);
    Route::post('pickups/add', ['as' => 'around_pickups_add_post', 'uses' => 'AroundController@postPickupsAdd']);
    Route::get('pickups/{pickup}/edit', ['as' => 'around_pickups_edit', 'uses' => 'AroundController@pickupsEdit']);
    Route::post('pickups/{pickup}/edit', ['as' => 'around_pickups_edit_post', 'uses' => 'AroundController@postPickupsEdit']);
    Route::get('pickups/{pickup}/remove', ['as' => 'around_pickups_remove', 'uses' => 'AroundController@pickupsRemove']);
    Route::get('tournaments', ['as' => 'around_tournaments', 'uses' => 'AroundController@tournaments']);
    Route::get('tournaments/add', ['as' => 'around_tournaments_add', 'uses' => 'AroundController@tournamentsAdd']);
    Route::post('tournaments/add', ['as' => 'around_tournaments_add_post', 'uses' => 'AroundController@postTournamentsAdd']);
    Route::get('discgolf', ['as' => 'around_discgolf', 'uses' => 'AroundController@discgolf']);
    Route::get('fields', ['as' => 'around_fields', 'uses' => 'AroundController@fields']);
});

Route::group(['prefix' => 'teams'], function () {
    Route::get('/', ['as' => 'teams', 'uses' => 'TeamsController@teams']);
    Route::get('add', ['as' => 'teams_add', 'uses' => 'TeamsController@teamsAdd']);
    Route::post('add', ['as' => 'teams_add_post', 'uses' => 'TeamsController@postTeamsAdd']);
    Route::get('edit', ['as' => 'teams_edit', 'uses' => 'TeamsController@teamsEdit']);
    Route::post('edit', ['as' => 'teams_edit_post', 'uses' => 'TeamsController@postTeamsEdit']);
    Route::get('{name}', ['as' => 'teams_show', 'uses' => 'TeamsController@show']);
    Route::get('{name}/edit', ['as' => 'teams_show_edit', 'uses' => 'TeamsController@showEdit']);
    Route::post('{name}/edit', ['as' => 'teams_show_edit_post', 'uses' => 'TeamsController@postShowEdit']);
});

Route::group(['prefix' => 'tournament'], function () {
    Route::get('/{name}/{year?}', ['as' => 'tournament', 'uses' => 'TournamentController@tournament']);
    Route::get('/{name}/{year}/admin', ['as' => 'tournament_admin', 'uses' => 'TournamentController@admin']);
    Route::post('/{name}/{year}/admin', ['as' => 'tournament_admin_post', 'uses' => 'TournamentController@postAdmin']);
    Route::get('/{name}/{year}/bid', ['as' => 'tournament_bid', 'uses' => 'TournamentController@bid']);
    Route::post('/{name}/{year}/bid', ['as' => 'tournament_bid_post', 'uses' => 'TournamentController@postBid']);
    Route::get('/{name}/{year}/bid/edit', ['as' => 'tournament_bid_edit', 'uses' => 'TournamentController@bidEdit']);
    Route::post('/{name}/{year}/bid/edit', ['as' => 'tournament_bid_edit_post', 'uses' => 'TournamentController@postBidEdit']);
    Route::get('/{name}/{year}/teams/add', ['as' => 'tournament_teams_add', 'uses' => 'TournamentController@teamsAdd']);
    Route::post('/{name}/{year}/teams/add', ['as' => 'tournament_teams_add_post', 'uses' => 'TournamentController@postTeamsAdd']);
    Route::get('/teams/{team}/edit', ['as' => 'tournament_teams_edit', 'uses' => 'TournamentController@teamsEdit']);
    Route::post('/teams/{team}/edit', ['as' => 'tournament_teams_edit_post', 'uses' => 'TournamentController@postTeamsEdit']);
    Route::get('/teams/{team}/remove', ['as' => 'tournament_teams_remove', 'uses' => 'TournamentController@teamsRemove']);
    Route::get('/{name}/{year}/teams/{division?}', ['as' => 'tournament_teams', 'uses' => 'TournamentController@teams']);
    Route::get('/{name}/{year}/schedule', ['as' => 'tournament_schedule', 'uses' => 'TournamentController@schedule']);
    Route::post('/{name}/{year}/schedule', ['as' => 'tournament_schedule_post', 'uses' => 'TournamentController@postSchedule']);
    Route::get('/{name}/{year}/location', ['as' => 'tournament_location', 'uses' => 'TournamentController@location']);
    Route::get('/{name}/{year}/contact', ['as' => 'tournament_contact', 'uses' => 'TournamentController@contact']);
    Route::post('/{name}/{year}/contact', ['as' => 'tournament_contact_post', 'uses' => 'TournamentController@postContact']);
    Route::get('/description/{tournament}/edit', ['as' => 'tournament_description_edit', 'uses' => 'TournamentController@descriptionEdit']);
    Route::post('/description/{tournament}/edit', ['as' => 'tournament_description_edit_post', 'uses' => 'TournamentController@postDescriptionEdit']);
    Route::get('/{name}/{year}/feed/add', ['as' => 'tournament_feed_add', 'uses' => 'TournamentController@feedAdd']);
    Route::post('/{name}/{year}/feed/add', ['as' => 'tournament_feed_add_post', 'uses' => 'TournamentController@postFeedAdd']);
    Route::get('/feed/{feed}/edit', ['as' => 'tournament_feed_edit', 'uses' => 'TournamentController@feedEdit']);
    Route::post('/feed/{feed}/edit', ['as' => 'tournament_feed_edit_post', 'uses' => 'TournamentController@postFeedEdit']);
    Route::get('/feed/{feed}/remove', ['as' => 'tournament_feed_remove', 'uses' => 'TournamentController@feedRemove']);
    Route::get('/schedule/{tournament}/edit', ['as' => 'tournament_schedule_edit', 'uses' => 'TournamentController@scheduleEdit']);
    Route::post('/schedule/{tournament}/edit', ['as' => 'tournament_schedule_edit_post', 'uses' => 'TournamentController@postScheduleEdit']);
    Route::get('/{name}/{year}/contact/add', ['as' => 'tournament_contact_add', 'uses' => 'TournamentController@contactAdd']);
    Route::post('/{name}/{year}/contact/add', ['as' => 'tournament_contact_add_post', 'uses' => 'TournamentController@postContactAdd']);
    Route::get('/contact/{member}/remove', ['as' => 'tournament_contact_remove', 'uses' => 'TournamentController@contactRemove']);
    Route::get('/contact/{member}/{direction}', ['as' => 'tournament_contact_order', 'uses' => 'TournamentController@contactOrder']);
    Route::get('/nationals/2014/fans', ['as' => 'tournament_2014_nationals_fans', 'uses' => 'TournamentController@nationals2014Fans']);
    Route::get('/{name}/{year}/location/add', ['as' => 'tournament_location_add', 'uses' => 'TournamentController@locationAdd']);
    Route::post('/{name}/{year}/location/add', ['as' => 'tournament_location_add_post', 'uses' => 'TournamentController@postLocationAdd']);
    Route::get('/location/{location}/edit', ['as' => 'tournament_location_edit', 'uses' => 'TournamentController@locationEdit']);
    Route::post('/location/{location}/edit', ['as' => 'tournament_location_edit_post', 'uses' => 'TournamentController@postLocationEdit']);
    Route::get('/location/{location}/remove', ['as' => 'tournament_location_remove', 'uses' => 'TournamentController@locationRemove']);
    Route::get('/{name}/{year}/location/map/edit', ['as' => 'tournament_location_map_edit', 'uses' => 'TournamentController@locationMapEdit']);
    Route::post('/{name}/{year}/location/map/edit', ['as' => 'tournament_location_map_edit_post', 'uses' => 'TournamentController@postLocationMapEdit']);
    Route::get('/{name}/{year}/payment', ['as' => 'tournament_payment', 'uses' => 'TournamentController@payment']);
    Route::get('/scinny/2014/masters', ['as' => 'tournament_masters_2014', 'uses' => 'TournamentController@masters2014']);
    Route::get('/scinny/2015/masters', ['as' => 'tournament_masters_2015', 'uses' => 'TournamentController@masters2015']);
});

Route::group(['prefix' => 'typeahead'], function () {
    Route::get('users/{filter?}', ['as' => 'typeahead_users', 'uses' => 'TypeaheadController@users']);
    Route::get('members/{league?}', ['as' => 'typeahead_members', 'uses' => 'TypeaheadController@members']);
});

Route::group(['prefix' => 'manage'], function () {
    Route::get('/', ['as' => 'manage', 'uses' => 'ManageController@manage']);
    Route::get('unpaid', ['as' => 'manage_unpaid', 'uses' => 'ManageController@unpaid']);
    Route::get('duplicates', ['as' => 'manage_duplicates', 'uses' => 'ManageController@duplicates']);
    Route::post('duplicates', ['as' => 'manage_duplicates_post', 'uses' => 'ManageController@postDuplicates']);
    Route::get('league_players', ['as' => 'manage_league_players', 'uses' => 'ManageController@leaguePlayers']);
    Route::post('league_players', ['as' => 'manage_league_players_handle', 'uses' => 'ManageController@postLeaguePlayers']);
    Route::get('load_league_teams', ['as' => 'manage_load_league_teams', 'uses' => 'ManageController@loadLeagueTeams']);
    Route::get('users', ['as' => 'manage_users', 'uses' => 'ManageController@users']);
    Route::post('users_detail', ['as' => 'manage_users_detail', 'uses' => 'ManageController@usersDetail']);
    Route::get('impersonate/{user}', ['as' => 'manage_impersonate', 'uses' => 'ManageController@impersonate']);
    Route::get('forms', ['as' => 'manage_forms', 'uses' => 'ManageController@forms']);
    Route::get('forms/add', ['as' => 'manage_forms_add', 'uses' => 'ManageController@formsAdd']);
    Route::post('forms/add', ['as' => 'manage_forms_add_post', 'uses' => 'ManageController@postFormsAdd']);
    Route::get('forms/{slug}/edit', ['as' => 'manage_forms_edit', 'uses' => 'ManageController@formsEdit']);
    Route::post('forms/{slug}/edit', ['as' => 'manage_forms_edit_post', 'uses' => 'ManageController@postFormsEdit']);
    Route::get('forms/{slug}/remove', ['as' => 'manage_forms_remove', 'uses' => 'ManageController@formsRemove']);
    Route::get('coaches', ['as' => 'manage_coaches', 'uses' => 'ManageController@coaches']);
    Route::get('coaches/download', ['as' => 'manage_coaches_download', 'uses' => 'ManageController@coachesDownload']);
    Route::get('files', ['as' => 'manage_files', 'uses' => 'ManageController@files']);
    Route::get('files/add', ['as' => 'manage_files_add', 'uses' => 'ManageController@filesAdd']);
    Route::post('files/add', ['as' => 'manage_files_add_post', 'uses' => 'ManageController@postFilesAdd']);
    Route::get('files/{file}/remove', ['as' => 'manage_files_remove', 'uses' => 'ManageController@filesRemove']);
});
