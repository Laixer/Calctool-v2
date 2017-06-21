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

use Carbon\Carbon;
use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\Models\Chapter;
use BynqIO\Dynq\Models\Part;
use BynqIO\Dynq\Models\Detail;
use BynqIO\Dynq\Models\PartType;
use BynqIO\Dynq\Models\Activity;
use BynqIO\Dynq\Models\Timesheet;
use BynqIO\Dynq\Models\MoreLabor;
use BynqIO\Dynq\Models\MoreMaterial;
use BynqIO\Dynq\Models\MoreEquipment;
use BynqIO\Dynq\Calculus\MoreRegister;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

    protected function newLaborRow($activity, Array $parameters)
    {
        $object = null;
        $project = $activity->chapter->project;

        $rate = $project->hour_rate_more;
        if ($activity->isSubcontracting() && isset($parameters['rate'])) {
            $rate = $parameters['rate'];
        }

        $object = MoreLabor::create([
            "rate"            => $rate,
            "amount"          => $parameters['amount'],
            "activity_id"     => $activity->id,
        ]);

        return [
            'id'            => $object->id,
            'amount'        => $object->amount * $object->rate,
        ];
    }

    protected function newMaterialRow($activity, Array $parameters)
    {
        $object = null;
        $project = $activity->chapter->project;

        $object = MoreMaterial::create([
            "material_name"   => $parameters['name'],
            "unit"            => $parameters['unit'],
            "rate"            => $parameters['rate'],
            "amount"          => $parameters['amount'],
            "activity_id"     => $activity->id,
        ]);

        // $this->updateMoreStatus($request->get('project'));

        return [
            'id'               => $object->id,
            'amount'           => $object->amount * $object->rate,
            'amount_incl'      => $object->amount * $object->rate * (($this->profit('material', $activity, $project) / 100) + 1),
            'total'            => MoreRegister::materialTotal($activity->id, $this->profit('material', $activity, $project)),
            'total_profit'     => MoreRegister::materialTotalProfit($activity->id, $this->profit('material', $activity, $project)),
        ];
    }

    protected function newOtherRow($activity, Array $parameters)
    {
        $object = null;
        $project = $activity->chapter->project;

        $object = MoreEquipment::create([
            "equipment_name" => $parameters['name'],
            "unit"           => $parameters['unit'],
            "rate"           => $parameters['rate'],
            "amount"         => $parameters['amount'],
            "activity_id"    => $activity->id,
        ]);

        return [
            'id'               => $object->id,
            'amount'           => $object->amount * $object->rate,
            'amount_incl'      => $object->amount * $object->rate * (($this->profit('other', $activity, $project) / 100) + 1),
            'total'            => MoreRegister::equipmentTotal($activity->id, $this->profit('other', $activity, $project)),
            'total_profit'     => MoreRegister::equipmentTotalProfit($activity->id, $this->profit('other', $activity, $project)),
        ];
    }

    protected function newTimesheetRow($activity, Array $parameters)
    {
        $object = null;
        $project = $activity->chapter->project;

        $timesheet = Timesheet::create([
            'register_date'      => Carbon::parse($parameters['date']),
            'register_hour'      => $parameters['amount'],
            'note'               => $parameters['name'],
            'activity_id'        => $activity->id,
            'timesheet_kind_id'  => 3
        ]);

        $object = MoreLabor::create([
            "rate"         => $project->hour_rate,
            "amount"       => $parameters['amount'],
            "hour_id"      => $timesheet->id,
            "activity_id"  => $activity->id,
        ]);

        return [
            'id'            => $object->id,
            'amount'        => $object->amount * $object->rate,
        ];
    }

    public function new(Request $request)
    {
        $this->validate($request, [
            'name'      => ['required_unless:layer,labor', 'max:100'],
            'unit'      => ['required_unless:layer,labor,timesheet', 'max:10'],
            'rate'      => ['required_unless:layer,labor,timesheet', 'numeric'],
            'date'      => ['required_if:layer,timesheet'],
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
            case 'timesheet':
                $object = $this->newTimesheetRow($activity, $request->all());
                break;
        }

        return response()->json(array_merge(['success' => true], $object));
    }

    protected function updateLaborRow($activity, Array $parameters)
    {
        $project = $activity->chapter->project;

        $rate = $project->hour_rate_more;
        if ($activity->isSubcontracting() && isset($parameters['rate'])) {
            $rate = $parameters['rate'];
        }

        $row = MoreLabor::findOrFail($parameters['id']);
        $row->rate           = $rate;
        $row->amount         = $parameters['amount'];
        $row->save();

        return [
            'id'            => $row->id,
            'amount'        => $row->amount * $row->rate,
        ];
    }

    protected function updateMaterialRow($activity, Array $parameters)
    {
        $project = $activity->chapter->project;

        $row = MoreMaterial::findOrFail($parameters['id']);
        $row->material_name  = $parameters['name'];
        $row->unit           = $parameters['unit'];
        $row->rate           = $parameters['rate'];
        $row->amount         = $parameters['amount'];
        $row->save();

        return [
            'id'               => $row->id,
            'amount'           => $row->amount * $row->rate,
            'amount_incl'      => $row->amount * $row->rate * (($this->profit('material', $activity, $project) / 100) + 1),
            'total'            => MoreRegister::materialTotal($activity->id, $this->profit('material', $activity, $project)),
            'total_profit'     => MoreRegister::materialTotalProfit($activity->id, $this->profit('material', $activity, $project)),
        ];
    }

    protected function updateOtherRow($activity, Array $parameters)
    {
        $project = $activity->chapter->project;

        $row = MoreEquipment::findOrFail($parameters['id']);
        $row->equipment_name  = $parameters['name'];
        $row->unit            = $parameters['unit'];
        $row->rate            = $parameters['rate'];
        $row->amount          = $parameters['amount'];
        $row->save();

        return [
            'id'               => $row->id,
            'amount'           => $row->amount * $row->rate,
            'amount_incl'      => $row->amount * $row->rate * (($this->profit('other', $activity, $project) / 100) + 1),
            'total'            => MoreRegister::equipmentTotal($activity->id, $this->profit('other', $activity, $project)),
            'total_profit'     => MoreRegister::equipmentTotalProfit($activity->id, $this->profit('other', $activity, $project)),
        ];
    }

    protected function updateTimesheetRow($activity, Array $parameters)
    {
        $project = $activity->chapter->project;

        $rate = $project->hour_rate_more;
        if ($activity->isSubcontracting() && isset($parameters['rate'])) {
            $rate = $parameters['rate'];
        }

        $row = MoreLabor::findOrFail($parameters['id']);
        $row->rate           = $rate;
        $row->amount         = $parameters['amount'];
        $row->save();

        $timesheet = Timesheet::findOrFail($row->hour_id);
        $timesheet->register_date  = Carbon::parse($parameters['date']);
        $timesheet->register_hour  = $parameters['amount'];
        $timesheet->note           = $parameters['name'];
        $timesheet->save();

        return [
            'id'            => $row->id,
            'amount'        => $row->amount * $row->rate,
        ];
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

        $object = null;

        $activity = Activity::findOrFail($request->get('activity'));

        switch ($request->get('layer')) {
            case 'labor':
                $object = $this->updateLaborRow($activity, $request->all());
                break;
            case 'material':
                $object = $this->updateMaterialRow($activity, $request->all());
                break;
            case 'other':
                $object = $this->updateOtherRow($activity, $request->all());
                break;
            case 'timesheet':
                $object = $this->updateTimesheetRow($activity, $request->all());
                break;
        }

        return response()->json(array_merge(['success' => true], $object));
    }

    protected function deleteMaterialRow(Array $parameters)
    {
        $row = MoreMaterial::findOrFail($parameters['id'])->delete();
    }

    protected function deleteOtherRow(Array $parameters)
    {
        $row = MoreEquipment::findOrFail($parameters['id'])->delete();
    }

    protected function deleteTimesheetRow(Array $parameters)
    {
        $row = MoreLabor::findOrFail($parameters['id']);
        $timesheet = Timesheet::findOrFail($row->hour_id);
        $timesheet->delete();
        $row->delete();
    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'id'        => ['required', 'integer', 'min:0'],
            'activity'  => ['required', 'integer', 'min:0'],
            'layer'     => ['required'],
        ]);

        switch ($request->get('layer')) {
            case 'material':
                $id = $this->deleteMaterialRow($request->all());
                break;
            case 'other':
                $id = $this->deleteOtherRow($request->all());
                break;
            case 'timesheet':
                $id = $this->deleteTimesheetRow($request->all());
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
