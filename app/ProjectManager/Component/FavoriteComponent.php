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
use BynqIO\Dynq\Models\FavoriteActivity;

use Auth;

/**
 * Class FavoriteComponent.
 */
class FavoriteComponent extends BaseComponent implements Component
{
    public function calculateFilter($builder)
    {
        return FavoriteActivity::where('user_id', Auth::id())
                          ->orderBy('created_at', 'desc');
    }

    public function render()
    {
        $ledger = new Ledger($this, [
            'level.new'              => true,

            // 'chapter'                => true,
            'chapter.options'        => true,

            /* Activity options */
            'activity.options'        => true,
            'activity.changename'     => true,
            'activity.note'           => true,
            'activity.favorite'       => true,
            'activity.remove'         => true,

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
            'rows.other'                 => true,
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
            return [null];
        });

        $ledger->layer(function ($layer, $activity = null) {
            switch ($layer) {
                case 'labor':
                    return 'BynqIO\Dynq\Models\FavoriteLabor';
                case 'material':
                    return 'BynqIO\Dynq\Models\FavoriteMaterial';
                case 'other':
                    return 'BynqIO\Dynq\Models\FavoriteEquipment';
            }
        });

        $ledger->layerTotal(function ($activity) {
            return 'BynqIO\Dynq\Calculus\FavoriteRegister';
        });

        $ledger->profit(function ($layer, $activity) {
            return 0;
        });

        $ledger->calculateRow(function ($row, $profit = 0) use ($ledger) {
            return $row->getRate($ledger->isOriginal()) * $row->getAmount($ledger->isOriginal()) * (($profit/100)+1);
        });

        return $this->blockLayout($ledger->data(['name' => 'calculate'])->make());
    }
}
