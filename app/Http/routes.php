<?php

Route::get('/', ['as' => 'home', 'uses' => 'PageController@home']);
Route::post('location/add', ['as' => 'location_add', 'uses' => 'PageController@locationAdd', 'middleware' => 'auth']);
Route::get('contact', ['as' => 'contact', 'uses' => 'PageController@contact']);
Route::post('contact', ['as' => 'contact_handle', 'uses' => 'PageController@postContact']);
Route::any('waiver/{year}/download/{type?}', ['as' => 'waiver_download', 'uses' => 'PageController@waiver_download']);
Route::any('waiver/{year}/{user_id?}', ['as' => 'waiver', 'uses' => 'PageController@waiver', 'middleware' => 'auth']);
Route::any('paypal/success/{id}', ['as' => 'paypal_success', 'uses' => 'PageController@paypal_success']);
Route::any('paypal/fail/{id}', ['as' => 'paypal_fail', 'uses' => 'PageController@paypal_fail']);
Route::any('paypal/{id}/{type}/{user_id?}/{team_id?}', ['as' => 'paypal', 'uses' => 'PageController@paypal']);

Route::get('/scholarship/hoy', ['as' => 'about_scholarship_hoy', 'uses' => 'ScholarshipController@hoy']);
Route::get('/scholarship/hoy', ['as' => 'scholarship_hoy', 'uses' => 'ScholarshipController@hoy']);
Route::match(['get', 'post'], '/scholarship/hoy/edit', ['as' => 'scholarship_hoy_edit', 'uses' => 'ScholarshipController@hoy_edit', 'before' => 'hoy_scholarship']);
Route::any('/scholarship/hoy/submit', ['as' => 'scholarship_hoy_submit', 'uses' => 'ScholarshipController@hoy_submit']);
Route::get('/scholarship/hoy/manage', ['as' => 'scholarship_hoy_manage', 'uses' => 'ScholarshipController@hoy_manage', 'before' => 'hoy_scholarship']);
Route::any('/scholarship/hoy/manage/{scholarship_id}', ['as' => 'scholarship_hoy_manage_edit', 'uses' => 'ScholarshipController@hoy_manage_edit', 'before' => 'hoy_scholarship']);
Route::any('/scholarship/hoy/manage/{scholarship_id}/delete', ['as' => 'scholarship_hoy_manage_delete', 'uses' => 'ScholarshipController@hoy_manage_delete', 'before' => 'hoy_scolarship']);

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
    Route::get('add', ['as' => 'posts_add', 'uses' => 'PostController@add', 'middleware' => 'role:reporter']);
    Route::post('add', ['as' => 'posts_add_post', 'uses' => 'PostController@postAdd', 'middleware' => 'role:reporter']);
    Route::get('{slug}', ['as' => 'post_view', 'uses' => 'PostController@view']);
    Route::get('{slug}/edit', ['as' => 'post_edit', 'uses' => 'PostController@edit', 'middleware' => 'role:reporter']);
    Route::post('{slug}/edit', ['as' => 'post_edit_post', 'uses' => 'PostController@postEdit', 'middleware' => 'role:reporter']);
});

