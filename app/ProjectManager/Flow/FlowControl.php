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

use Exception;
use ReflectionException;
use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\ProjectManager\Flow\BaseFlow;

class FlowControl
{
    protected $container = [];

    /**
     * The application instance.
     *
     * @var object
     */
    protected $app;

    public function all()
    {
        $names = [];
        foreach ($this->container as $class => $instance) {
            array_push($names, static::name($class));
        }

        return $names;
    }

    public function make($id, $component)
    {
        $project = Project::findOrFail($id);
        if (!$project->isOwner()){
            throw new NotAllowedException;
        }

        if ($project->is_dilapidated){
            throw new NotAllowedException;
        }

        $this->resolveFlow($project);

        $type = static::convertProjectType($project->type->type_name);

        try {
            $instance = $this->app->makeWith($component, [
                'project' => $project,
                'type' => $type,
                'component' => $component,
            ]);
        } catch (ReflectionException $e) {
            abort(404);
        }

        return $instance;
    }

    public static function name($input)
    {
        $name = strtolower(class_basename($input));
        if (str_contains($name, 'flow')) {
            return str_replace("flow", null, $name);
        }

        return $name;
    }

    private static function convertProjectType($type)
    {
        switch ($type) {
            case 'regie':
                return 'directwork';
            case 'calculatie':
                return 'calculation';
            case 'snelle offerte en factuur':
                return 'quickinvoice';
        }
    }

    protected function resolveFlow($project)
    {
        $projectType = static::convertProjectType($project->type->type_name);
        foreach ($this->container as $class => $instance) {
            if (static::name($class) == $projectType) {
                if (is_null($instance)) {

                    $instance = $this->app->make($class);
                    if (!$instance instanceof BaseFlow) {
                        throw Exception('Must be instance of BaseFlow');
                    }

                    $this->container[$class] = $instance;
                }

                return $this->container[$class];
            }
        }
    }

    public function add($flow)
    {
        // if (!class_exists($flow)) {
        //
        // }
        $this->container[$flow] = null;
    }

    public function __construct($app)
    {
        $this->app = $app;
    }
}
