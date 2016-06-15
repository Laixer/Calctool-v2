<?php

namespace Calctool\Providers;

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
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('calctool', function()
        {
            return new \Calctool\Other\Calctool;
        });

        if (config('app.profiler') && config('app.debug')) {
            $this->app->register('Barryvdh\Debugbar\ServiceProvider');
        }
    }
}