Route::group(['prefix' => 'profile'], function () {
    Route::any('/', ['as' => 'profile', 'uses' => 'ProfileController@profile', 'before' => 'auth']);
    Route::any('password', ['as' => 'profile_password', 'uses' => 'ProfileController@password', 'before' => 'auth']);
    Route::get('minors', ['as' => 'profile_minors', 'uses' => 'ProfileController@minors', 'before' => 'auth']);
    Route::any('minors/add', ['as' => 'profile_minor_add', 'uses' => 'ProfileController@minor_add', 'before' => 'auth']);
    Route::any('minors/{minor_id}/edit', ['as' => 'profile_minor_edit', 'uses' => 'ProfileController@minor_edit', 'before' => 'auth']);
    Route::get('minors/{minor_id}/remove', ['as' => 'profile_minor_remove', 'uses' => 'ProfileController@minor_remove', 'before' => 'auth']);
    Route::get('leagues', ['as' => 'profile_leagues', 'uses' => 'ProfileController@leagues', 'before' => 'auth']);
    Route::get('teams', ['as' => 'profile_teams', 'uses' => 'ProfileController@teams', 'before' => 'auth']);
    Route::get('contacts', ['as' => 'profile_contacts', 'uses' => 'ProfileController@contacts', 'before' => 'auth']);
    Route::any('contacts/add', ['as' => 'profile_contact_add', 'uses' => 'ProfileController@contact_add', 'before' => 'auth']);
    Route::any('contacts/{minor_id}/edit', ['as' => 'profile_contact_edit', 'uses' => 'ProfileController@contact_edit', 'before' => 'auth']);
    Route::get('contacts/{minor_id}/remove', ['as' => 'profile_contact_remove', 'uses' => 'ProfileController@contact_remove', 'before' => 'auth']);
    Route::post('add/contact', ['as' => 'profile_add_contact', 'uses' => 'ProfileController@add_contact', 'before' => 'auth']);
    Route::post('remove/contact', ['as' => 'profile_remove_contact', 'uses' => 'ProfileController@remove_contact', 'before' => 'auth']);
    Route::get('{userId}', ['as' => 'profile_public', 'uses' => 'ProfileController@public_profile'])->where('userId', '[0-9]+');
});

Route::group(['prefix' => 'form'], function () {
    Route::get('{slug}', ['as' => 'form_view', 'uses' => 'FormController@view']);
});

Route::group(['prefix' => 'about'], function () {
    Route::get('/', ['as' => 'about', 'uses' => 'AboutController@about']);

    Route::get('mission', ['as' => 'about_mission', 'uses' => 'AboutController@mission']);
    Route::get('mission/edit', ['as' => 'about_mission_edit', 'uses' => 'AboutController@missionEdit', 'middleware' => 'role:editor']);
    Route::post('mission/edit', ['as' => 'about_mission_edit_post', 'uses' => 'AboutController@postMissionEdit', 'middleware' => 'role:editor']);

    Route::get('board', ['as' => 'about_board', 'uses' => 'AboutController@board']);
    Route::get('board/add', ['as' => 'about_board_add', 'uses' => 'AboutController@boardAdd', 'middleware' => 'role:manager']);
    Route::post('board/add', ['as' => 'about_board_add_post', 'uses' => 'AboutController@postBoardAdd', 'middleware' => 'role:manager']);
    Route::get('board/edit/{officer_id}', ['as' => 'about_board_edit', 'uses' => 'AboutController@boardEdit', 'middleware' => 'role:manager']);
    Route::post('board/edit/{officer_id}', ['as' => 'about_board_edit_post', 'uses' => 'AboutController@postBoardEdit', 'middleware' => 'role:manager']);
    Route::get('board/remove/{officer_id}', ['as' => 'about_board_remove', 'uses' => 'AboutController@boardRemove', 'middleware' => 'role:manager']);

    Route::get('minutes', ['as' => 'about_minutes', 'uses' => 'AboutController@minutes']);
    Route::get('minutes/add', ['as' => 'about_minutes_add', 'uses' => 'AboutController@minutesAdd', 'middleware' => 'role:editor']);
    Route::post('minutes/add', ['as' => 'about_minutes_add_post', 'uses' => 'AboutController@postMinutesAdd', 'middleware' => 'role:editor']);
    Route::get('minutes/edit/{minute_id}', ['as' => 'about_minutes_edit', 'uses' => 'AboutController@minutesEdit', 'middleware' => 'role:editor']);
    Route::post('minutes/edit/{minute_id}', ['as' => 'about_minutes_edit_post', 'uses' => 'AboutController@postMinutesEdit', 'middleware' => 'role:editor']);
    Route::get('minutes/download/{minute_id}', ['as' => 'about_minutes_download', 'uses' => 'AboutController@minutesDownload']);
    Route::any('minutes/remove/{minute_id}', ['as' => 'about_minutes_remove', 'uses' => 'AboutController@minutesRemove', 'middleware' => 'role:editor']);
    Route::get('links', ['as' => 'about_links', 'uses' => 'AboutController@links']);
    Route::get('links/edit', ['as' => 'about_links_edit', 'uses' => 'AboutController@linksEdit', 'middleware' => 'role:manager']);
    Route::post('links/edit', ['as' => 'about_links_edit_post', 'uses' => 'AboutController@postLinksEdit', 'middleware' => 'role:manager']);
});

