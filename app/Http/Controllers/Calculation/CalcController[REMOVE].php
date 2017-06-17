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

use \Illuminate\Http\Request;
use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\Models\Chapter;
use BynqIO\Dynq\Models\Part;
use BynqIO\Dynq\Models\PartType;
use BynqIO\Dynq\Models\ProjectType;
use BynqIO\Dynq\Models\Tax;
use BynqIO\Dynq\Models\Activity;
use BynqIO\Dynq\Models\FavoriteActivity;
use BynqIO\Dynq\Calculus\InvoiceTerm;
use BynqIO\Dynq\Calculus\ResultEndresult;
use BynqIO\Dynq\Calculus\CalculationRegister;
use BynqIO\Dynq\Models\CalculationMaterial;
use BynqIO\Dynq\Models\CalculationEquipment;
use BynqIO\Dynq\Models\CalculationLabor;
use BynqIO\Dynq\Models\FavoriteLabor;
use BynqIO\Dynq\Models\FavoriteMaterial;
use BynqIO\Dynq\Models\FavoriteEquipment;
use BynqIO\Dynq\Models\EstimateLabor;
use BynqIO\Dynq\Models\EstimateMaterial;
use BynqIO\Dynq\Models\EstimateEquipment;
use BynqIO\Dynq\Models\Invoice;
use BynqIO\Dynq\Models\Offer;
use BynqIO\Dynq\Http\Controllers\Controller;

use \Auth;
use \PDF;

