<?php

Route::model('scholarship_id', 'Cupa\Scholarship');
Route::model('minute_id', 'Cupa\Minute');
Route::model('officer_id', 'Cupa\Officer');
Route::model('user_id', 'Cupa\User');
Route::model('league_id', 'Cupa\League');
Route::model('pickup_id', 'Cupa\Pickup');

Route::get('/', ['as' => 'home', 'uses' => 'PageController@home']);
Route::post('location/add', ['as' => 'location_add', 'uses' => 'PageController@locationAdd']);
Route::get('contact', ['as' => 'contact', 'uses' => 'PageController@contact']);
Route::post('contact', ['as' => 'contact_handle', 'uses' => 'PageController@postContact']);
Route::any('waiver/{year}/download/{type?}', ['as' => 'waiver_download', 'uses' => 'PageController@waiver_download']);
Route::any('waiver/{year}/{user_id?}', ['as' => 'waiver', 'uses' => 'PageController@waiver']);
Route::any('paypal/success/{id}', ['as' => 'paypal_success', 'uses' => 'PageController@paypal_success']);
Route::any('paypal/fail/{id}', ['as' => 'paypal_fail', 'uses' => 'PageController@paypal_fail']);
Route::any('paypal/{id}/{type}/{user_id?}/{team_id?}', ['as' => 'paypal', 'uses' => 'PageController@paypal']);

Route::get('/scholarship/hoy', ['as' => 'about_scholarship_hoy', 'uses' => 'ScholarshipController@hoy']);
Route::get('/scholarship/hoy', ['as' => 'scholarship_hoy', 'uses' => 'ScholarshipController@hoy']);
Route::get('/scholarship/hoy/edit', ['as' => 'scholarship_hoy_edit', 'uses' => 'ScholarshipController@hoyEdit']);
Route::post('/scholarship/hoy/edit', ['as' => 'scholarship_hoy_edit_post', 'uses' => 'ScholarshipController@postHoyEdit']);
Route::get('/scholarship/hoy/submit', ['as' => 'scholarship_hoy_submit', 'uses' => 'ScholarshipController@hoySubmit']);
Route::post('/scholarship/hoy/submit', ['as' => 'scholarship_hoy_submit_post', 'uses' => 'ScholarshipController@postHoySubmit']);
Route::get('/scholarship/hoy/manage', ['as' => 'scholarship_hoy_manage', 'uses' => 'ScholarshipController@hoyManage']);
Route::get('/scholarship/hoy/manage/{scholarship_id}', ['as' => 'scholarship_hoy_manage_edit', 'uses' => 'ScholarshipController@hoyManageEdit']);
Route::post('/scholarship/hoy/manage/{scholarship_id}', ['as' => 'scholarship_hoy_manage_edit_post', 'uses' => 'ScholarshipController@postHoyManageEdit']);
Route::any('/scholarship/hoy/manage/{scholarship_id}/delete', ['as' => 'scholarship_hoy_manage_delete', 'uses' => 'ScholarshipController@hoyManageDelete']);

