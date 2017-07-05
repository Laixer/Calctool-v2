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
use BynqIO\Dynq\Contracts\LevelSink;
use BynqIO\Dynq\Http\Controllers\Controller;
use BynqIO\Dynq\Exceptions\NotImplementedException;
use Illuminate\Http\Request;

//TODO: check every operation with project status & permissions
class LevelController extends Controller implements LevelSink
{
    public function descriptionLevel(Request $request)
    {
        $this->validate($request, [
            'id' => ['required'],
            'description' => ['required'],
        ]);

        $activity = FavoriteActivity::findOrFail($request->get('id'));
        $activity->note = $request->get('description');
        $activity->save();

        return back()->with('success', 'Niveau omschrijving aangepast');
    }

    public function renameLevel(Request $request)
    {
        $this->validate($request, [
            'id' => ['required'],
            'level' => ['required'],
            'name' => ['required', 'max:100'],
        ]);

        $activity = FavoriteActivity::findOrFail($request->get('id'));
        $activity->activity_name = $request->get('name');
        $activity->save();

        return back()->with('success', 'Niveaunaam aangepast');
    }

    public function newLevel(Request $request)
    {
        $this->validate($request, [
            'project' => ['required'],
            'level' => ['required'],
            'name' => ['required', 'max:50'],
        ]);

        $tax = Tax::where('tax_rate', 21)->firstOrFail();

        FavoriteActivity::create([
            'activity_name'    => $request->get('name'),
            'user_id'          => $request->user()->id,
            'tax_labor_id'     => $tax->id,
            'tax_material_id'  => $tax->id,
            'tax_equipment_id' => $tax->id,
        ]);

        return back()->with('success', 'Niveau aangemaakt');
    }

    public function moveLevel(Request $request)
    {
        throw new NotImplementedException;
    }

    public function deleteLevel(Request $request)
    {
        if (csrf_token() != $request->get('csrf')) {
            abort(404);
        }

        FavoriteActivity::findOrFail($request->get('activity'))->delete();

        return back()->with('success', 'Niveau verwijderd');
    }

}
