<?php

namespace BynqIO\CalculatieTool\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('layout.header', 'BynqIO\CalculatieTool\Http\Composers\HeaderComposer');

        Blade::directive('logo', function () {
            if (!defined('APP_LOGO')) {
                return;
            }

            $appname = config('app.name');
            $logo = APP_LOGO;
            return "<img src='{$logo}' width='230px' alt='{$appname}' title='{$appname}' />";
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
