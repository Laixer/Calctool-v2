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

namespace BynqIO\Dynq\Http\Controllers\Calculation;

use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\Models\Chapter;
use BynqIO\Dynq\Models\Part;
use BynqIO\Dynq\Models\Detail;
use BynqIO\Dynq\Models\PartType;
use BynqIO\Dynq\Models\Activity;
use BynqIO\Dynq\Models\MoreLabor;
use BynqIO\Dynq\Models\MoreMaterial;
use BynqIO\Dynq\Models\MoreEquipment;
use BynqIO\Dynq\Calculus\MoreRegister;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Default Home Controlluse BynqIO\Dynq\Models\Invoice
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |	Route::get('/', 'HomeController@showWelcome');
    |
    */

    private function profit($layer, $activity, $project) {
        if ($activity->isSubcontracting()) {
            switch ($layer) {
                case 'labor':
                    return 0;
                case 'material':
                    return $project->profit_more_subcontr_mat;
                case 'other':
                    return $project->profit_more_subcontr_equip;
            }
        } else {
            switch ($layer) {
                case 'labor':
                    return 0;
                case 'material':
                    return $project->profit_more_contr_mat;
                case 'other':
                    return $project->profit_more_contr_equip;
            }
        }
    }

    public function new(Request $request)
    {
        $this->validate($request, [
            'name'      => ['required_unless:layer,labor', 'max:100'],
            'unit'      => ['required_unless:layer,labor', 'max:10'],
            'rate'      => ['required_unless:layer,labor', 'numeric'],
            'amount'    => ['required', 'numeric'],
            'activity'  => ['required', 'integer', 'min:0'],
            'layer'     => ['required'],
        ]);

        $object = null;

        $activity = Activity::findOrFail($request->get('activity'));

        switch ($request->get('layer')) {
            case 'labor':
                $object = $this->newLaborRow($activity, $request->all());
                break;
            case 'material':
                $object = $this->newMaterialRow($activity, $request->all());
                break;
            case 'other':
                $object = $this->newOtherRow($activity, $request->all());
                break;
        }

        return response()->json(array_merge(['success' => true], $object));
    }
}
