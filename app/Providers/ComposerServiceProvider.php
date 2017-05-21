<?php

namespace BynqIO\Dynq\Providers;

use BynqIO\Dynq\Services\FormatService;
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
        view()->composer('layout.header', 'BynqIO\Dynq\Http\Composers\HeaderComposer');

        Blade::directive('logo', function () {
            if (defined('APP_LOGO')) {
                $appname = config('app.name');
                $logo = APP_LOGO;
                $logo_width = APP_LOGO_WIDTH;
                return "<img src='{$logo}' width='{$logo_width}px' alt='{$appname}' title='{$appname}' />";
            }
        });

        Blade::directive('format', function ($expression) {

            // $format = $expression);
            return "<?php if(isset($expression) && $expression === true): ?>";
            return "{FormatService::monetary($expression)}";
// dd($expression);
  
//  {{ '&euro; ' . \BynqIO\Dynq\Services\FormatService::monetary() }}

            // return FormatService::monetary($expression);

            // "'&euro; '"

            // return "<img src='{$logo}' width='{$logo_width}px' alt='{$appname}' title='{$appname}' />";
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
