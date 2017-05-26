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

use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\Models\Chapter;
use BynqIO\Dynq\Models\Activity;
use BynqIO\Dynq\Models\Tax;
use BynqIO\Dynq\Models\Part;
use BynqIO\Dynq\Models\PartType;
use BynqIO\Dynq\Models\Detail;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

//TODO: check every operation with project status & permissions
class LevelController extends Controller
{
    protected function newActivity($project, $chapter, $name, $type, $detail)
    {
        $part = Part::where('part_name', 'contracting')->firstOrFail();
        $part_type = PartType::where('type_name', $type)->firstOrFail();
        $detail_type = null;

        if (isset($detail)) {
            $detail_type = Detail::where('detail_name','more')->firstOrFail();
        }

        if ($project->tax_reverse) {
            $tax = Tax::where('tax_rate', 0)->firstOrFail();
        } else {
            $tax = Tax::where('tax_rate', 21)->firstOrFail();
        }

        $last_activity = $chapter->activities()->where('chapter_id', $chapter->id)
            ->where('part_type_id', $part_type->id)
            ->orderBy('priority', 'desc')
            ->first();

        Activity::create([
            'activity_name'    => $name,
            'priority'         => $last_activity ? $last_activity->priority + 1 : 0,
            'chapter_id'       => $chapter->id,
            'part_id'          => $part->id,
            'part_type_id'     => $part_type->id,
            'detail_id'        => $detail_type ? $detail_type->id : null,
            'tax_labor_id'     => $tax->id,
            'tax_material_id'  => $tax->id,
            'tax_equipment_id' => $tax->id,
        ]);
    }

    protected function newChapter($project, $name)
    {
        $last_chaper = $project->chapters()->orderBy('priority','desc')->first();

        Chapter::create([
            'chapter_name' => $name,
            'priority'     => $last_chaper ? $last_chaper->priority + 1 : 0,
            'project_id'   => $project->id,
        ]);
    }

    protected function renameChapter($id, $name)
    {
        $chapter = Chapter::findOrFail($id);
        $chapter->chapter_name = $name;
        $chapter->save();
    }

    protected function renameActivity($id, $name)
    {
        $activity = Activity::findOrFail($id);
        $activity->activity_name = $name;
        $activity->save();
    }

    public function descriptionLevel(Request $request)
    {
        $this->validate($request, [
            'id' => ['required'],
            'description' => ['required'],
        ]);

        $activity = Activity::findOrFail($request->get('id'));
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

        switch ($request->get('level')) {
            case 1:
                $this->renameChapter($request->get('id'), $request->get('name'));
                break;
            case 2:
                $this->renameActivity($request->get('id'), $request->get('name'));
                break;
        }

        return back()->with('success', 'Niveaunaam aangepast');
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
                $this->newActivity($project, $chapter, $request->get('name'), $request->get('type'), $request->get('detail'));
                break;
        }

        return back()->with('success', 'Niveau aangemaakt');
    }

    protected function enableTimesheet(Activity $activity)
    {
        $activity->use_timesheet = true;
        $activity->save();
    }

    protected function disableTimesheet(Activity $activity)
    {
        $activity->use_timesheet = false;
        $activity->save();
    }

    protected function convertContracting(Activity $activity)
    {
        $activity->part_id = Part::where('part_name','contracting')->firstOrFail()->id;
        $activity->save();
    }

    protected function convertSubcontracting(Activity $activity)
    {
        $activity->part_id = Part::where('part_name','subcontracting')->firstOrFail()->id;
        $activity->save();
    }

    protected function convertEstimate(Activity $activity)
    {
        $activity->part_type_id = PartType::where('type_name', 'estimate')->firstOrFail()->id;
        $activity->save();
    }

    protected function convertCalculation(Activity $activity)
    {
        $activity->part_type_id = PartType::where('type_name', 'calculation')->firstOrFail()->id;
        $activity->save();
    }

    protected function moveChapter($id, $direction)
    {
        $chapter = Chapter::findOrFail($id);
        switch ($direction) {
            case 'up':
                $switch_chapter = Chapter::where('project_id', $chapter->project_id)->where('priority', '<', $chapter->priority)->orderBy('priority','desc')->firstOrFail();

                $old_priority = $chapter->priority;

                $chapter->priority = $switch_chapter->priority;
                $chapter->save();

                $switch_chapter->priority = $old_priority;
                $switch_chapter->save();
                break;
            case 'down':
                $switch_chapter = Chapter::where('project_id', $chapter->project_id)->where('priority', '>', $chapter->priority)->orderBy('priority')->firstOrFail();

                $old_priority = $chapter->priority;

                $chapter->priority = $switch_chapter->priority;
                $chapter->save();

                $switch_chapter->priority = $old_priority;
                $switch_chapter->save();
                break;
        }
    }

    protected function moveActivity($id, $direction)
    {
        $activity = Activity::findOrFail($id);
        $chapter = Chapter::findOrFail($activity->chapter_id);
        switch ($direction) {
            case 'up':
                $switch_activity = Activity::where('chapter_id', $chapter->id)->where('priority', '<', $activity->priority)->whereNull('detail_id')->orderBy('priority','desc')->firstOrFail();

                $old_priority = $activity->priority;
                $activity->priority = $switch_activity->priority;
                $activity->save();

                $switch_activity->priority = $old_priority;
                $switch_activity->save();
                break;
            case 'down':
                $switch_activity = Activity::where('chapter_id', $chapter->id)->where('priority', '>', $activity->priority)->whereNull('detail_id')->orderBy('priority')->firstOrFail();

                $old_priority = $activity->priority;
                $activity->priority = $switch_activity->priority;
                $activity->save();

                $switch_activity->priority = $old_priority;
                $switch_activity->save();
                break;
        }
    }

    public function moveLevel(Request $request)
    {
        if (csrf_token() != $request->get('csrf')) {
            abort(404);
        }

        switch ($request->get('level')) {
            case 1:
                $this->moveChapter($request->get('id'), $request->input('direction'));
                break;
            case 2:
                $this->moveActivity($request->get('id'), $request->input('direction'));
                break;
        }

        return back()->with('success', 'Niveau bijgewerkt');
    }

    public function setOption(Request $request)
    {
        if (csrf_token() != $request->get('csrf')) {
            abort(404);
        }

        $activity = Activity::findOrFail($request->get('activity'));
        $chapter = Chapter::findOrFail($activity->chapter_id);
        $project = Project::findOrFail($chapter->project_id);
        switch ($request->get('action')) {
            case 'enable_timesheet':
                $this->enableTimesheet($activity);
                break;
            case 'disable_timesheet':
                $this->disableTimesheet($activity);
                break;
            case 'convert_contracting':
                $this->convertContracting($activity);
                break;
            case 'convert_subcontracting':
                $this->convertSubcontracting($activity);
                $project->use_subcontract = true;
                $project->save();
                break;
            case 'convert_estimate':
                $this->convertEstimate($activity);
                $project->use_estimate = true;
                $project->save();
                break;
            case 'convert_calculation':
                $this->convertCalculation($activity);
                break;
            default:
                abort(404);
        }

        return back()->with('success', 'Niveau bijgewerkt');
    }

    //TODO: add level
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
