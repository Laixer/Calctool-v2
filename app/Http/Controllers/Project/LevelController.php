<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the BynqIO\CalculatieTool.com.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  CalculatieTool
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
 */

namespace BynqIO\CalculatieTool\Http\Controllers\Project;

use BynqIO\CalculatieTool\Models\Project;
use BynqIO\CalculatieTool\Models\Chapter;
use BynqIO\CalculatieTool\Models\Activity;
use BynqIO\CalculatieTool\Models\Tax;
use BynqIO\CalculatieTool\Models\Part;
use BynqIO\CalculatieTool\Models\PartType;
use BynqIO\CalculatieTool\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    protected function newActivity($project, $chapter, $name)
    {
        $part = Part::where('part_name', 'contracting')->firstOrFail();
        $part_type = PartType::where('type_name', 'calculation')->firstOrFail();

        if ($project->tax_reverse) {
            $tax = Tax::where('tax_rate', 0)->firstOrFail();
        } else {
            $tax = Tax::where('tax_rate', 21)->firstOrFail();
        }

        $last_activity = $chapter->activities()->where('chapter_id', $chapter->id)
            ->where('part_type_id', $part_type->id)
            ->orderBy('priority', 'desc')
            ->firstOrFail();

        Activity::create([
            'activity_name'    => $name,
            'priority'         => $last_activity ? $last_activity->priority + 1 : 0,
            'chapter_id'       => $chapter->id,
            'part_id'          => $part->id,
            'part_type_id'     => $part_type->id,
            'tax_labor_id'     => $tax->id,
            'tax_material_id'  => $tax->id,
            'tax_equipment_id' => $tax->id,
        ]);
    }

    protected function newChapter($project, $name)
    {
        $last_chaper = $project->chapters()->where('project_id', $project->id)->orderBy('priority','desc')->first();

        Chapter::create([
            'chapter_name' => $name,
            'priority'     => $last_chaper ? $last_chaper->priority + 1 : 0,
            'project_id'   => $project->id,
        ]);
    }

    public function newLevel(Request $request)
    {
        $this->validate($request, [
            'project' => ['required'],
            'level' => ['required'],
            'name' => ['required', 'max:50'],
        ]);

        $project = Project::findOrFail($request->get('project'));
        if (!$project->isOwner()) {
            return back();
        }

        if ($request->has('chapter')) {
            $chapter = $project->chapters()->findOrFail($request->get('chapter'));
            //TODO:
            // if (!$chapter->isOwner()) {
            //     return back();
            // }
        }

        switch ($request->get('level')) {
            case 1:
                $this->newChapter($project, $request->get('name'));
                break;
            case 2:
                $this->newActivity($project, $chapter, $request->get('name'));
                break;
        }

        return back()->with('success', 'Niveau aangemaakt');
    }

    public function deleteLevel(Request $request)
    {
        if (csrf_token() != $request->get('csrf')) {
            abort(404);
        }

        $activity = Activity::find($request->get('activity'));
        if ($activity) {
            $activity->delete();
        }

        $chapter = Chapter::find($request->get('chapter'));
        if ($chapter) {
            $chapter->delete();
        }

        return back()->with('success', 'Niveau verwijderd');
    }

}