Route::group(['prefix' => 'volunteer'], function () {
    Route::get('/', ['as' => 'volunteer', 'uses' => 'VolunteerController@volunteer']);
    Route::get('about', ['as' => 'volunteer_about', 'uses' => 'VolunteerController@about']);
    Route::any('about/edit', ['as' => 'volunteer_about_edit', 'uses' => 'VolunteerController@about_edit', 'before' => 'volunteer|editor']);
    Route::any('signup', ['as' => 'volunteer_signup', 'uses' => 'VolunteerController@signup']);
    Route::get('show', ['as' => 'volunteer_show', 'uses' => 'VolunteerController@show']);
    Route::any('show/add', ['as' => 'volunteer_show_add', 'uses' => 'VolunteerController@show_add', 'before' => 'volunteer']);
    Route::any('show/edit/{eventId}', ['as' => 'volunteer_show_edit', 'uses' => 'VolunteerController@show_edit', 'before' => 'volunteer']);
    Route::get('show/members/{eventId}', ['as' => 'volunteer_show_members', 'uses' => 'VolunteerController@show_members', 'before' => 'volunteer']);
    Route::get('show/members/export/{eventId}', ['as' => 'volunteer_show_members_export', 'uses' => 'VolunteerController@show_members_export', 'before' => 'volunteer']);
    Route::any('show/signup/{eventId}', ['as' => 'volunteer_show_signup', 'uses' => 'VolunteerController@show_signup', 'before' => 'auth']);
    Route::get('list', ['as' => 'volunteer_list', 'uses' => 'VolunteerController@pool', 'before' => 'volunteer']);
    Route::get('list/download', ['as' => 'volunteer_list_download', 'uses' => 'VolunteerController@pool_download', 'before' => 'volunteer']);
});

