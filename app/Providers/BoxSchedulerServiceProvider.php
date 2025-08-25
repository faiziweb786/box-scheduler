<?php

namespace App\Providers;

use App\Console\Commands\BoxScheduler;
use Illuminate\Support\ServiceProvider;

class BoxSchedulerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                BoxScheduler::class,
            ]);
        }
    }
}
