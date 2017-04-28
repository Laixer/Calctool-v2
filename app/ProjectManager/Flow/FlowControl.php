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

namespace BynqIO\CalculatieTool\ProjectManager\Flow;

use Exception;
use BynqIO\CalculatieTool\Models\Project;
use BynqIO\CalculatieTool\ProjectManager\Flow\BaseFlow;

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

        $instance = $this->app->make($component);
        $instance->setProject($project);
        $instance->setType($type);
        $instance->setComponent($component);

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
