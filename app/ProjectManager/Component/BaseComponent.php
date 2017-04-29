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

namespace BynqIO\CalculatieTool\ProjectManager\Component;

use BynqIO\CalculatieTool\Models\Project;
use Illuminate\Container\Container;

/**
 * Class BaseComponent.
 */
abstract class BaseComponent
{
    protected $container;
    protected $project;
    protected $type;
    protected $component;

    public function response()
    {
        $data = [
            'project'   => $this->project,
            'wizard'    => $this->type, //TODO: rename
            'type'      => $this->type,
            'page'      => $this->component, //TODO: rename
            'component' => $this->component,
        ];

        return $this->render()->with($data);
    }

    public function __construct(Container $container, $project, $type, $component)
    {
        $this->container = $container;
        $this->project = $project;
        $this->type = $type;
        $this->component = $component;
    }
}
