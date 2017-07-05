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

namespace BynqIO\Dynq\Http\Controllers\Favorite;

use BynqIO\Dynq\Models\Tax;
use BynqIO\Dynq\Models\FavoriteActivity;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

//TODO: check every operation with project status & permissions
class TransformController extends Controller
{
    public function xyz(Request $request)
    {
        $this->validate($request, [
            'activity' => array('required','integer','min:0')
        ]);

        $activity = Activity::find($request->input('activity'));
        if (!$activity)
            return response()->json(['success' => 0]);
        $chapter = Chapter::find($activity->chapter_id);
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return response()->json(['success' => 0]);
        }
        $project = Project::find($chapter->project_id);

        $fav_activity = new FavoriteActivity;
        $fav_activity->activity_name = $activity->activity_name;
        $fav_activity->note = $activity->note;
        $fav_activity->user_id = Auth::id();

        if ($project->tax_reverse) {
            $tax = Tax::where('tax_rate',21)->first();
            $fav_activity->tax_labor_id = $tax->id;
            $fav_activity->tax_material_id = $tax->id;
            $fav_activity->tax_equipment_id = $tax->id;
        } else {
            $fav_activity->tax_labor_id = $activity->tax_labor_id;
            $fav_activity->tax_material_id = $activity->tax_material_id;
            $fav_activity->tax_equipment_id = $activity->tax_equipment_id;
        }

        $fav_activity->save();

        foreach (CalculationLabor::where('activity_id', $activity->id)->get() as $orig_calc_labor) {
            $calc_labor = new FavoriteLabor;
            $calc_labor->rate = $orig_calc_labor->rate;
            $calc_labor->amount = $orig_calc_labor->amount;
            $calc_labor->activity_id = $fav_activity->id;

            $calc_labor->save();
        }

        foreach (CalculationMaterial::where('activity_id', $activity->id)->get() as $orig_calc_material) {
            $calc_material = new FavoriteMaterial;
            $calc_material->material_name = $orig_calc_material->material_name;
            $calc_material->unit = $orig_calc_material->unit;
            $calc_material->rate = $orig_calc_material->rate;
            $calc_material->amount = $orig_calc_material->amount;
            $calc_material->activity_id = $fav_activity->id;

            $calc_material->save();
        }

        foreach (CalculationEquipment::where('activity_id', $activity->id)->get() as $orig_calc_equipment) {
            $calc_equipment = new FavoriteEquipment;
            $calc_equipment->equipment_name = $orig_calc_equipment->equipment_name;
            $calc_equipment->unit = $orig_calc_equipment->unit;
            $calc_equipment->rate = $orig_calc_equipment->rate;
            $calc_equipment->amount = $orig_calc_equipment->amount;
            $calc_equipment->activity_id = $fav_activity->id;

            $calc_equipment->save();
        }

        return response()->json(['success' => 1]);
    }

}
