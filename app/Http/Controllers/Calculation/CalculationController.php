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

use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\Models\Chapter;
use BynqIO\Dynq\Models\Part;
use BynqIO\Dynq\Models\Activity;
use BynqIO\Dynq\Models\CalculationLabor;
use BynqIO\Dynq\Models\CalculationMaterial;
use BynqIO\Dynq\Models\CalculationEquipment;
use BynqIO\Dynq\Models\EstimateLabor;
use BynqIO\Dynq\Models\EstimateMaterial;
use BynqIO\Dynq\Models\EstimateEquipment;
use BynqIO\Dynq\Calculus\EstimateRegister;
use BynqIO\Dynq\Calculus\CalculationRegister;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CalculationController extends Controller
{
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

    private function profit($layer, $activity, $project) {
        if ($activity->isSubcontracting()) {
            switch ($layer) {
                case 'labor':
                    return 0;
                case 'material':
                    return $project->profit_calc_subcontr_mat;
                case 'other':
                    return $project->profit_calc_subcontr_equip;
            }
        } else {
            switch ($layer) {
                case 'labor':
                    return 0;
                case 'material':
                    return $project->profit_calc_contr_mat;
                case 'other':
                    return $project->profit_calc_contr_equip;
            }
        }
    }

    protected function newLaborRow($activity, Array $parameters)
    {
        $object = null;
        $project = $activity->chapter->project;

        $rate = $project->hour_rate;
        if ($activity->isSubcontracting() && isset($parameters['rate'])) {
            $rate = $parameters['rate'];
        }

        if ($activity->isEstimate()) {
            $object = EstimateLabor::create([
                "rate"            => $rate,
                "amount"          => $parameters['amount'],
                "activity_id"     => $activity->id,
                "original"        => true,
                "isset"           => false,
            ]);
        } else {
            $object = CalculationLabor::create([
                "rate"            => $rate,
                "amount"          => $parameters['amount'],
                "activity_id"     => $activity->id,
            ]);
        }

        return [
            'id'            => $object->id,
            'amount'        => $object->amount * $object->rate,
        ];
    }

    protected function newMaterialRow($activity, Array $parameters)
    {
        $object = null;
        $project = $activity->chapter->project;

        if ($activity->isEstimate()) {
            $object = EstimateMaterial::create([
                "material_name"   => $parameters['name'],
                "unit"            => $parameters['unit'],
                "rate"            => $parameters['rate'],
                "amount"          => $parameters['amount'],
                "activity_id"     => $activity->id,
                "original"        => true,
                "isset"           => false,
            ]);

            return [
                'id'               => $object->id,
                'amount'           => $object->amount * $object->rate,
                'amount_incl'      => $object->amount * $object->rate * (($this->profit('material', $activity, $project) / 100) + 1),
                'total'            => EstimateRegister::materialTotal($activity->id, $this->profit('material', $activity, $project)),
                'total_profit'     => EstimateRegister::materialTotalProfit($activity->id, $this->profit('material', $activity, $project)),
            ];
        } else {
            $object = CalculationMaterial::create([
                "material_name"   => $parameters['name'],
                "unit"            => $parameters['unit'],
                "rate"            => $parameters['rate'],
                "amount"          => $parameters['amount'],
                "activity_id"     => $activity->id,
            ]);

            return [
                'id'               => $object->id,
                'amount'           => $object->amount * $object->rate,
                'amount_incl'      => $object->amount * $object->rate * (($this->profit('material', $activity, $project) / 100) + 1),
                'total'            => CalculationRegister::materialTotal($activity->id, $this->profit('material', $activity, $project)),
                'total_profit'     => CalculationRegister::materialTotalProfit($activity->id, $this->profit('material', $activity, $project)),
            ];
        }
    }

    protected function newOtherRow($activity, Array $parameters)
    {
        $object = null;
        $project = $activity->chapter->project;

        if ($activity->isEstimate()) {
            $object = EstimateEquipment::create([
                "equipment_name" => $parameters['name'],
                "unit"           => $parameters['unit'],
                "rate"           => $parameters['rate'],
                "amount"         => $parameters['amount'],
                "activity_id"    => $activity->id,
                "original"       => true,
                "isset"          => false,
            ]);

            return [
                'id'               => $object->id,
                'amount'           => $object->amount * $object->rate,
                'amount_incl'      => $object->amount * $object->rate * (($this->profit('other', $activity, $project) / 100) + 1),
                'total'            => EstimateRegister::equipmentTotal($activity->id, $this->profit('other', $activity, $project)),
                'total_profit'     => EstimateRegister::equipmentTotalProfit($activity->id, $this->profit('other', $activity, $project)),
            ];
        } else {
            $object = CalculationEquipment::create([
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
                'total'            => CalculationRegister::equipmentTotal($activity->id, $this->profit('other', $activity, $project)),
                'total_profit'     => CalculationRegister::equipmentTotalProfit($activity->id, $this->profit('other', $activity, $project)),
            ];
        }
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
        }

        return response()->json(array_merge(['success' => true], $object));
    }

    protected function updateLaborRow($activity, Array $parameters)
    {
        $project = $activity->chapter->project;

        $rate = $project->hour_rate;
        if ($activity->isSubcontracting() && isset($parameters['rate'])) {
            $rate = $parameters['rate'];
        }

        $row = null;
        if ($activity->isEstimate()) {
            $row = EstimateLabor::findOrFail($parameters['id']);
        } else {
            $row = CalculationLabor::findOrFail($parameters['id']);
        }

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

        $row = null;
        if ($activity->isEstimate()) {
            $row = EstimateMaterial::findOrFail($parameters['id']);
        } else {
            $row = CalculationMaterial::findOrFail($parameters['id']);
        }

        $row->material_name  = $parameters['name'];
        $row->unit           = $parameters['unit'];
        $row->rate           = $parameters['rate'];
        $row->amount         = $parameters['amount'];
        $row->save();

        if ($activity->isEstimate()) {
            return [
                'id'               => $row->id,
                'amount'           => $row->amount * $row->rate,
                'amount_incl'      => $row->amount * $row->rate * (($this->profit('material', $activity, $project) / 100) + 1),
                'total'            => EstimateRegister::materialTotal($activity->id, $this->profit('material', $activity, $project)),
                'total_profit'     => EstimateRegister::materialTotalProfit($activity->id, $this->profit('material', $activity, $project)),
            ];
        } else {
            return [
                'id'               => $row->id,
                'amount'           => $row->amount * $row->rate,
                'amount_incl'      => $row->amount * $row->rate * (($this->profit('material', $activity, $project) / 100) + 1),
                'total'            => CalculationRegister::materialTotal($activity->id, $this->profit('material', $activity, $project)),
                'total_profit'     => CalculationRegister::materialTotalProfit($activity->id, $this->profit('material', $activity, $project)),
            ];
        }
    }

    protected function updateOtherRow($activity, Array $parameters)
    {
        $project = $activity->chapter->project;

        $row = null;
        if ($activity->isEstimate()) {
            $row = EstimateEquipment::findOrFail($parameters['id']);
        } else {
            $row = CalculationEquipment::findOrFail($parameters['id']);
        }

        $row->equipment_name  = $parameters['name'];
        $row->unit            = $parameters['unit'];
        $row->rate            = $parameters['rate'];
        $row->amount          = $parameters['amount'];
        $row->save();

        if ($activity->isEstimate()) {
            return [
                'id'               => $row->id,
                'amount'           => $row->amount * $row->rate,
                'amount_incl'      => $row->amount * $row->rate * (($this->profit('other', $activity, $project) / 100) + 1),
                'total'            => EstimateRegister::equipmentTotal($activity->id, $this->profit('other', $activity, $project)),
                'total_profit'     => EstimateRegister::equipmentTotalProfit($activity->id, $this->profit('other', $activity, $project)),
            ];
        } else {
            return [
                'id'               => $row->id,
                'amount'           => $row->amount * $row->rate,
                'amount_incl'      => $row->amount * $row->rate * (($this->profit('other', $activity, $project) / 100) + 1),
                'total'            => CalculationRegister::equipmentTotal($activity->id, $this->profit('other', $activity, $project)),
                'total_profit'     => CalculationRegister::equipmentTotalProfit($activity->id, $this->profit('other', $activity, $project)),
            ];
        }
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
        }

        return response()->json(array_merge(['success' => true], $object));
    }

    protected function deleteMaterialRow($activity, Array $parameters)
    {
        $project = $activity->chapter->project;

        if ($activity->isEstimate()) {
            EstimateMaterial::findOrFail($parameters['id'])->delete();

            return [
                'total'            => EstimateRegister::materialTotal($activity->id, $this->profit('material', $activity, $project)),
                'total_profit'     => EstimateRegister::materialTotalProfit($activity->id, $this->profit('material', $activity, $project)),
            ];
        } else {
            CalculationMaterial::findOrFail($parameters['id'])->delete();

            return [
                'total'            => CalculationRegister::materialTotal($activity->id, $this->profit('material', $activity, $project)),
                'total_profit'     => CalculationRegister::materialTotalProfit($activity->id, $this->profit('material', $activity, $project)),
            ];
        }
    }

    protected function deleteOtherRow($activity, Array $parameters)
    {
        if ($activity->isEstimate()) {
            EstimateEquipment::findOrFail($parameters['id'])->delete();

            return [
                'total'            => EstimateRegister::equipmentTotal($activity->id, $this->profit('other', $activity, $project)),
                'total_profit'     => EstimateRegister::equipmentTotalProfit($activity->id, $this->profit('other', $activity, $project)),
            ];
        } else {
            CalculationEquipment::findOrFail($parameters['id'])->delete();

            return [
                'total'            => CalculationRegister::equipmentTotal($activity->id, $this->profit('other', $activity, $project)),
                'total_profit'     => CalculationRegister::equipmentTotalProfit($activity->id, $this->profit('other', $activity, $project)),
            ];
        }
    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'id'        => ['required', 'integer', 'min:0'],
            'activity'  => ['required', 'integer', 'min:0'],
            'layer'     => ['required'],
        ]);

        $object = null;

        $activity = Activity::findOrFail($request->get('activity'));

        switch ($request->get('layer')) {
            case 'material':
                $object = $this->deleteMaterialRow($activity, $request->all());
                break;
            case 'other':
                $object = $this->deleteOtherRow($activity, $request->all());
                break;
        }

        return response()->json(array_merge(['success' => true], $object));
    }

    //TODO; placed here fow now
    public function asyncSummary($projectid)
    {
        $project = Project::find($projectid);

        return view('component.calculation.summary', ['project' => $project, 'section' => 'summary', 'filter' => function($section, $builder) {
            return $builder->whereNull('detail_id')
                           ->orderBy('priority');
        }]);
    }

    //TODO; placed here fow now
    public function asyncEndresult($projectid)
    {
        $project = Project::find($projectid);

        return view('component.calculation.endresult', ['project' => $project, 'section' => 'summary']);
    }
}
