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

namespace BynqIO\Dynq\ProjectManager\Flow;

use Closure;
use Illuminate\Container\Container;

class BaseFlow
{
    protected $container;
    protected $namespace = 'BynqIO\Dynq\ProjectManager\Component';
    protected $currentStep;

    protected function bind($name, $func)
    {
        $this->container->bind($name, function ($app, array $parameters) use ($func) {
            $class = $this->namespace . '\\'. $func;
            return $app->makeWith($class, [
                'project' => $parameters['project'],
                'type' => $parameters['type'],
                'component' => $parameters['component'],
            ]);
        });
    }

    public function __construct(Container $container) {
        $this->container = $container;
        $this->map();
    }

    public function __toString() {
        return $this->name();
    }
}
