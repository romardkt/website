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
        'Cupa\Model' => 'Cupa\Policies\ModelPolicy',
         \Cupa\Tournament::class => \Cupa\Policies\TournamentPolicy::class,
         \Cupa\League::class => \Cupa\Policies\LeaguePolicy::class,
         \Cupa\Team::class => \Cupa\Policies\TeamPolicy::class,
         \Cupa\Volunteer::class => \Cupa\Policies\VolunteerPolicy::class,
         \Cupa\Officer::class => \Cupa\Policies\OfficerPolicy::class,
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
            'hoy-scholarship' => ['admin', 'manager', 'hoy'],
        ];

        foreach ($roles as $name => $perms) {
            $gate->define('is-'.$name, function ($user) use ($perms) {
                $roles = $user->roles();
                if ($roles->count() > 0 && in_array($roles->first()->role->name, $perms)) {
                    return true;
                }

                return false;
            });
        }
    }
}
