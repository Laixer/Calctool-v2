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

namespace BynqIO\Dynq\Http\Controllers\Project;

use BynqIO\Dynq\Models\Activity;
use BynqIO\Dynq\Models\Tax;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LayerController extends Controller
{
    protected function setTaxLabor(Activity $activity, Tax $tax)
    {
        $activity->tax_labor_id = $tax->id;
        $activity->save();
    }

    protected function setTaxMaterial(Activity $activity, Tax $tax)
    {
        $activity->tax_material_id = $tax->id;
        $activity->save();
    }

    protected function setTaxOther(Activity $activity, Tax $tax)
    {
        $activity->tax_equipment_id = $tax->id;
        $activity->save();
    }

    public function updateTax(Request $request)
    {
        $this->validate($request, [
            'layer'    => ['required'],
            'value'    => ['required', 'integer'],
            'activity' => ['required', 'integer'],
        ]);

        $activity = Activity::findOrFail($request->get('activity'));
        $tax = Tax::findOrFail($request->input('value'));

        switch ($request->get('layer')) {
            case 'labor':
                $this->setTaxLabor($activity, $tax);
                break;
            case 'material':
                $this->setTaxMaterial($activity, $tax);
                break;
            case 'other':
                $this->setTaxOther($activity, $tax);
                break;
        }

        return response()->json(['success' => true]);
    }
}