Route::group(['prefix' => 'youth'], function () {
    Route::get('/', ['as' => 'youth', 'uses' => 'YouthController@youth']);
    Route::get('about', ['as' => 'youth_about', 'uses' => 'YouthController@about']);
    Route::any('about/edit', ['as' => 'youth_about_edit', 'uses' => 'YouthController@about_edit', 'before' => 'editor']);
    Route::get('yuc', ['as' => 'youth_yuc', 'uses' => 'YouthController@yuc']);
    Route::get('yuc/parents', ['as' => 'yuc_parents', 'uses' => 'YouthController@yuc_parents']);
    Route::get('yuc/coaches', ['as' => 'yuc_coaches', 'uses' => 'YouthController@yuc_coaches']);
    Route::any('yuc/edit', ['as' => 'youth_yuc_edit', 'uses' => 'YouthController@yuc_edit', 'before' => 'editor']);
    Route::any('yuc/parents/edit', ['as' => 'yuc_parents_edit', 'uses' => 'YouthController@yuc_parents_edit', 'before' => 'editor']);
    Route::any('yuc/coaches/edit', ['as' => 'yuc_coaches_edit', 'uses' => 'YouthController@yuc_coaches_edit', 'before' => 'editor']);
    Route::get('ycc', ['as' => 'youth_ycc', 'uses' => 'YouthController@ycc']);
    Route::get('leagues', ['as' => 'youth_leagues', 'uses' => 'YouthController@leagues']);
    Route::get('tournaments', ['as' => 'youth_tournaments', 'uses' => 'YouthController@tournaments']);
    Route::any('tournaments/add', ['as' => 'youth_tournaments_add', 'uses' => 'YouthController@tournaments_add', 'before' => 'manager']);
    Route::get('clinics', ['as' => 'youth_clinics', 'uses' => 'YouthController@clinics']);
    Route::any('clinics/add', ['as' => 'youth_clinics_add', 'uses' => 'YouthController@clinic_add']);
    Route::get('clinic/{name}', ['as' => 'youth_clinic', 'uses' => 'YouthController@clinic']);
    Route::any('clinic/{name}/edit', ['as' => 'youth_clinics_edit', 'uses' => 'YouthController@clinic_edit', 'before' => 'editor']);
    Route::get('coaching/requirements', ['as' => 'youth_coaching_requirements', 'uses' => 'YouthController@coaching_requirements']);
    Route::get('coaching', ['as' => 'youth_coaching', 'uses' => 'YouthController@coaching']);
    Route::any('coaching/edit', ['as' => 'youth_coaching_edit', 'uses' => 'YouthController@coaching_edit', 'before' => 'editor']);
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
    Route::any('{slug}/team/add', ['as' => 'league_team_add', 'uses' => 'LeaguesController@team_add', 'before' => 'manager']);
    Route::any('{slug}/team/{team_id}/edit', ['as' => 'league_team_edit', 'uses' => 'LeaguesController@team_edit', 'before' => 'manager']);
    Route::get('{slug}/team/{team_id}/remove', ['as' => 'league_team_remove', 'uses' => 'LeaguesController@team_remove', 'before' => 'manager']);
    Route::get('{slug}/schedule', ['as' => 'league_schedule', 'uses' => 'LeaguesController@schedule']);
    Route::any('{slug}/schedule/edit/{game_id}', ['as' => 'league_schedule_edit', 'uses' => 'LeaguesController@schedule_edit', 'before' => 'manager']);
    Route::any('{slug}/schedule/remove/{game_id}', ['as' => 'league_schedule_remove', 'uses' => 'LeaguesController@schedule_remove', 'before' => 'manager']);
    Route::any('{slug}/schedule/add', ['as' => 'league_schedule_add', 'uses' => 'LeaguesController@schedule_add', 'before' => 'manager']);
    Route::any('{slug}/schedule/generate', ['as' => 'league_schedule_generate', 'uses' => 'LeaguesController@schedule_generate', 'before' => 'manager']);
    Route::get('{slug}/shirts', ['as' => 'league_shirts', 'uses' => 'LeaguesController@shirts', 'before' => 'manager']);
    Route::get('{slug}/shirts/download', ['as' => 'league_shirts_download', 'uses' => 'LeaguesController@shirts_download', 'before' => 'manager']);
    Route::get('{slug}/emergency', ['as' => 'league_emergency', 'uses' => 'LeaguesController@emergency', 'before' => 'manager']);
    Route::get('{slug}/emergency/download', ['as' => 'league_emergency_download', 'uses' => 'LeaguesController@emergency_download', 'before' => 'manager']);
    Route::get('{slug}/requests', ['as' => 'league_requests', 'uses' => 'LeaguesController@requests', 'before' => 'manager']);
    Route::get('{slug}/requests/accept/{memberId?}', ['as' => 'league_requests_accept', 'uses' => 'LeaguesController@requests_accept', 'before' => 'manager']);
    Route::get('{slug}/players', ['as' => 'league_players', 'uses' => 'LeaguesController@players', 'before' => 'manager']);
    Route::get('{slug}/players/download', ['as' => 'league_players_download', 'uses' => 'LeaguesController@players_download', 'before' => 'manager']);
    Route::get('{slug}/status/{all}/download', ['as' => 'league_status_download', 'uses' => 'LeaguesController@status_download', 'before' => 'manager']);
    Route::get('{slug}/status/{all?}', ['as' => 'league_status', 'uses' => 'LeaguesController@status', 'before' => 'manager']);
    Route::post('{slug}/status/toggle', ['as' => 'league_status_toggle', 'uses' => 'LeaguesController@status_toggle', 'before' => 'manager']);
    Route::match(['get', 'post'], '{slug}/manage', ['as' => 'league_manage', 'uses' => 'LeaguesController@manage', 'before' => 'manager']);
    Route::get('{slug}/waitlist', ['as' => 'league_waitlist', 'uses' => 'LeaguesController@waitlist', 'before' => 'manager']);
    Route::get('{slug}/waitlist/download', ['as' => 'league_waitlist_download', 'uses' => 'LeaguesController@waitlist_download', 'before' => 'manager']);
    Route::get('{slug}/waitlist/accept/{member_id}', ['as' => 'league_waitlist_accept', 'uses' => 'LeaguesController@waitlist_accept', 'before' => 'manager']);
    Route::any('{slug}/register/success', ['as' => 'league_success', 'uses' => 'LeaguesController@success', 'before' => 'auth']);
    Route::any('{slug}/register/{state?}', ['as' => 'league_register', 'uses' => 'LeaguesController@register', 'before' => 'auth']);
    Route::any('{slug}/email', ['as' => 'league_email', 'uses' => 'LeaguesController@email']);
    Route::any('add/{season}', ['as' => 'league_add', 'uses' => 'LeaguesController@add', 'before' => 'manager']);
    Route::any('{slug}/archive', ['as' => 'league_archive', 'uses' => 'LeaguesController@archive', 'before' => 'manager']);
    Route::get('{slug}/coaches', ['as' => 'league_coaches', 'uses' => 'LeaguesController@coaches', 'before' => 'coach']);
    Route::any('{slug}/coaches/{memberId}/edit', ['as' => 'league_coaches_edit', 'uses' => 'LeaguesController@coaches_edit', 'before' => 'coach']);
    Route::get('{slug}/coaches/download', ['as' => 'league_coaches_download', 'uses' => 'LeaguesController@coaches_download', 'before' => 'coach']);
    Route::any('{slug}/coaches/email', ['as' => 'league_coaches_email', 'uses' => 'LeaguesController@coaches_email', 'before' => 'manager']);
    Route::any('{slug}/edit/{type}', ['as' => 'league_edit', 'uses' => 'LeaguesController@league_edit', 'before' => 'manager']);
    Route::get('{season}/{slug}', ['as' => 'leagues_old', 'uses' => 'LeaguesController@league_old'])
        ->where('season', '(winter|spring|summer|fall)');
});

