<?php

namespace Cupa\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Cupa\Models\Tournament::class => \Cupa\Policies\TournamentPolicy::class,
        \Cupa\Models\League::class => \Cupa\Policies\LeaguePolicy::class,
        \Cupa\Models\LeagueTeam::class => \Cupa\Policies\LeagueTeamPolicy::class,
        \Cupa\Models\Team::class => \Cupa\Policies\TeamPolicy::class,
        \Cupa\Models\VolunteerEvent::class => \Cupa\Policies\VolunteerEventPolicy::class,
        \Cupa\Models\Officer::class => \Cupa\Policies\OfficerPolicy::class,
        \Cupa\Models\Pickup::class => \Cupa\Policies\PickupPolicy::class,
        \Cupa\Models\User::class => \Cupa\Policies\UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        $roles = [
            'admin' => ['admin'],
            'manager' => ['admin', 'manager'],
            'reporter' => ['admin', 'manager', 'reporter'],
            'editor' => ['admin', 'manager', 'editor'],
            'volunteer' => ['admin', 'manager', 'volunteer'],
            'hoy-scholarship' => ['admin', 'manager', 'hoy'],
            'director' => ['admin', 'manager'],
        ];

        foreach ($roles as $name => $perms) {
            Gate::define('is-'.$name, function ($user) use ($perms, $name) {
                $roles = $user->roles();
                foreach ($roles->get() as $role) {
                    if (in_array($role->role->name, $perms)) {
                        return true;
                    }
                }

                // check to see if the user is a director
                if ($name == 'director') {
                    return $user->isDirector();
                }

                return false;
            });
        }

        Gate::define('is-user', function ($user) {
            return true;
        });
    }
}
