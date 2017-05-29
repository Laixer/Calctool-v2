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
 * Class LessComponent.
 */
class LessComponent extends BaseComponent implements Component
{
    public function calculateFilter($builder)
    {
        return $builder->whereNull('detail_id')
                       ->where('part_type_id', PartType::where('type_name','calculation')->firstOrFail()->id)
                       ->orderBy('priority');
    }

    public function summaryFilter($builder)
    {
        return $builder->whereNull('detail_id')
                       ->where('part_type_id', PartType::where('type_name','calculation')->firstOrFail()->id)
                       ->orderBy('priority');
    }

    public function render()
    {
        $data['filter'] = function($section, $object) {
            return $this->{$section . 'Filter'}($object);
        };

        $data['layer'] = function($layer, $activity = null) {
            switch ($layer) {
                case 'labor':
                    return 'BynqIO\Dynq\Models\CalculationLabor';
                case 'material':
                    return 'BynqIO\Dynq\Models\CalculationMaterial';
                case 'other':
                    return 'BynqIO\Dynq\Models\CalculationEquipment';
            }
        };

        $data['features'] = [
            'level.new' => false,

            'activity.move' => false,
            'activity.changename' => false,
            'activity.remove' => true,
            'activity.convertsubcon' => false,
            'activity.timesheet'     => false,

            'chapter.options'        => false,
            'chapter.move'           => false,
            'chapter.changename'     => false,
            'chapter.remove'         => false,

            'rows.labor.edit.amount'      => true,
            'rows.labor.reset'     => true,
            'rows.labor'           => true,
            'rows.timesheet'       => true,
            'rows.material.edit'   => true,
            'rows.material.edit.rate'   => true,
            'rows.material.edit.amount'   => true,
            'rows.material.reset'  => true,
            'rows.material'        => true,
            'rows.other.edit'      => true,
            'rows.other.edit.rate'      => true,
            'rows.other.edit.amount'      => true,
            'rows.other.reset'     => true,
            'rows.other'           => false,

            'tax.update' => false,
        ];

        $data['original'] = false;

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

        $tabs[] = ['name' => 'calculate', 'title' => 'Minderwerk', 'icon' => 'fa-list'];

        $async = [
            ['name' => 'summary',   'title' => 'Uittrekstaat',  'icon' => 'fa-sort-amount-asc', 'async' => "summary/project-{$this->project->id}"],
            ['name' => 'endresult', 'title' => 'Eindresultaat', 'icon' => 'fa-check-circle-o',  'async' => "endresult/project-{$this->project->id}"],
        ];

        $tabs[] = $async[0];
        $tabs[] = $async[1];

        return $this->tabLayout($tabs, $data);
    }
}
