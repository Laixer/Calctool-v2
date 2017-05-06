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

namespace BynqIO\Dynq\ProjectManager\Component;

use BynqIO\Dynq\ProjectManager\Contracts\Component;
use BynqIO\Dynq\Models\PartType;

/**
 * Class CalculationComponent.
 */
class CalculationComponent extends BaseComponent implements Component
{
    public function calculateFilter($builder)
    {
        return $builder->whereNull('detail_id')
                       ->where('part_type_id', PartType::where('type_name','calculation')->first()->id)
                       ->orderBy('priority');
    }

    public function estimateFilter($builder)
    {
        return $builder->whereNull('detail_id')
                       ->where('part_type_id', PartType::where('type_name','estimate')->first()->id)
                       ->orderBy('priority');
    }

    public function render()
    {
        $data['filter'] = function($section, $object) {
            return $this->{$section . 'Filter'}($object);
        };

        $tabs[] = ['name' => 'calculate', 'title' => 'Calculatie', 'icon' => 'fa-list'];

        /* Additional options */
        if ($this->project->use_estimate) {
            $tabs[] = ['name' => 'estimate', 'include' => 'calculate', 'title' => 'Stelposten', 'icon' => 'fa-align-justify'];
        }

        $async = [
            ['name' => 'summary',   'title' => 'Uittrekstaat',  'icon' => 'fa-sort-amount-asc', 'async' => "/calculation/summary/project-{$this->project->id}"],
            ['name' => 'endresult', 'title' => 'Eindresultaat', 'icon' => 'fa-check-circle-o',  'async' => "/calculation/endresult/project-{$this->project->id}"],
        ];

        $tabs[] = $async[0];
        $tabs[] = $async[1];

        return $this->tabLayout($tabs, $data);
    }
}
