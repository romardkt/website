<?php

namespace Cupa\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         \Cupa\Tournament::class => \Cupa\Policies\TournamentPolicy::class,
         \Cupa\League::class => \Cupa\Policies\LeaguePolicy::class,
         \Cupa\LeagueTeam::class => \Cupa\Policies\LeagueTeamPolicy::class,
         \Cupa\Team::class => \Cupa\Policies\TeamPolicy::class,
         \Cupa\VolunteerEvent::class => \Cupa\Policies\VolunteerEventPolicy::class,
         \Cupa\Officer::class => \Cupa\Policies\OfficerPolicy::class,
         \Cupa\Pickup::class => \Cupa\Policies\PickupPolicy::class,
         \Cupa\User::class => \Cupa\Policies\UserPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param \Illuminate\Contracts\Auth\Access\Gate $gate
     */
    public function boot(GateContract $gate)
    {
        parent::registerPolicies($gate);

        $roles = [
            'admin' => ['admin'],
            'manager' => ['admin', 'manager'],
            'reporter' => ['admin', 'manager', 'reporter'],
            'editor' => ['admin', 'manager', 'editor'],
            'volunteer' => ['admin', 'manager', 'volunteer'],
            'hoy-scholarship' => ['admin', 'manager', 'hoy'],
        ];

        foreach ($roles as $name => $perms) {
            $gate->define('is-'.$name, function ($user) use ($perms) {
                $roles = $user->roles();
                foreach ($roles->get() as $role) {
                    if (in_array($role->role->name, $perms)) {
                        return true;
                    }
                }

                return false;
            });
        }

        $gate->define('is-user', function($user) {
            return true;
        });
    }
}
