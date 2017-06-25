<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the Dynq project.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  Dynq
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
 */

namespace BynqIO\Dynq\Listeners;

use BynqIO\Dynq\Models\Audit;
use BynqIO\Dynq\Models\User;
use Illuminate\Auth\Events\Login;

use Encryptor;

class EvolutionUpgrade
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    protected $user;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    protected function isExecuted()
    {
        return false;//TODO
    }

    protected function evolutionPath()
    {
        return app_path() . '/Evolutions';
    }

    private function findClass($file)
    {
        $php_code = file_get_contents($file);
        $classes = get_php_classes($php_code);
        if (!count($classes)) {
            return; //TODO: should be exception
        }

        include_once $file;

        $instance = new $classes[0];

        if (method_exists($instance, 'up')) {
            $instance->up($this->user);
        }
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $this->user = $event->user;
        $path = $this->evolutionPath();
        $files = scandir($path, SCANDIR_SORT_ASCENDING);

        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            if (preg_match('/^\d{4}_\d{2}_\d{2}_\d{6}_.+\.php$/', $file)) {
                if ($this->isExecuted()) {
                    continue;
                }

                $this->findClass($path . '/' . $file);
            }
        }
    }
}
