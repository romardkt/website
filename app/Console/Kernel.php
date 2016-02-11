<?php

namespace Cupa\Console;

use Illuminate\Support\Facades\Artisan;
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
        \Cupa\Console\Commands\Dump::class,
        \Cupa\Console\Commands\SendEmails::class,
        \Cupa\Console\Commands\ImportUploads::class,
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
    }
}
