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

namespace BynqIO\CalculatieTool\Core\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class FlowServiceProvider extends ServiceProvider
{
    /**
     * The flow paths provided for the project.
     *
     * @var array
     */
    protected $flow = [];

    /**
     * Register the application's event listeners.
     *
     * @return void
     */
    public function boot()
    {
        // foreach ($this->flows() as $event) {
        //     Event::listen($event, $listener);
        // }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->singleton('flow', function ($app) {
        //     $config = $app->make('config')->get('database.redis');

        //     return new RedisManager(Arr::pull($config, 'client', 'predis'), $config);
        // });

        // $this->app->bind('redis.connection', function ($app) {
        //     return $app['redis']->connection();
        // });
    }

    /**
     * Get the flows.
     *
     * @return array
     */
    public function flows()
    {
        return $this->flow;
    }
}
