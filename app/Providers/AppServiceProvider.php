<?php

namespace Cupa\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/../functions.php';
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      $this->app->alias('bugsnag.logger', \Illuminate\Contracts\Logging\Log::class);
      $this->app->alias('bugsnag.logger', \Psr\Log\LoggerInterface::class);
    }
}
