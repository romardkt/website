<?php

namespace Cupa\Console;

use Artisan;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Cupa\Console\Commands\CopyFiles::class,
        \Cupa\Console\Commands\SendEmails::class,
        \Cupa\Console\Commands\RemoveInactive::class,
        \Cupa\Console\Commands\NotifyParentsOfMinorAccount::class,
        \Cupa\Console\Commands\CheckMinorAccounts::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            Artisan::call('cupa:emails');
        })->weekly()->mondays()->at('9:00');

        // this is scheduled to run 15min after the database backup
        // to minimize errors if something happens
        $schedule->call(function () {
            // due to league counts we have disabled this
            Artisan::call('cupa:remove-inactives');
            Artisan::call('cupa:check-minor-accounts');
            Artisan::call('cupa:notify-parents');
        })->daily()->at('8:15');
    }

    /**
     * Register the Closure based commands for the application.
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
