<?php

namespace Cupa\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Cupa\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function boot(Router $router)
    {
        Route::model('paypal_id', 'Cupa\Paypal');
        Route::model('scholarship_id', 'Cupa\Scholarship');
        Route::model('minute_id', 'Cupa\Minute');
        Route::model('officer_id', 'Cupa\Officer');
        Route::model('user_id', 'Cupa\User');
        Route::model('league_id', 'Cupa\League');
        Route::model('pickup_id', 'Cupa\Pickup');
        Route::model('event_id', 'Cupa\VolunteerEvent');
        Route::model('minor_id', 'Cupa\User');
        Route::model('contact_id', 'Cupa\UserContact');
        Route::model('tournament_id', 'Cupa\Tournament');
        Route::model('tournament_feed_id', 'Cupa\TournamentFeed');
        Route::model('tournament_team_id', 'Cupa\TournamentTeam');
        Route::model('tournament_member_id', 'Cupa\TournamentMember');
        Route::model('tournament_location_id', 'Cupa\TournamentLocation');
        Route::model('league_team_id', 'Cupa\LeagueTeam');
        Route::model('league_game_id', 'Cupa\LeagueGame');
        Route::model('league_member_id', 'Cupa\LeagueMember');

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
