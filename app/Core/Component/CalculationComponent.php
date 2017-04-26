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

use BynqIO\CalculatieTool\Core\Contracts\ComponentInterface;

/**
 * Class CalculationComponent.
 */
class CalculationComponent extends BaseComponent implements ComponentInterface
{
    public function getClassName()
    {
        return 'App\Models\CalculationComponent';
    }

    public static function getName()
    {
        return 'calculation';
    }

    public function view(Array $optdata = [])
    {
        $data = array_merge([
            'project' => $this->project,
            'title' => $this->getName(),
            'page' => $this->getName(),
        ], $optdata);

        return view("component.{$this->getName()}", $data);
    }
}
