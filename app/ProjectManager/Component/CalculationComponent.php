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

use BynqIO\CalculatieTool\ProjectManager\Contracts\Component;

/**
 * Class CalculationComponent.
 */
class CalculationComponent extends BaseComponent implements Component
{
    public function render()
    {
        $data = [
            'tabs' => [
                ['name' => 'calculate', 'title' => 'Calculatie',    'icon' => 'fa-list'],
            ]
        ];

        if ($this->project->use_estimate) {
            array_push($data['tabs'], ['name' => 'estimate',  'title' => 'Stelposten',    'icon' => 'fa-align-justify']);
        }

        $async = [
            ['name' => 'summary',   'title' => 'Uittrekstaat',  'icon' => 'fa-sort-amount-asc', 'async' => "/calculation/summary/project-{$this->project->id}"],
            ['name' => 'endresult', 'title' => 'Eindresultaat', 'icon' => 'fa-check-circle-o',  'async' => "/calculation/endresult/project-{$this->project->id}"],
        ];

        array_push($data['tabs'], $async[0], $async[1]);

        return view("component.tabs", $data);
        // return view("component.{$this->component}", $data);
    }
}
