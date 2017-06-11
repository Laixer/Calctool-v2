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
use BynqIO\Dynq\Models\Detail;
use BynqIO\Dynq\Models\PartType;

/**
 * Class MoreComponent.
 */
class MoreComponent extends BaseComponent implements Component
{
    public function calculateFilter($builder)
    {
        return $builder->where('detail_id', Detail::where('detail_name','more')->firstOrFail()->id)
                       ->where('part_type_id', PartType::where('type_name','calculation')->firstOrFail()->id)
                       ->orderBy('priority');
    }

    public function summaryFilter($builder)
    {
        return $builder->where('detail_id', Detail::where('detail_name','more')->firstOrFail()->id)
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
                    return 'BynqIO\Dynq\Models\MoreLabor';
                case 'material':
                    return 'BynqIO\Dynq\Models\MoreMaterial';
                case 'other':
                    return 'BynqIO\Dynq\Models\MoreEquipment';
            }
        };

        $data['features'] = [
            'level.new'              => true,

            'chapter.options'        => true,

            /* Activity options */
            'activity.options'        => true,
            'activity.move'           => true,
            'activity.changename'     => true,
            'activity.remove'         => true,
            'activity.timesheet'      => true,
            'activity.convertsubcon'  => true,
            'activity.converestimate' => false,

            /* Row options */
            'rows.labor'                 => true,
            'rows.labor.edit'            => true,
            'rows.labor.edit.rate'       => true,
            'rows.labor.edit.amount'     => true,
            'rows.timesheet'             => true,
            'rows.material'              => true,
            'rows.material.add'          => true,
            'rows.material.edit'         => true,
            'rows.material.edit.name'    => true,
            'rows.material.edit.unit'    => true,
            'rows.material.edit.rate'    => true,
            'rows.material.edit.amount'  => true,
            'rows.material.remove'       => true,
            'rows.other'                 => false,
            'rows.other.add'             => true,
            'rows.other.edit'            => true,
            'rows.other.edit.name'       => true,
            'rows.other.edit.unit'       => true,
            'rows.other.edit.rate'       => true,
            'rows.other.edit.amount'     => true,
            'rows.other.remove'          => true,

            /* Tax */
            'tax.update'             => true,
        ];

        $data['original'] = true;

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
