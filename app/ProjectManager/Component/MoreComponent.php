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
use BynqIO\Dynq\ProjectManager\Support\Ledger;
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
        $ledger = new Ledger($this, [
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
            'rows.timesheet.add'         => true,
            'rows.timesheet.remove'      => true,
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
        ]);

        $ledger->layer(function ($layer, $activity = null) {
            switch ($layer) {
                case 'labor':
                    return 'BynqIO\Dynq\Models\MoreLabor';
                case 'material':
                    return 'BynqIO\Dynq\Models\MoreMaterial';
                case 'other':
                    return 'BynqIO\Dynq\Models\MoreEquipment';
            }
        });

        $ledger->layerTotal(function ($activity) {
            return 'BynqIO\Dynq\Calculus\MoreRegister';
        });

        $ledger->profit(function ($layer, $activity) {
            if ($activity->isSubcontracting()) {
                switch ($layer) {
                    case 'labor':
                        return 0;
                    case 'material':
                        return $this->project->profit_more_subcontr_mat;
                    case 'other':
                        return $this->project->profit_more_subcontr_equip;
                }
            } else {
                switch ($layer) {
                    case 'labor':
                        return 0;
                    case 'material':
                        return $this->project->profit_more_contr_mat;
                    case 'other':
                        return $this->project->profit_more_contr_equip;
                }
            }
        });

        $ledger->calculateRow(function ($row, $profit = 0) use ($ledger) {
            return $row->getRate($ledger->isOriginal()) * $row->getAmount($ledger->isOriginal()) * (($profit/100)+1);
        });

        if ($this->project->use_equipment) {
            $ledger->features(['rows.other' => true]);
        }

        /* Disable all editable options for closed projects */
        if ($this->project->project_close) {
            $this->readOnly();
        }

        $tabs = [
            ['name' => 'calculate', 'title' => 'Meerwerk',    'icon' => 'fa-list'],
            ['name' => 'summary',   'title' => 'Uittrekstaat',  'icon' => 'fa-sort-amount-asc', 'async' => "summary/project-{$this->project->id}"],
            ['name' => 'endresult', 'title' => 'Eindresultaat', 'icon' => 'fa-check-circle-o',  'async' => "endresult/project-{$this->project->id}"],
        ];

        return $this->tabLayout($tabs, $ledger->make());
    }
}