Route::group(['prefix' => 'around'], function () {
    Route::get('/', ['as' => 'around', 'uses' => 'AroundController@around']);
    Route::get('pickups', ['as' => 'around_pickups', 'uses' => 'AroundController@pickups']);
    Route::any('pickups/add', ['as' => 'around_pickups_add', 'uses' => 'AroundController@pickups_add', 'before' => 'editor']);
    Route::any('pickups/{pickup_id}/edit', ['as' => 'around_pickups_edit', 'uses' => 'AroundController@pickups_edit', 'before' => 'editor']);
    Route::get('pickups/{pickup_id}/remove', ['as' => 'around_pickups_remove', 'uses' => 'AroundController@pickups_remove', 'before' => 'editor']);
    Route::get('tournaments', ['as' => 'around_tournaments', 'uses' => 'AroundController@tournaments']);
    Route::any('tournaments/add', ['as' => 'around_tournaments_add', 'uses' => 'AroundController@tournaments_add', 'before' => 'manager']);
    Route::get('discgolf', ['as' => 'around_discgolf', 'uses' => 'AroundController@discgolf']);
    Route::get('fields', ['as' => 'around_fields', 'uses' => 'AroundController@fields']);
});

Route::group(['prefix' => 'teams'], function () {
    Route::get('/', ['as' => 'teams', 'uses' => 'TeamsController@teams']);
    Route::any('add', ['as' => 'teams_add', 'uses' => 'TeamsController@teams_add', 'before' => 'editor']);
    Route::any('edit', ['as' => 'teams_edit', 'uses' => 'TeamsController@teams_edit', 'before' => 'editor']);
    Route::get('{name}', ['as' => 'teams_show', 'uses' => 'TeamsController@show']);
    Route::any('{name}/edit', ['as' => 'teams_show_edit', 'uses' => 'TeamsController@show_edit', 'before' => 'editor']);
});

