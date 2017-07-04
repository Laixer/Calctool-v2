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
use BynqIO\Dynq\Models\FavoriteActivity;
use BynqIO\Dynq\Models\FavoriteLabor;
use BynqIO\Dynq\Models\FavoriteMaterial;
use BynqIO\Dynq\Models\FavoriteEquipment;
use BynqIO\Dynq\Calculus\MoreRegister;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavoriteController extends Controller
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
        $object = null;
        $rate = 0;

        $object = FavoriteLabor::create([
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

        $object = FavoriteMaterial::create([
            "material_name"   => $parameters['name'],
            "unit"            => $parameters['unit'],
            "rate"            => $parameters['rate'],
            "amount"          => $parameters['amount'],
            "activity_id"     => $activity->id,
        ]);

        return [
            'id'               => $object->id,
            'amount'           => $object->amount * $object->rate,
            'amount_incl'      => 0,//$object->amount * $object->rate * (($this->profit('material', $activity, $project) / 100) + 1),
            // 'total'            => MoreRegister::materialTotal($activity->id, $this->profit('material', $activity, $project)),
            // 'total_profit'     => MoreRegister::materialTotalProfit($activity->id, $this->profit('material', $activity, $project)),
        ];
    }

    protected function newOtherRow($activity, Array $parameters)
    {
        $object = null;
        // $project = $activity->chapter->project;

        $object = FavoriteEquipment::create([
            "equipment_name" => $parameters['name'],
            "unit"           => $parameters['unit'],
            "rate"           => $parameters['rate'],
            "amount"         => $parameters['amount'],
            "activity_id"    => $activity->id,
        ]);

        return [
            'id'               => $object->id,
            'amount'           => $object->amount * $object->rate,
            'amount_incl'      => 0,//$object->amount * $object->rate * (($this->profit('other', $activity, $project) / 100) + 1),
            // 'total'            => MoreRegister::equipmentTotal($activity->id, $this->profit('other', $activity, $project)),
            // 'total_profit'     => MoreRegister::equipmentTotalProfit($activity->id, $this->profit('other', $activity, $project)),
        ];
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

        $activity = FavoriteActivity::findOrFail($request->get('activity'));

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
        // $project = $activity->chapter->project;

        $rate = 0;//$project->hour_rate_more;

        $row = FavoriteLabor::findOrFail($parameters['id']);
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
        // $project = $activity->chapter->project;

        $row = FavoriteMaterial::findOrFail($parameters['id']);
        $row->material_name  = $parameters['name'];
        $row->unit           = $parameters['unit'];
        $row->rate           = $parameters['rate'];
        $row->amount         = $parameters['amount'];
        $row->save();

        return [
            'id'               => $row->id,
            'amount'           => $row->amount * $row->rate,
            'amount_incl'      => 0,//$row->amount * $row->rate * (($this->profit('material', $activity, $project) / 100) + 1),
            // 'total'            => MoreRegister::materialTotal($activity->id, $this->profit('material', $activity, $project)),
            // 'total_profit'     => MoreRegister::materialTotalProfit($activity->id, $this->profit('material', $activity, $project)),
        ];
    }

    protected function updateOtherRow($activity, Array $parameters)
    {
        // $project = $activity->chapter->project;

        $row = FavoriteEquipment::findOrFail($parameters['id']);
        $row->equipment_name  = $parameters['name'];
        $row->unit            = $parameters['unit'];
        $row->rate            = $parameters['rate'];
        $row->amount          = $parameters['amount'];
        $row->save();

        return [
            'id'               => $row->id,
            'amount'           => $row->amount * $row->rate,
            'amount_incl'      => 0,//$row->amount * $row->rate * (($this->profit('other', $activity, $project) / 100) + 1),
            // 'total'            => MoreRegister::equipmentTotal($activity->id, $this->profit('other', $activity, $project)),
            // 'total_profit'     => MoreRegister::equipmentTotalProfit($activity->id, $this->profit('other', $activity, $project)),
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

        $activity = FavoriteActivity::findOrFail($request->get('activity'));

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

    protected function deleteMaterialRow(Array $parameters)
    {
        $row = FavoriteMaterial::findOrFail($parameters['id'])->delete();
    }

    protected function deleteOtherRow(Array $parameters)
    {
        $row = FavoriteMoreEquipment::findOrFail($parameters['id'])->delete();
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
        }

        return response()->json(['success' => 1]);
    }
}
