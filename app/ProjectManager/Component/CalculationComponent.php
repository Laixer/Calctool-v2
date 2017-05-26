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

use BynqIO\Dynq\Models\CalculationLabor;
use BynqIO\Dynq\Models\CalculationMaterial;
use BynqIO\Dynq\Models\CalculationEquipment;

/**
 * Class CalculationComponent.
 */
class CalculationComponent extends BaseComponent implements Component
{
    public function calculateFilter($builder)
    {
        return $builder->whereNull('detail_id')
                       ->orderBy('priority');
    }

    public function summaryFilter($builder)
    {
        return $builder->whereNull('detail_id')
                       ->orderBy('priority');
    }

    public function render()
    {
        $data['filter'] = function($section, $object) {
            return $this->{$section . 'Filter'}($object);
        };

        $data['features'] = [
            'activity.options' => true,
            'chapter.options' => true,
            'rows.labor'     => true,
            'rows.labor.edit'=> false,
            'rows.timesheet' => true,
            'rows.material'  => true,
            'rows.material.add'=> true,
            'rows.material.edit'=> true,
            'rows.material.remove'=> true,
            'rows.other'     => false,
            'rows.other.add'=> true,
            'rows.other.edit'=> true,
            'rows.other.remove'=> true,
        ];

        if ($this->project->use_equipment) {
            $data['features']['rows.other'] = true;
        }

        /* Disable all editable options for closed projects */
        if ($this->project->project_close) {
            $data['features']['level.new']           = false;
            $data['features']['activity.options']    = false;
            $data['features']['chapter.options']     = false;
            $data['features']['tax.update']          = false;
            $data['features']['rows.labor.edit']     = false;
            $data['features']['rows.material.add']   = false;
            $data['features']['rows.material.edit']  = false;
            $data['features']['rows.other.add']      = false;
            $data['features']['rows.other.edit']     = false;
        }

        $data['layer']['labor']     = 'BynqIO\Dynq\Models\CalculationLabor';
        $data['layer']['material']  = 'BynqIO\Dynq\Models\CalculationMaterial';
        $data['layer']['other']     = 'BynqIO\Dynq\Models\CalculationEquipment';

        $tabs[] = ['name' => 'calculate', 'title' => 'Calculatie', 'icon' => 'fa-list'];

        $async = [
            ['name' => 'summary',   'title' => 'Uittrekstaat',  'icon' => 'fa-sort-amount-asc', ], //'async' => "/calculation/summary/project-{$this->project->id}"
            ['name' => 'endresult', 'title' => 'Eindresultaat', 'icon' => 'fa-check-circle-o', ], // 'async' => "/calculation/endresult/project-{$this->project->id}"
        ];

        $tabs[] = $async[0];
        $tabs[] = $async[1];

        return $this->tabLayout($tabs, $data);
    }
}
