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

use BynqIO\Dynq\Models\Chapter;
use BynqIO\Dynq\Models\Activity;
use BynqIO\Dynq\Models\FavoriteActivity;
use BynqIO\Dynq\Models\CalculationLabor;
use BynqIO\Dynq\Models\CalculationMaterial;
use BynqIO\Dynq\Models\CalculationEquipment;
use BynqIO\Dynq\Models\FavoriteLabor;
use BynqIO\Dynq\Models\FavoriteMaterial;
use BynqIO\Dynq\Models\FavoriteEquipment;
use BynqIO\Dynq\Mappers\LayerMapper;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransformController extends Controller
{
    public function toFavorite(Request $request)
    {
        $this->validate($request, [
            'activity' => ['required','integer']
        ]);

        $activity = Activity::findOrFail($request->get('activity'));

        /* Convert activity and commit to persistent storage */
        $new_activity = LayerMapper::mapActivity(new FavoriteActivity, $activity, $request->user());
        $new_activity->save();

        foreach (CalculationLabor::where('activity_id', $activity->id)->get() as $labor) {
            $new_labor = LayerMapper::mapLabor(new FavoriteLabor, $labor, $new_activity);
            $new_labor->save();
        }

        foreach (CalculationMaterial::where('activity_id', $activity->id)->get() as $material) {
            $new_material = LayerMapper::mapMaterial(new FavoriteMaterial, $material, $new_activity);
            $new_material->save();
        }

        foreach (CalculationEquipment::where('activity_id', $activity->id)->get() as $other) {
            $new_other = LayerMapper::mapOther(new FavoriteEquipment, $other, $new_activity);
            $new_other->save();
        }

        return back()->with('success', 'Toegevoegd aan favorieten');
    }

    public function fromFavorite(Request $request)
    {
        $this->validate($request, [
            'activity' => ['required','integer'],
            'chapter'  => ['required','integer'],
        ]);

        $activity = FavoriteActivity::findOrFail($request->get('activity'));
        $chapter = Chapter::findOrFail($request->get('chapter'));

        /* Convert activity and commit to persistent storage */
        $new_activity = LayerMapper::mapActivity(new Activity, $activity, $chapter);
        $new_activity->save();

        foreach (FavoriteLabor::where('activity_id', $activity->id)->get() as $labor) {
            $new_labor = LayerMapper::mapLabor(new CalculationLabor, $labor, $new_activity);
            $new_labor->save();
        }

        foreach (FavoriteMaterial::where('activity_id', $activity->id)->get() as $material) {
            $new_material = LayerMapper::mapMaterial(new CalculationMaterial, $material, $new_activity);
            $new_material->save();
        }

        foreach (FavoriteEquipment::where('activity_id', $activity->id)->get() as $other) {
            $new_other = LayerMapper::mapOther(new CalculationEquipment, $other, $new_activity);
            $new_other->save();
        }

        return back()->with('success', 'Toegevoegd aan project');
    }

}
