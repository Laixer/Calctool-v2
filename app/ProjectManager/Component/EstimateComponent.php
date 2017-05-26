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
 * Class EstimateComponent.
 */
class EstimateComponent extends BaseComponent implements Component
{
    public function calculateFilter($builder)
    {
        return $builder->whereNull('detail_id')
                       ->where('part_type_id', PartType::where('type_name','estimate')->firstOrFail()->id)
                       ->orderBy('priority');
    }

    public function summaryFilter($builder)
    {
        return $builder->whereNull('detail_id')
                       ->where('part_type_id', PartType::where('type_name','estimate')->firstOrFail()->id)
                       ->orderBy('priority');
    }

    public function render()
    {
        $data['filter'] = function($section, $object) {
            return $this->{$section . 'Filter'}($object);
        };

        $data['features'] = [
            'activity.options'       => true,
            'activity.timesheet'     => true,

            'chapter.options'        => false,
            'chapter.move'           => false,
            'chapter.changename'     => false,
            'chapter.remove'         => false,

            'rows.labor'             => true,
            'rows.labor.edit'        => true,
            'rows.labor.reset'       => true,
            'rows.timesheet'         => true,
            'rows.material'          => true,
            'rows.material.add'      => true,
            'rows.material.edit'     => true,
            'rows.material.reset'    => true,
            'rows.other'             => false,
            'rows.other.add'         => true,
            'rows.other.edit'        => true,
            'rows.other.reset'       => true,

            'tax.update'             => false,
        ];

        if ($this->project->use_equipment) {
            $data['features']['rows.other'] = true;
        }

        $data['layer']['labor']     = 'BynqIO\Dynq\Models\EstimateLabor';
        $data['layer']['material']  = 'BynqIO\Dynq\Models\EstimateMaterial';
        $data['layer']['other']     = 'BynqIO\Dynq\Models\EstimateEquipment';

        $tabs[] = ['name' => 'calculate', 'title' => 'Stelposten stellen', 'icon' => 'fa-list'];

        $async = [
            ['name' => 'summary',   'title' => 'Uittrekstaat',  'icon' => 'fa-sort-amount-asc', ], //'async' => "/calculation/summary/project-{$this->project->id}"
            ['name' => 'endresult', 'title' => 'Eindresultaat', 'icon' => 'fa-check-circle-o',  ],//'async' => "/calculation/endresult/project-{$this->project->id}"
        ];

        $tabs[] = $async[0];
        $tabs[] = $async[1];

        return $this->tabLayout($tabs, $data);
    }
}