class CalcController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Default Home Controlluse BynqIO\Dynq\Models\Invoice;er
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |	Route::get('/', 'HomeController@showWelcome');
    |
    */

    public function getCalculationWithFavorite(Request $request, $projectid, $chapterid, $favid)
    {
        $chapter = Chapter::find($chapterid);
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return back();
        }

        $favact = FavoriteActivity::find($favid);
        if (!$favact || !$favact->isOwner()) {
            return back();
        }

        $part = Part::where('part_name','contracting')->first();
        $part_type = PartType::where('type_name','calculation')->first();
        $project = Project::find($chapter->project_id);

        $last_activity = Activity::where('chapter_id', $chapter->id)->where('part_type_id',$part_type->id)->orderBy('priority','desc')->first();

        $activity = new Activity;
        $activity->activity_name = $favact->activity_name;
        $activity->priority = $last_activity ? $last_activity->priority + 1 : 0;
        $activity->note = $favact->note;
        $activity->chapter_id = $chapter->id;
        $activity->part_id = $part->id;
        $activity->part_type_id = $part_type->id;

        if ($project->tax_reverse) {
            $tax_id = Tax::where('tax_rate','0')->first()['id'];
            $activity->tax_labor_id = $tax_id;
            $activity->tax_material_id = $tax_id;
            $activity->tax_equipment_id = $tax_id;
        } else {
            $activity->tax_labor_id = $favact->tax_labor_id;
            $activity->tax_material_id = $favact->tax_material_id;
            $activity->tax_equipment_id = $favact->tax_equipment_id;
        }

        $activity->save();

        foreach (FavoriteLabor::where('activity_id', $favact->id)->get() as $fav_calc_labor) {
            CalculationLabor::create(array(
                "amount" => $fav_calc_labor->amount,
                "activity_id" => $activity->id,
            ));
        }

        foreach (FavoriteMaterial::where('activity_id', $favact->id)->get() as $fav_calc_material) {
            CalculationMaterial::create(array(
                "material_name" => $fav_calc_material->material_name,
                "unit" => $fav_calc_material->unit,
                "rate" => $fav_calc_material->rate,
                "amount" => $fav_calc_material->amount,
                "activity_id" => $activity->id,
            ));
        }

        if ($project->use_equipment) {
            foreach (FavoriteEquipment::where('activity_id', $favact->id)->get() as $fav_calc_equipment) {
                CalculationEquipment::create(array(
                    "equipment_name" => $fav_calc_equipment->equipment_name,
                    "unit" => $fav_calc_equipment->unit,
                    "rate" => $fav_calc_equipment->rate,
                    "amount" => $fav_calc_equipment->amount,
                    "activity_id" => $activity->id,
                ));
            }
        }

        return back();
    }

    public function getEstimateWithFavorite(Request $request, $projectid, $chapterid, $favid)
    {
        $chapter = Chapter::find($chapterid);
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return back();
        }

        $favact = FavoriteActivity::find($favid);
        if (!$favact || !$favact->isOwner()) {
            return back();
        }

        $part = Part::where('part_name','=','contracting')->first();
        $part_type = PartType::where('type_name','=','estimate')->first();
        $project = Project::find($chapter->project_id);

        $last_activity = Activity::where('chapter_id', $chapter->id)->where('part_type_id',$part_type->id)->orderBy('priority','desc')->first();

        $activity = new Activity;
        $activity->activity_name = $favact->activity_name;
        $activity->priority = $last_activity ? $last_activity->priority : 0;
        $activity->note = $favact->note;
        $activity->chapter_id = $chapter->id;
        $activity->part_id = $part->id;
        $activity->part_type_id = $part_type->id;

        if ($project->tax_reverse) {
            $tax_id = Tax::where('tax_rate','0')->first()['id'];
            $activity->tax_labor_id = $tax_id;
            $activity->tax_material_id = $tax_id;
            $activity->tax_equipment_id = $tax_id;
        } else {
            $activity->tax_labor_id = $favact->tax_labor_id;
            $activity->tax_material_id = $favact->tax_material_id;
            $activity->tax_equipment_id = $favact->tax_equipment_id;
        }

        $activity->save();

        foreach (FavoriteLabor::where('activity_id', $favact->id)->get() as $fav_calc_labor) {
            EstimateLabor::create(array(
                "amount" => $fav_calc_labor->amount,
                "activity_id" => $activity->id,
                "original" => true,
                "isset" => false,
            ));
        }

        foreach (FavoriteMaterial::where('activity_id', $favact->id)->get() as $fav_calc_material) {
            EstimateMaterial::create(array(
                "material_name" => $fav_calc_material->material_name,
                "unit" => $fav_calc_material->unit,
                "rate" => $fav_calc_material->rate,
                "amount" => $fav_calc_material->amount,
                "activity_id" => $activity->id,
                "original" => true,
                "isset" => false,
            ));
        }

        if ($project->use_equipment) {
            foreach (FavoriteEquipment::where('activity_id', $favact->id)->get() as $fav_calc_equipment) {
                EstimateEquipment::create(array(
                    "equipment_name" => $fav_calc_equipment->equipment_name,
                    "unit" => $fav_calc_equipment->unit,
                    "rate" => $fav_calc_equipment->rate,
                    "amount" => $fav_calc_equipment->amount,
                    "activity_id" => $activity->id,
                    "original" => true,
                    "isset" => false,
                ));
            }
        }

        return back();
    }

    public function doNewCalculationFavorite(Request $request)
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

    public function doNewEstimateFavorite(Request $request)
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

        foreach (EstimateLabor::where('activity_id', $activity->id)->get() as $orig_calc_labor) {
            $calc_labor = new FavoriteLabor;
            $calc_labor->rate = $orig_calc_labor->rate;
            $calc_labor->amount = $orig_calc_labor->amount;
            $calc_labor->activity_id = $fav_activity->id;

            $calc_labor->save();
        }
        
        foreach (EstimateMaterial::where('activity_id', $activity->id)->get() as $orig_calc_material) {
            $calc_material = new FavoriteMaterial;
            $calc_material->material_name = $orig_calc_material->material_name;
            $calc_material->unit = $orig_calc_material->unit;
            $calc_material->rate = $orig_calc_material->rate;
            $calc_material->amount = $orig_calc_material->amount;
            $calc_material->activity_id = $fav_activity->id;

            $calc_material->save();
        }

        foreach (EstimateEquipment::where('activity_id', $activity->id)->get() as $orig_calc_equipment) {
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
