<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the BynqIO\CalculatieTool.com.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  CalculatieTool
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
 */

namespace BynqIO\CalculatieTool\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

use Auth;
use URL;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Toplevel namespaces.
     *
     * @var string
     */ 
    protected $namespace            = 'BynqIO\CalculatieTool\Http\Controllers';
    protected $namespaceApi         = 'BynqIO\CalculatieTool\Http\Controllers\Api';
    protected $namespaceAdmin       = 'BynqIO\CalculatieTool\Http\Controllers\Admin';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::pattern('project_id',  '[0-9]{5,}');
        Route::pattern('relation_id', '[0-9]{5,}');
        Route::pattern('contact_id',  '[0-9]{3,}');
        Route::pattern('resource_id', '[0-9]+');
        Route::pattern('invoice_id',  '[0-9]+');
        Route::pattern('token',       '[0-9a-z]{40}');

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
        $this->mapAsyncRoutes();
        $this->mapApiRoutes();
    }

    /**
     * Define the "web" routes for the internal application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        $routesWeb   = function ($router) { require base_path('routes/web.php'); };
        $routesAdmin = function ($router) { require base_path('routes/admin.php'); };

        /* Default application routes */
        Route::group([
            'namespace' => $this->namespace,
            'middleware' => 'web'
        ], $routesWeb);

        /* Admin application routes */
        Route::group([
            'namespace' => $this->namespaceAdmin,
            'prefix' => 'admin',
            'middleware' => 'admin'
        ], $routesAdmin);
    }

    /**
     * Define the "async" routes for the internal application.
      *
     * @return void
     */
    protected function mapAsyncRoutes()
    {
        $routes = function ($router) { require base_path('routes/async.php'); };

        /* Admin application routes */
        Route::group([
            'namespace' => $this->namespaceApi,
            'prefix' => 'api/v1',//TODO: /async/
            'middleware' => 'async'
        ], $routes);
    }

    /**
     * Define the "api" routes for the external application.
      *
     * @return void
     */
    protected function mapApiRoutes()
    {
        $routes = function ($router) { require base_path('routes/api.php'); };

        /* Admin application routes */
        Route::group([
            'namespace' => $this->namespaceApi,
            'prefix' => 'oauth2',
            'middleware' => 'api'
        ], $routes);
    }

}
