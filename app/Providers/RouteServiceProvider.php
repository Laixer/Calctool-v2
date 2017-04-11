<?php

namespace CalculatieTool\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

use Auth;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * A namespace is applied to a grou of controller routes in the routes file.
     *
     * @var string
     */ 
    protected $namespace = 'CalculatieTool\Http\Controllers';
    protected $namespaceAdmin = 'CalculatieTool\Http\Controllers\Admin';
    protected $namespaceApi = 'CalculatieTool\Http\Controllers\Api';

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

        $this->mapApiRoutes();
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

    /**
     * Define the "api" routes for the application.
      *
     * @return void
     */
    protected function mapApiRoutes()
    {
        /* Admin application routes */
        Route::group([
            'namespace' => $this->namespaceApi,
            'prefix' => 'api/v1',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }
}
