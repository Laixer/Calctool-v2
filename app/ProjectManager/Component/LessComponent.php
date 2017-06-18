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
        $ledger = new Ledger($this, [
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
        ]);

        $ledger->layer(function ($layer, $activity = null) {
            switch ($layer) {
                case 'labor':
                    return 'BynqIO\Dynq\Models\CalculationLabor';
                case 'material':
                    return 'BynqIO\Dynq\Models\CalculationMaterial';
                case 'other':
                    return 'BynqIO\Dynq\Models\CalculationEquipment';
            }
        });

        $ledger->profit(function ($layer, $activity) {
            if ($activity->isSubcontracting()) {
                switch ($layer) {
                    case 'labor':
                        return 0;
                    case 'material':
                        return $this->project->profit_calc_subcontr_mat;
                    case 'other':
                        return $this->project->profit_calc_subcontr_equip;
                }
            } else {
                switch ($layer) {
                    case 'labor':
                        return 0;
                    case 'material':
                        return $this->project->profit_calc_contr_mat;
                    case 'other':
                        return $this->project->profit_calc_contr_equip;
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
            ['name' => 'calculate', 'title' => 'Minderwerk',    'icon' => 'fa-list'],
            ['name' => 'summary',   'title' => 'Uittrekstaat',  'icon' => 'fa-sort-amount-asc', 'async' => "summary/project-{$this->project->id}"],
            ['name' => 'endresult', 'title' => 'Eindresultaat', 'icon' => 'fa-check-circle-o',  'async' => "endresult/project-{$this->project->id}"],
        ];

        return $this->tabLayout($tabs, $ledger->original(false)->make());
    }
}