Route::post('login', ['as' => 'login', 'uses' => 'AuthController@login']);
Route::get('logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
Route::get('register', ['as' => 'register', 'uses' => 'AuthController@register']);
Route::post('register', ['as' => 'register_handle', 'uses' => 'AuthController@postRegister']);
Route::any('activate/{code}', ['as' => 'activate', 'uses' => 'AuthController@activate']);
Route::any('reset', ['as' => 'reset', 'uses' => 'AuthController@reset']);
Route::any('reset/{code}', ['as' => 'do_reset', 'uses' => 'AuthController@do_reset']);

Route::get('daytonultimate', ['as' => 'leagues_dayton', 'uses' => 'PageController@dayton']);
Route::any('daytonultimate/edit', ['as' => 'leagues_dayton_edit', 'uses' => 'PageController@dayton_edit']);

Route::group(['prefix' => 'post'], function () {
    Route::get('all', ['as' => 'posts', 'uses' => 'PostController@all']);
    Route::get('add', ['as' => 'posts_add', 'uses' => 'PostController@add']);
    Route::post('add', ['as' => 'posts_add_post', 'uses' => 'PostController@postAdd']);
    Route::get('{slug}', ['as' => 'post_view', 'uses' => 'PostController@view']);
    Route::get('{slug}/edit', ['as' => 'post_edit', 'uses' => 'PostController@edit']);
    Route::post('{slug}/edit', ['as' => 'post_edit_post', 'uses' => 'PostController@postEdit']);
});

Route::group(['prefix' => 'profile'], function () {
    Route::any('/', ['as' => 'profile', 'uses' => 'ProfileController@profile']);
    Route::any('password', ['as' => 'profile_password', 'uses' => 'ProfileController@password']);
    Route::get('minors', ['as' => 'profile_minors', 'uses' => 'ProfileController@minors']);
    Route::any('minors/add', ['as' => 'profile_minor_add', 'uses' => 'ProfileController@minor_add']);
    Route::any('minors/{minor_id}/edit', ['as' => 'profile_minor_edit', 'uses' => 'ProfileController@minor_edit']);
    Route::get('minors/{minor_id}/remove', ['as' => 'profile_minor_remove', 'uses' => 'ProfileController@minor_remove']);
    Route::get('leagues', ['as' => 'profile_leagues', 'uses' => 'ProfileController@leagues']);
    Route::get('teams', ['as' => 'profile_teams', 'uses' => 'ProfileController@teams']);
    Route::get('contacts', ['as' => 'profile_contacts', 'uses' => 'ProfileController@contacts']);
    Route::any('contacts/add', ['as' => 'profile_contact_add', 'uses' => 'ProfileController@contact_add']);
    Route::any('contacts/{minor_id}/edit', ['as' => 'profile_contact_edit', 'uses' => 'ProfileController@contact_edit']);
    Route::get('contacts/{minor_id}/remove', ['as' => 'profile_contact_remove', 'uses' => 'ProfileController@contact_remove']);
    Route::post('add/contact', ['as' => 'profile_add_contact', 'uses' => 'ProfileController@add_contact']);
    Route::post('remove/contact', ['as' => 'profile_remove_contact', 'uses' => 'ProfileController@remove_contact']);
    Route::get('{userId}', ['as' => 'profile_public', 'uses' => 'ProfileController@public_profile'])->where('userId', '[0-9]+');
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
    Route::get('board/edit/{officer_id}', ['as' => 'about_board_edit', 'uses' => 'AboutController@boardEdit']);
    Route::post('board/edit/{officer_id}', ['as' => 'about_board_edit_post', 'uses' => 'AboutController@postBoardEdit']);
    Route::get('board/remove/{officer_id}', ['as' => 'about_board_remove', 'uses' => 'AboutController@boardRemove']);

    Route::get('minutes', ['as' => 'about_minutes', 'uses' => 'AboutController@minutes']);
    Route::get('minutes/add', ['as' => 'about_minutes_add', 'uses' => 'AboutController@minutesAdd']);
    Route::post('minutes/add', ['as' => 'about_minutes_add_post', 'uses' => 'AboutController@postMinutesAdd']);
    Route::get('minutes/edit/{minute_id}', ['as' => 'about_minutes_edit', 'uses' => 'AboutController@minutesEdit']);
    Route::post('minutes/edit/{minute_id}', ['as' => 'about_minutes_edit_post', 'uses' => 'AboutController@postMinutesEdit']);
    Route::get('minutes/download/{minute_id}', ['as' => 'about_minutes_download', 'uses' => 'AboutController@minutesDownload']);
    Route::any('minutes/remove/{minute_id}', ['as' => 'about_minutes_remove', 'uses' => 'AboutController@minutesRemove']);
    Route::get('links', ['as' => 'about_links', 'uses' => 'AboutController@links']);
    Route::get('links/edit', ['as' => 'about_links_edit', 'uses' => 'AboutController@linksEdit']);
    Route::post('links/edit', ['as' => 'about_links_edit_post', 'uses' => 'AboutController@postLinksEdit']);
});

Route::group(['prefix' => 'volunteer'], function () {
    Route::get('/', ['as' => 'volunteer', 'uses' => 'VolunteerController@volunteer']);
    Route::get('about', ['as' => 'volunteer_about', 'uses' => 'VolunteerController@about']);
    Route::any('about/edit', ['as' => 'volunteer_about_edit', 'uses' => 'VolunteerController@about_edit']);
    Route::any('signup', ['as' => 'volunteer_signup', 'uses' => 'VolunteerController@signup']);
    Route::get('show', ['as' => 'volunteer_show', 'uses' => 'VolunteerController@show']);
    Route::any('show/add', ['as' => 'volunteer_show_add', 'uses' => 'VolunteerController@show_add']);
    Route::any('show/edit/{eventId}', ['as' => 'volunteer_show_edit', 'uses' => 'VolunteerController@show_edit']);
    Route::get('show/members/{eventId}', ['as' => 'volunteer_show_members', 'uses' => 'VolunteerController@show_members']);
    Route::get('show/members/export/{eventId}', ['as' => 'volunteer_show_members_export', 'uses' => 'VolunteerController@show_members_export']);
    Route::any('show/signup/{eventId}', ['as' => 'volunteer_show_signup', 'uses' => 'VolunteerController@show_signup']);
    Route::get('list', ['as' => 'volunteer_list', 'uses' => 'VolunteerController@pool']);
    Route::get('list/download', ['as' => 'volunteer_list_download', 'uses' => 'VolunteerController@pool_download']);
});

Route::group(['prefix' => 'youth'], function () {
    Route::get('/', ['as' => 'youth', 'uses' => 'YouthController@youth']);
    Route::get('about', ['as' => 'youth_about', 'uses' => 'YouthController@about']);
    Route::any('about/edit', ['as' => 'youth_about_edit', 'uses' => 'YouthController@about_edit']);
    Route::get('yuc', ['as' => 'youth_yuc', 'uses' => 'YouthController@yuc']);
    Route::get('yuc/parents', ['as' => 'yuc_parents', 'uses' => 'YouthController@yuc_parents']);
    Route::get('yuc/coaches', ['as' => 'yuc_coaches', 'uses' => 'YouthController@yuc_coaches']);
    Route::any('yuc/edit', ['as' => 'youth_yuc_edit', 'uses' => 'YouthController@yuc_edit']);
    Route::any('yuc/parents/edit', ['as' => 'yuc_parents_edit', 'uses' => 'YouthController@yuc_parents_edit']);
    Route::any('yuc/coaches/edit', ['as' => 'yuc_coaches_edit', 'uses' => 'YouthController@yuc_coaches_edit']);
    Route::get('ycc', ['as' => 'youth_ycc', 'uses' => 'YouthController@ycc']);
    Route::get('leagues', ['as' => 'youth_leagues', 'uses' => 'YouthController@leagues']);
    Route::get('tournaments', ['as' => 'youth_tournaments', 'uses' => 'YouthController@tournaments']);
    Route::any('tournaments/add', ['as' => 'youth_tournaments_add', 'uses' => 'YouthController@tournaments_add']);
    Route::get('clinics', ['as' => 'youth_clinics', 'uses' => 'YouthController@clinics']);
    Route::any('clinics/add', ['as' => 'youth_clinics_add', 'uses' => 'YouthController@clinic_add']);
    Route::get('clinic/{name}', ['as' => 'youth_clinic', 'uses' => 'YouthController@clinic']);
    Route::any('clinic/{name}/edit', ['as' => 'youth_clinics_edit', 'uses' => 'YouthController@clinic_edit']);
    Route::get('coaching/requirements', ['as' => 'youth_coaching_requirements', 'uses' => 'YouthController@coaching_requirements']);
    Route::get('coaching', ['as' => 'youth_coaching', 'uses' => 'YouthController@coaching']);
    Route::any('coaching/edit', ['as' => 'youth_coaching_edit', 'uses' => 'YouthController@coaching_edit']);
});

Route::group(['prefix' => 'leagues'], function () {
    Route::get('/', ['as' => 'leagues', 'uses' => 'LeaguesController@leagues']);
    Route::get('winter', ['as' => 'leagues_winter', 'uses' => 'LeaguesController@leagues']);
    Route::get('spring', ['as' => 'leagues_spring', 'uses' => 'LeaguesController@leagues']);
    Route::get('summer', ['as' => 'leagues_summer', 'uses' => 'LeaguesController@leagues']);
    Route::get('fall', ['as' => 'leagues_fall', 'uses' => 'LeaguesController@leagues']);
    Route::get('team_players', ['as' => 'league_team_players', 'uses' => 'LeaguesController@team_players']);
    Route::get('team_record', ['as' => 'league_team_record', 'uses' => 'LeaguesController@team_record']);
    Route::get('{slug}', ['as' => 'league', 'uses' => 'LeaguesController@league']);
    Route::get('{slug}/teams', ['as' => 'league_teams', 'uses' => 'LeaguesController@teams']);
    Route::any('{slug}/team/add', ['as' => 'league_team_add', 'uses' => 'LeaguesController@team_add']);
    Route::any('{slug}/team/{team_id}/edit', ['as' => 'league_team_edit', 'uses' => 'LeaguesController@team_edit']);
    Route::get('{slug}/team/{team_id}/remove', ['as' => 'league_team_remove', 'uses' => 'LeaguesController@team_remove']);
    Route::get('{slug}/schedule', ['as' => 'league_schedule', 'uses' => 'LeaguesController@schedule']);
    Route::any('{slug}/schedule/edit/{game_id}', ['as' => 'league_schedule_edit', 'uses' => 'LeaguesController@schedule_edit']);
    Route::any('{slug}/schedule/remove/{game_id}', ['as' => 'league_schedule_remove', 'uses' => 'LeaguesController@schedule_remove']);
    Route::any('{slug}/schedule/add', ['as' => 'league_schedule_add', 'uses' => 'LeaguesController@schedule_add']);
    Route::any('{slug}/schedule/generate', ['as' => 'league_schedule_generate', 'uses' => 'LeaguesController@schedule_generate']);
    Route::get('{slug}/shirts', ['as' => 'league_shirts', 'uses' => 'LeaguesController@shirts']);
    Route::get('{slug}/shirts/download', ['as' => 'league_shirts_download', 'uses' => 'LeaguesController@shirts_download']);
    Route::get('{slug}/emergency', ['as' => 'league_emergency', 'uses' => 'LeaguesController@emergency']);
    Route::get('{slug}/emergency/download', ['as' => 'league_emergency_download', 'uses' => 'LeaguesController@emergency_download']);
    Route::get('{slug}/requests', ['as' => 'league_requests', 'uses' => 'LeaguesController@requests']);
    Route::get('{slug}/requests/accept/{memberId?}', ['as' => 'league_requests_accept', 'uses' => 'LeaguesController@requests_accept']);
    Route::get('{slug}/players', ['as' => 'league_players', 'uses' => 'LeaguesController@players']);
    Route::get('{slug}/players/download', ['as' => 'league_players_download', 'uses' => 'LeaguesController@players_download']);
    Route::get('{slug}/status/{all}/download', ['as' => 'league_status_download', 'uses' => 'LeaguesController@status_download']);
    Route::get('{slug}/status/{all?}', ['as' => 'league_status', 'uses' => 'LeaguesController@status']);
    Route::post('{slug}/status/toggle', ['as' => 'league_status_toggle', 'uses' => 'LeaguesController@status_toggle']);
    Route::match(['get', 'post'], '{slug}/manage', ['as' => 'league_manage', 'uses' => 'LeaguesController@manage']);
    Route::get('{slug}/waitlist', ['as' => 'league_waitlist', 'uses' => 'LeaguesController@waitlist']);
    Route::get('{slug}/waitlist/download', ['as' => 'league_waitlist_download', 'uses' => 'LeaguesController@waitlist_download']);
    Route::get('{slug}/waitlist/accept/{member_id}', ['as' => 'league_waitlist_accept', 'uses' => 'LeaguesController@waitlist_accept']);
    Route::any('{slug}/register/success', ['as' => 'league_success', 'uses' => 'LeaguesController@success']);
    Route::any('{slug}/register/{state?}', ['as' => 'league_register', 'uses' => 'LeaguesController@register']);
    Route::any('{slug}/email', ['as' => 'league_email', 'uses' => 'LeaguesController@email']);
    Route::any('add/{season}', ['as' => 'league_add', 'uses' => 'LeaguesController@add']);
    Route::any('{slug}/archive', ['as' => 'league_archive', 'uses' => 'LeaguesController@archive']);
    Route::get('{slug}/coaches', ['as' => 'league_coaches', 'uses' => 'LeaguesController@coaches']);
    Route::any('{slug}/coaches/{memberId}/edit', ['as' => 'league_coaches_edit', 'uses' => 'LeaguesController@coaches_edit']);
    Route::get('{slug}/coaches/download', ['as' => 'league_coaches_download', 'uses' => 'LeaguesController@coaches_download']);
    Route::any('{slug}/coaches/email', ['as' => 'league_coaches_email', 'uses' => 'LeaguesController@coaches_email']);
    Route::any('{slug}/edit/{type}', ['as' => 'league_edit', 'uses' => 'LeaguesController@league_edit']);
    Route::get('{season}/{slug}', ['as' => 'leagues_old', 'uses' => 'LeaguesController@league_old'])
        ->where('season', '(winter|spring|summer|fall)');
});

Route::group(['prefix' => 'around'], function () {
    Route::get('/', ['as' => 'around', 'uses' => 'AroundController@around']);
    Route::get('pickups', ['as' => 'around_pickups', 'uses' => 'AroundController@pickups']);
    Route::get('pickups/add', ['as' => 'around_pickups_add', 'uses' => 'AroundController@pickupsAdd']);
    Route::post('pickups/add', ['as' => 'around_pickups_add_post', 'uses' => 'AroundController@postPickupsAdd']);
    Route::get('pickups/{pickup_id}/edit', ['as' => 'around_pickups_edit', 'uses' => 'AroundController@pickupsEdit']);
    Route::post('pickups/{pickup_id}/edit', ['as' => 'around_pickups_edit_post', 'uses' => 'AroundController@postPickupsEdit']);
    Route::get('pickups/{pickup_id}/remove', ['as' => 'around_pickups_remove', 'uses' => 'AroundController@pickupsRemove']);
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
    Route::any('/{name}/{year}/admin', ['as' => 'tournament_admin', 'uses' => 'TournamentController@admin']);
    Route::any('/{name}/{year}/bid', ['as' => 'tournament_bid', 'uses' => 'TournamentController@bid']);
    Route::any('/{name}/{year}/bid/edit', ['as' => 'tournament_bid_edit', 'uses' => 'TournamentController@bid_edit']);
    Route::any('/{name}/{year}/teams/add', ['as' => 'tournament_teams_add', 'uses' => 'TournamentController@teams_add']);
    Route::any('/teams/{teamId}/edit', ['as' => 'tournament_teams_edit', 'uses' => 'TournamentController@teams_edit']);
    Route::get('/teams/{teamId}/remove', ['as' => 'tournament_teams_remove', 'uses' => 'TournamentController@teams_remove']);
    Route::any('/{name}/{year}/teams/{division?}', ['as' => 'tournament_teams', 'uses' => 'TournamentController@teams']);
    Route::any('/{name}/{year}/schedule', ['as' => 'tournament_schedule', 'uses' => 'TournamentController@schedule']);
    Route::any('/{name}/{year}/location', ['as' => 'tournament_location', 'uses' => 'TournamentController@location']);
    Route::any('/{name}/{year}/contact', ['as' => 'tournament_contact', 'uses' => 'TournamentController@contact']);
    Route::any('/description/{tournament_id}/edit', ['as' => 'tournament_description_edit', 'uses' => 'TournamentController@description_edit']);
    Route::any('/{name}/{year}/feed/add', ['as' => 'tournament_feed_add', 'uses' => 'TournamentController@feed_add']);
    Route::any('/feed/{feed_id}/edit', ['as' => 'tournament_feed_edit', 'uses' => 'TournamentController@feed_edit']);
    Route::get('/feed/{feed_id}/remove', ['as' => 'tournament_feed_remove', 'uses' => 'TournamentController@feed_remove']);
    Route::any('/schedule/{tournament_id}/edit', ['as' => 'tournament_schedule_edit', 'uses' => 'TournamentController@schedule_edit']);
    Route::any('/{name}/{year}/contact/add', ['as' => 'tournament_contact_add', 'uses' => 'TournamentController@contact_add']);
    Route::get('/contact/{memberId}/remove', ['as' => 'tournament_contact_remove', 'uses' => 'TournamentController@contact_remove']);
    Route::get('/contact/{memberId}/{direction}', ['as' => 'tournament_contact_order', 'uses' => 'TournamentController@contact_order']);
    Route::get('/nationals/2014/fans', ['as' => 'tournament_2014_nationals_fans', 'uses' => 'TournamentController@nationals_2014_fans']);
    Route::any('/{name}/{year}/location/add', ['as' => 'tournament_location_add', 'uses' => 'TournamentController@location_add']);
    Route::any('/location/{locationId}/edit', ['as' => 'tournament_location_edit', 'uses' => 'TournamentController@location_edit']);
    Route::get('/location/{locationId}/remove', ['as' => 'tournament_location_remove', 'uses' => 'TournamentController@location_remove']);
    Route::any('/{name}/{year}/location/map/edit', ['as' => 'tournament_location_map_edit', 'uses' => 'TournamentController@location_map_edit']);
    Route::any('/{name}/{year}/payment', ['as' => 'tournament_payment', 'uses' => 'TournamentController@payment']);
    Route::get('/scinny/2014/masters', ['as' => 'tournament_masters_2014', 'uses' => 'TournamentController@masters_2014']);
    Route::get('/scinny/2015/masters', ['as' => 'tournament_masters_2015', 'uses' => 'TournamentController@masters_2015']);
});

Route::group(['prefix' => 'typeahead'], function () {
    Route::get('users/{filter?}', ['as' => 'typeahead_users', 'uses' => 'TypeaheadController@users']);
    Route::get('members/{league_id?}', ['as' => 'typeahead_members', 'uses' => 'TypeaheadController@members']);
});

Route::group(['prefix' => 'manage'], function () {
    Route::get('/', ['as' => 'manage', 'uses' => 'ManageController@manage']);
    Route::get('unpaid', ['as' => 'manage_unpaid', 'uses' => 'ManageController@unpaid']);
    Route::get('duplicates', ['as' => 'manage_duplicates', 'uses' => 'ManageController@duplicates']);
    Route::post('duplicates', ['as' => 'manage_duplicates_post', 'uses' => 'ManageController@postDuplicates']);
    Route::get('league_players', ['as' => 'manage_league_players', 'uses' => 'ManageController@leaguePlayers']);
    Route::post('league_players', ['as' => 'manage_league_players_handle', 'uses' => 'ManageController@postLeaguePlayers']);
    Route::get('load_league_teams', ['as' => 'manage_load_league_teams', 'uses' => 'ManageController@load_league_teams']);
    Route::any('users', ['as' => 'manage_users', 'uses' => 'ManageController@users']);
    Route::post('users_detail', ['as' => 'manage_users_detail', 'uses' => 'ManageController@users_detail']);
    Route::get('impersonate/{user_id}', ['as' => 'manage_impersonate', 'uses' => 'ManageController@impersonate']);
    Route::get('forms', ['as' => 'manage_forms', 'uses' => 'ManageController@forms']);
    Route::get('forms/add', ['as' => 'manage_forms_add', 'uses' => 'ManageController@formsAdd']);
    Route::post('forms/add', ['as' => 'manage_forms_add_post', 'uses' => 'ManageController@postFormsAdd']);
    Route::get('forms/{slug}/edit', ['as' => 'manage_forms_edit', 'uses' => 'ManageController@formsEdit']);
    Route::post('forms/{slug}/edit', ['as' => 'manage_forms_edit_post', 'uses' => 'ManageController@postFormsEdit']);
    Route::get('forms/{slug}/remove', ['as' => 'manage_forms_remove', 'uses' => 'ManageController@formsRemove']);
});