Route::group(['prefix' => 'tournament'], function () {
    Route::get('/{name}/{year?}', ['as' => 'tournament', 'uses' => 'TournamentController@tournament']);
    Route::any('/{name}/{year}/admin', ['as' => 'tournament_admin', 'uses' => 'TournamentController@admin', 'before' => 'manager']);
    Route::any('/{name}/{year}/bid', ['as' => 'tournament_bid', 'uses' => 'TournamentController@bid']);
    Route::any('/{name}/{year}/bid/edit', ['as' => 'tournament_bid_edit', 'uses' => 'TournamentController@bid_edit']);
    Route::any('/{name}/{year}/teams/add', ['as' => 'tournament_teams_add', 'uses' => 'TournamentController@teams_add', 'before' => 'manager']);
    Route::any('/teams/{teamId}/edit', ['as' => 'tournament_teams_edit', 'uses' => 'TournamentController@teams_edit', 'before' => 'manager']);
    Route::get('/teams/{teamId}/remove', ['as' => 'tournament_teams_remove', 'uses' => 'TournamentController@teams_remove', 'before' => 'manager']);
    Route::any('/{name}/{year}/teams/{division?}', ['as' => 'tournament_teams', 'uses' => 'TournamentController@teams']);
    Route::any('/{name}/{year}/schedule', ['as' => 'tournament_schedule', 'uses' => 'TournamentController@schedule']);
    Route::any('/{name}/{year}/location', ['as' => 'tournament_location', 'uses' => 'TournamentController@location']);
    Route::any('/{name}/{year}/contact', ['as' => 'tournament_contact', 'uses' => 'TournamentController@contact']);
    Route::any('/description/{tournament_id}/edit', ['as' => 'tournament_description_edit', 'uses' => 'TournamentController@description_edit', 'before' => 'manager']);
    Route::any('/{name}/{year}/feed/add', ['as' => 'tournament_feed_add', 'uses' => 'TournamentController@feed_add', 'before' => 'manager']);
    Route::any('/feed/{feed_id}/edit', ['as' => 'tournament_feed_edit', 'uses' => 'TournamentController@feed_edit', 'before' => 'manager']);
    Route::get('/feed/{feed_id}/remove', ['as' => 'tournament_feed_remove', 'uses' => 'TournamentController@feed_remove', 'before' => 'manager']);
    Route::any('/schedule/{tournament_id}/edit', ['as' => 'tournament_schedule_edit', 'uses' => 'TournamentController@schedule_edit', 'before' => 'manager']);
    Route::any('/{name}/{year}/contact/add', ['as' => 'tournament_contact_add', 'uses' => 'TournamentController@contact_add', 'before' => 'manager']);
    Route::get('/contact/{memberId}/remove', ['as' => 'tournament_contact_remove', 'uses' => 'TournamentController@contact_remove', 'before' => 'manager']);
    Route::get('/contact/{memberId}/{direction}', ['as' => 'tournament_contact_order', 'uses' => 'TournamentController@contact_order', 'before' => 'manager']);
    Route::get('/nationals/2014/fans', ['as' => 'tournament_2014_nationals_fans', 'uses' => 'TournamentController@nationals_2014_fans']);
    Route::any('/{name}/{year}/location/add', ['as' => 'tournament_location_add', 'uses' => 'TournamentController@location_add', 'before' => 'manager']);
    Route::any('/location/{locationId}/edit', ['as' => 'tournament_location_edit', 'uses' => 'TournamentController@location_edit', 'before' => 'manager']);
    Route::get('/location/{locationId}/remove', ['as' => 'tournament_location_remove', 'uses' => 'TournamentController@location_remove', 'before' => 'manager']);
    Route::any('/{name}/{year}/location/map/edit', ['as' => 'tournament_location_map_edit', 'uses' => 'TournamentController@location_map_edit', 'before' => 'manager']);
    Route::any('/{name}/{year}/payment', ['as' => 'tournament_payment', 'uses' => 'TournamentController@payment']);
    Route::get('/scinny/2014/masters', ['as' => 'tournament_masters_2014', 'uses' => 'TournamentController@masters_2014']);
    Route::get('/scinny/2015/masters', ['as' => 'tournament_masters_2015', 'uses' => 'TournamentController@masters_2015']);

});

