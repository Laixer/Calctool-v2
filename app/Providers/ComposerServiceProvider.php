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

        Blade::directive('money', function ($expression, $symbol = true) {
            if ($symbol) {
                return "<?php echo '" . LOCALE_CURRENCY . " ' . \BynqIO\Dynq\Services\FormatService::monetary($expression); ?>";
            } else {
                return "<?php echo \BynqIO\Dynq\Services\FormatService::monetary($expression); ?>";
            }
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
