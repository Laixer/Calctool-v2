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
        $ledger = new Ledger($this, [
            'level.new'                  => true,

            'chapter'                    => true,
            'chapter.options'            => true,

            /* Activity options */
            'activity.options'           => true,
            'activity.move'              => true,
            'activity.changename'        => true,
            'activity.note'              => true,
            'activity.favorite'          => true,
            'activity.remove'            => true,
            'activity.convertsubcon'     => true,
            'activity.converestimate'    => true,

            /* Row options */
            'rows.labor'                 => true,
            'rows.labor.edit'            => true,
            'rows.labor.edit.rate'       => true,
            'rows.labor.edit.amount'     => true,
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

        $ledger->levelFilter(function () {
            return $this->project->chapters()->orderBy('priority')->get();
        });

        $ledger->layer(function ($layer, $activity) {
            if ($activity->isEstimate()) {
                switch ($layer) {
                    case 'labor':
                        return 'BynqIO\Dynq\Models\EstimateLabor';
                    case 'material':
                        return 'BynqIO\Dynq\Models\EstimateMaterial';
                    case 'other':
                        return 'BynqIO\Dynq\Models\EstimateEquipment';
                }
            } else {
                switch ($layer) {
                    case 'labor':
                        return 'BynqIO\Dynq\Models\CalculationLabor';
                    case 'material':
                        return 'BynqIO\Dynq\Models\CalculationMaterial';
                    case 'other':
                        return 'BynqIO\Dynq\Models\CalculationEquipment';
                }
            }
        });

        $ledger->layerTotal(function ($activity) {
            if ($activity->isEstimate()) {
                return 'BynqIO\Dynq\Calculus\EstimateRegister';
            } else {
                return 'BynqIO\Dynq\Calculus\CalculationRegister';
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

        if ($this->project->quotations()->orderBy('created_at', 'desc')->limit(1)->count()) {
            $ledger->features([
                'level.new'                   => false,
                'activity.options'            => false,
                'chapter.options'             => false,
                'tax.update'                  => false,
                'rows.labor.edit'             => false,
                'rows.labor.edit.rate'        => false,
                'rows.labor.edit.amount'      => false,
                'rows.material.add'           => false,
                'rows.material.edit'          => false,
                'rows.material.edit.name'     => false,
                'rows.material.edit.unit'     => false,
                'rows.material.edit.rate'     => false,
                'rows.material.edit.amount'   => false,
                'rows.other.add'              => false,
                'rows.other.edit'             => false,
                'rows.other.edit.name'        => false,
                'rows.other.edit.unit'        => false,
                'rows.other.edit.rate'        => false,
                'rows.other.edit.amount'      => false,
            ]);
        }

        /* Disable all editable options for closed projects */
        if ($this->project->project_close) {
            $ledger->readOnly();
        }

        $tabs = [
            ['name' => 'calculate', 'title' => 'Calculatie',    'icon' => 'fa-list'],
            ['name' => 'summary',   'title' => 'Uittrekstaat',  'icon' => 'fa-sort-amount-asc', 'async' => "summary/project-{$this->project->id}"],
            ['name' => 'endresult', 'title' => 'Eindresultaat', 'icon' => 'fa-check-circle-o',  'async' => "endresult/project-{$this->project->id}"],
        ];

        return $this->tabLayout($tabs, $ledger->make());
    }
}
