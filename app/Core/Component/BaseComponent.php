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
use BynqIO\CalculatieTool\Core\Exceptions\NotAllowedException;

/**
 * Class BaseComponent.
 */
abstract class BaseComponent
{
    protected $project;

    /**
     * @return null
     */
    public function getClassName()
    {
        return null;
    }

    /**
     * @return null
     */
    public static function getName()
    {
        return null;
    }

    public function __construct($id)
    {
        $this->project = Project::findOrFail($id);
        if (!$this->project->isOwner()){
            throw new NotAllowedException;
        }
        if ($this->project->is_dilapidated){
            throw new NotAllowedException;
        }
    }
}
