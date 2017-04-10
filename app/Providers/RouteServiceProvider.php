<?php

namespace Calctool\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

use Auth;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */ 
    protected $namespace = 'Calctool\Http\Controllers';

    /**
     * This namespace is applied to the admin controller routes.
     *
     * @var string
     */ 
    protected $namespaceAdmin = 'Calctool\Http\Controllers\Admin';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::pattern('project_id', '[0-9]{5}');

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        /* Default application routes */
        Route::group([
            'namespace' => $this->namespace
        ], function ($router) {
            require base_path('routes/web.php');
        });

        /* Admin application routes */
        Route::group([
            'namespace' => $this->namespace,
            'before' => 'admin',
            'prefix' => 'admin',
            'middleware' => 'admin'
        ], function ($router) {
            require base_path('routes/admin.php');
        });

    }
}
