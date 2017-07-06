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
use BynqIO\Dynq\Models\FavoriteActivity;
use BynqIO\Dynq\Models\CalculationLabor;
use BynqIO\Dynq\Models\FavoriteLabor;

use BynqIO\Dynq\Http\Controllers\Controller;
use BynqIO\Dynq\Adapters\LaborAdapter;
use BynqIO\Dynq\Adapters\ActivityAdapter;
use Illuminate\Http\Request;

class TransformController extends Controller
{
    private function mapActivity($activity_new, $activity_copy, $user)
    {
        $new  = new ActivityAdapter($activity_new);
        $copy = new ActivityAdapter($activity_copy);

        $new->setName($copy->getName());
        $new->setNote($copy->getNote());
        $new->setUser($user);

        $new->setLaborTax($copy->getLaborTax());
        $new->setMaterialTax($copy->getMaterialTax());
        $new->setOtherTax($copy->getOtherTax());

        return $new->getActivity();
    }

    private function mapLabor($activity_new, $labor_copy, $activity)
    {
        $new  = new LaborAdapter($activity_new);
        $copy = new LaborAdapter($labor_copy);

        $new->setRate($copy->getRate());
        $new->setAmount($copy->getAmount());
        $new->setParent($activity);

        return $new->getLabor();
    }

    private function mapMaterial($activity_new, $labor_copy, $activity)
    {
        // foreach (CalculationMaterial::where('activity_id', $activity->id)->get() as $orig_calc_material) {
        //     $calc_material = new FavoriteMaterial;
        //     $calc_material->material_name = $orig_calc_material->material_name;
        //     $calc_material->unit = $orig_calc_material->unit;
        //     $calc_material->rate = $orig_calc_material->rate;
        //     $calc_material->amount = $orig_calc_material->amount;
        //     $calc_material->activity_id = $fav_activity->id;

        //     $calc_material->save();
        // }
    }

    private function mapEquipment($activity_new, $labor_copy, $activity)
    {
        // foreach (CalculationEquipment::where('activity_id', $activity->id)->get() as $orig_calc_equipment) {
        //     $calc_equipment = new FavoriteEquipment;
        //     $calc_equipment->equipment_name = $orig_calc_equipment->equipment_name;
        //     $calc_equipment->unit = $orig_calc_equipment->unit;
        //     $calc_equipment->rate = $orig_calc_equipment->rate;
        //     $calc_equipment->amount = $orig_calc_equipment->amount;
        //     $calc_equipment->activity_id = $fav_activity->id;

        //     $calc_equipment->save();
        // }
    }

    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'activity' => ['required','integer']
        ]);

        $activity = Activity::findOrFail($request->get('activity'));

        /* Convert activity and commit to persistent storage */
        $new_activity = $this->mapActivity(new FavoriteActivity, $activity, $request->user());
        $new_activity->save();

        foreach (CalculationLabor::where('activity_id', $activity->id)->get() as $labor) {
            $new_labor = $this->mapLabor(new FavoriteLabor, $labor, $new_activity);
            $new_labor->save();
        }

        ////

        return back()->with('success', 'Toegevoegd aan favorieten');
    }

}
