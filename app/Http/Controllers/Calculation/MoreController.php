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

use Illuminate\Http\Request;
use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\Models\Chapter;
use BynqIO\Dynq\Models\Part;
use BynqIO\Dynq\Models\Detail;
use BynqIO\Dynq\Models\PartType;
use BynqIO\Dynq\Models\Activity;
use BynqIO\Dynq\Models\MoreLabor;
use BynqIO\Dynq\Models\MoreMaterial;
use BynqIO\Dynq\Models\MoreEquipment;
use BynqIO\Dynq\Http\Controllers\Controller;

class MoreController extends Controller
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

    protected function newLaborRow($activity, Array $parameters)
    {
        $chapter = Chapter::findOrFail($activity->chapter_id);
        $project = Project::findOrFail($chapter->project_id);

        $rate = $project->hour_rate_more;
        if ($activity->isSubcontracting() && isset($parameters['rate'])) {
            $rate = $parameters['rate'];
        }

        return MoreLabor::create([
            "rate"            => $rate,
            "amount"          => $parameters['amount'],
            "activity_id"     => $activity->id,
        ]);
    }

    protected function newMaterialRow($activity, Array $parameters)
    {
        return MoreMaterial::create([
            "material_name"   => $parameters['name'],
            "unit"            => $parameters['unit'],
            "rate"            => $parameters['rate'],
            "amount"          => $parameters['amount'],
            "activity_id"     => $activity->id,
        ]);

        $this->updateMoreStatus($request->get('project'));
    }

    protected function newOtherRow($activity, Array $parameters)
    {
        return MoreEquipment::create([
            "equipment_name" => $parameters['name'],
            "unit"           => $parameters['unit'],
            "rate"           => $parameters['rate'],
            "amount"         => $parameters['amount'],
            "activity_id"    => $activity->id,
        ]);
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

        $id = null;

        $activity = Activity::findOrFail($request->get('activity'));

        switch ($request->get('layer')) {
            case 'labor':
                $id = $this->newLaborRow($activity, $request->all())->id;
                break;
            case 'material':
                $id = $this->newMaterialRow($activity, $request->all())->id;
                break;
            case 'other':
                $id = $this->newOtherRow($activity, $request->all())->id;
                break;
        }

        return response()->json(['success' => 1, 'id' => $id]);
    }

    protected function updateLaborRow($activity, Array $parameters)
    {
        $chapter = Chapter::findOrFail($activity->chapter_id);
        $project = Project::findOrFail($chapter->project_id);

        $rate = $project->hour_rate_more;
        if ($activity->isSubcontracting() && isset($parameters['rate'])) {
            $rate = $parameters['rate'];
        }

        $row = MoreLabor::findOrFail($parameters['id']);
        $row->rate           = $rate;
        $row->amount         = $parameters['amount'];
        $row->save();
    }

    protected function updateMaterialRow($activity, Array $parameters)
    {
        $row = MoreMaterial::findOrFail($parameters['id']);
        $row->material_name  = $parameters['name'];
        $row->unit           = $parameters['unit'];
        $row->rate           = $parameters['rate'];
        $row->amount         = $parameters['amount'];
        $row->save();
    }

    protected function updateOtherRow($activity, Array $parameters)
    {
        $row = MoreEquipment::findOrFail($parameters['id']);
        $row->equipment_name  = $parameters['name'];
        $row->unit            = $parameters['unit'];
        $row->rate            = $parameters['rate'];
        $row->amount          = $parameters['amount'];
        $row->save();
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'id'        => ['required', 'integer', 'min:0'],
            'name'      => ['required_unless:layer,labor', 'max:100'],
            'unit'      => ['required_unless:layer,labor', 'max:10'],
            'rate'      => ['required_unless:layer,labor', 'numeric'],
            'amount'    => ['required', 'numeric'],
            'activity'  => ['required', 'integer', 'min:0'],
            'layer'     => ['required'],
        ]);

        $activity = Activity::findOrFail($request->get('activity'));

        switch ($request->get('layer')) {
            case 'labor':
                $this->updateLaborRow($activity, $request->all());
                break;
            case 'material':
                $this->updateMaterialRow($activity, $request->all());
                break;
            case 'other':
                $this->updateOtherRow($activity, $request->all());
                break;
        }

        return response()->json(['success' => 1]);
    }

    // protected function deleteLaborRow(Array $parameters)
    // {
    //     CalculationLabor::findOrFail($parameters['id'])->delete();
    // }

    protected function deleteMaterialRow(Array $parameters)
    {
        $row = MoreMaterial::findOrFail($parameters['id'])->delete();
    }

    protected function deleteOtherRow(Array $parameters)
    {
        $row = MoreEquipment::findOrFail($parameters['id'])->delete();
    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'id'        => ['required', 'integer', 'min:0'],
            'activity'  => ['required', 'integer', 'min:0'],
            'layer'     => ['required'],
        ]);

        switch ($request->get('layer')) {
            // case 'labor':
            //     $id = $this->deleteLaborRow($request->all());
            //     break;
            case 'material':
                $id = $this->deleteMaterialRow($request->all());
                break;
            case 'other':
                $id = $this->deleteOtherRow($request->all());
                break;
        }

        return response()->json(['success' => 1]);
    }

    //TODO; placed here fow now
    public function asyncSummary($projectid)
    {
        $project = Project::find($projectid);

        return view('component.more.summary', ['project' => $project, 'section' => 'summary', 'filter' => function($section, $builder) {
            return $builder->where('detail_id', Detail::where('detail_name','more')->firstOrFail()->id)
                           ->where('part_type_id', PartType::where('type_name','calculation')->firstOrFail()->id)
                           ->orderBy('priority');
        }]);
    }

    //TODO; placed here fow now
    public function asyncEndresult($projectid)
    {
        $project = Project::find($projectid);
        return view('component.more.endresult', ['project' => $project, 'section' => 'summary']);
    }
}
