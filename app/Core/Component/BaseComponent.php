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

namespace BynqIO\CalculatieTool\Core\Component;

use BynqIO\CalculatieTool\Models\Project;

/**
 * Class BaseComponent.
 */
abstract class BaseComponent
{
    protected $control;
    protected $project;
    protected $type;
    protected $component;

    public function response()
    {
        $data = [
            'project' => $this->project,
            'wizard' => $this->type,
            'page' => $this->component,
        ];

        return $this->render()->with($data);
    }

    public function setProject($project)
    {
        $this->project = $project;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setComponent($component)
    {
        $this->component = $component;
    }

    public function __construct()
    {
        $this->control = app()->make('flow');
    }
}