Route::group(['prefix' => 'typeahead'], function () {
    Route::get('users/{filter?}', ['as' => 'typeahead_users', 'uses' => 'TypeaheadController@users', 'before' => 'auth']);
    Route::get('members/{league_id?}', ['as' => 'typeahead_members', 'uses' => 'TypeaheadController@members', 'before' => 'auth']);
});

Route::group(['prefix' => 'manage'], function () {
    Route::get('/', ['as' => 'manage', 'uses' => 'ManageController@manage', 'middleware' => 'role:manager']);
    Route::get('unpaid', ['as' => 'manage_unpaid', 'uses' => 'ManageController@unpaid', 'middleware' => 'role:manager']);
    Route::get('duplicates', ['as' => 'manage_duplicates', 'uses' => 'ManageController@duplicates', 'middleware' => 'role:manager']);
    Route::post('duplicates', ['as' => 'manage_duplicates_post', 'uses' => 'ManageController@postDuplicates', 'middleware' => 'role:manager']);
    Route::get('league_players', ['as' => 'manage_league_players', 'uses' => 'ManageController@leaguePlayers', 'middleware' => 'role:manager']);
    Route::post('league_players', ['as' => 'manage_league_players_handle', 'uses' => 'ManageController@postLeaguePlayers', 'middleware' => 'role:manager']);
    Route::get('load_league_teams', ['as' => 'manage_load_league_teams', 'uses' => 'ManageController@load_league_teams', 'middleware' => 'role:manager']);
    Route::any('users', ['as' => 'manage_users', 'uses' => 'ManageController@users', 'middleware' => 'role:manager']);
    Route::post('users_detail', ['as' => 'manage_users_detail', 'uses' => 'ManageController@users_detail', 'middleware' => 'role:manager']);
    Route::get('impersonate/{user_id}', ['as' => 'manage_impersonate', 'uses' => 'ManageController@impersonate', 'middleware' => 'role:admin']);
    Route::get('forms', ['as' => 'manage_forms', 'uses' => 'ManageController@forms', 'middleware' => 'role:admin']);
    Route::get('forms/add', ['as' => 'manage_forms_add', 'uses' => 'ManageController@formsAdd', 'middleware' => 'role:admin']);
    Route::post('forms/add', ['as' => 'manage_forms_add_post', 'uses' => 'ManageController@postFormsAdd', 'middleware' => 'role:admin']);
    Route::get('forms/{slug}/edit', ['as' => 'manage_forms_edit', 'uses' => 'ManageController@formsEdit', 'middleware' => 'role:admin']);
    Route::post('forms/{slug}/edit', ['as' => 'manage_forms_edit_post', 'uses' => 'ManageController@postFormsEdit', 'middleware' => 'role:admin']);
    Route::get('forms/{slug}/remove', ['as' => 'manage_forms_remove', 'uses' => 'ManageController@formsRemove', 'middleware' => 'role:admin']);
});
