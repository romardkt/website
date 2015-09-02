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
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param \Illuminate\Contracts\Auth\Access\Gate $gate
     */
    public function boot(GateContract $gate)
    {
        parent::registerPolicies($gate);

        $gate->define('is-admin', function ($user) {
            $roles = $user->roles();
            if ($roles->count() > 0 && $roles->first()->role->name === 'admin') {
                return true;
            }

            return false;
        });

        $gate->define('is-manager', function ($user) {
            $roles = $user->roles();
            if ($roles->count() > 0 && in_array($roles->first()->role->name, ['admin', 'manager'])) {
                return true;
            }

            return false;
        });
    }
}
