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
        parent::boot($router);

        $router->model('paypal', 'Cupa\Paypal');
        $router->model('scholarship', 'Cupa\Scholarship');
        $router->model('minute', 'Cupa\Minute');
        $router->model('officer', 'Cupa\Officer');
        $router->model('user', 'Cupa\User');
        $router->model('league', 'Cupa\League');
        $router->model('pickup', 'Cupa\Pickup');
        $router->model('event', 'Cupa\VolunteerEvent');
        $router->model('minor', 'Cupa\User');
        $router->model('contact', 'Cupa\UserContact');
        $router->model('tournament', 'Cupa\Tournament');
        $router->model('tournamentFeed', 'Cupa\TournamentFeed');
        $router->model('tournamentTeam', 'Cupa\TournamentTeam');
        $router->model('tournamentMember', 'Cupa\TournamentMember');
        $router->model('tournamentLocation', 'Cupa\TournamentLocation');
        $router->model('leagueTeam', 'Cupa\LeagueTeam');
        $router->model('leagueGame', 'Cupa\LeagueGame');
        $router->model('leagueMember', 'Cupa\LeagueMember');
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
