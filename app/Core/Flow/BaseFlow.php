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

namespace BynqIO\CalculatieTool\Core\Flow;

class BaseFlow
{
    protected $currentStep;

    /**
     * @param $entity
     * @param $type
     *
     * @return string
     */
    private function getEventClass($entity, $type)
    {
        return 'App\\Events\\' . ucfirst($entity->getEntityType()) . 'Was' . $type;
    }

    // public function __construct($id)
    // {
    //     $this->project = Project::findOrFail($id);
    //     if (!$this->project->isOwner()){
    //         throw new NotAllowedException;
    //     }
    //     if ($this->project->is_dilapidated){
    //         throw new NotAllowedException;
    //     }
    // }
}
