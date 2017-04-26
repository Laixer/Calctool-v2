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

/**
 * Class DetailComponent.
 */
class DetailsComponent extends BaseComponent
{
    public function getClassName()
    {
        return 'App\Models\DetailComponent';
    }

    public static function getName()
    {
        return 'details';
    }

    public function view(Array $optdata = [])
    {
        $data = array_merge([
            'project' => $this->project,
            'title' => 'projectgegevens',
            'page' => $this->getName(),
        ], $optdata);

        return view("component.{$this->getName()}", $data);
    }
}
