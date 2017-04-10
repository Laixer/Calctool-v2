<?php

namespace CalculatieTool\Providers;

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
        $this->registerCalctool();

        //
    }

    /**
     * Register the calctool helper.
     *
     * @return void
     */
    public function registerCalctool()
    {
        $this->app->bind('calctool', function() {
            return new \CalculatieTool\Other\Calctool;
        });
    }
}
