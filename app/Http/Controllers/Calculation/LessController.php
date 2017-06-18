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

use Exception;
use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\Models\Chapter;
use BynqIO\Dynq\Models\Part;
use BynqIO\Dynq\Models\PartType;
use BynqIO\Dynq\Models\Activity;
use BynqIO\Dynq\Models\CalculationLabor;
use BynqIO\Dynq\Models\CalculationMaterial;
use BynqIO\Dynq\Models\CalculationEquipment;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

//TODO: update $proj->update_less = date('Y-m-d');
class LessController extends Controller
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

    protected function updateLaborRow($activity, Array $parameters)
    {
        $chapter = Chapter::findOrFail($activity->chapter_id);
        $project = Project::findOrFail($chapter->project_id);

        $row = CalculationLabor::findOrFail($parameters['id']);
        if ($parameters['amount'] > $row->amount) {
            throw new Exception('not allowed');
        }

        $row->less_amount  = $parameters['amount'];
        $row->isless       = true;
        $row->save();
    }

    protected function updateMaterialRow($activity, Array $parameters)
    {
        $row = CalculationMaterial::findOrFail($parameters['id']);
        if ($parameters['rate'] > $row->rate) {
            throw new Exception('not allowed');
        }

        if ($parameters['amount'] > $row->amount) {
            throw new Exception('not allowed');
        }

        $row->less_rate          = $parameters['rate'];
        $row->less_amount        = $parameters['amount'];
        $row->isless             = true;
        $row->save();
    }

    protected function updateOtherRow($activity, Array $parameters)
    {
        $row = CalculationEquipment::findOrFail($parameters['id']);
        if ($parameters['rate'] > $row->rate) {
            throw new Exception('not allowed');
        }

        if ($parameters['amount'] > $row->amount) {
            throw new Exception('not allowed');
        }

        $row->less_rate           = $parameters['rate'];
        $row->less_amount         = $parameters['amount'];
        $row->isless              = true;
        $row->save();
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'id'        => ['required', 'integer', 'min:0'],
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

    protected function resetLaborRow(Array $parameters)
    {
        $row = CalculationLabor::findOrFail($parameters['id']);
        if (!$row->isless) {
            throw new Exception('not allowed');
        }

        $row->less_amount  = null;
        $row->isless       = false;
        $row->save();

        return $row;
    }

    protected function resetMaterialRow(Array $parameters)
    {
        $row = CalculationMaterial::findOrFail($parameters['id']);
        if (!$row->isless) {
            throw new Exception('not allowed');
        }

        $row->less_rate    = null;
        $row->less_amount  = null;
        $row->isless       = false;
        $row->save();

        return $row;
    }

    protected function resetOtherRow(Array $parameters)
    {
        $row = CalculationEquipment::findOrFail($parameters['id']);
        if (!$row->isless) {
            throw new Exception('not allowed');
        }

        $row->less_rate    = null;
        $row->less_amount  = null;
        $row->isless       = false;
        $row->save();

        return $row;
    }

    public function reset(Request $request)
    {
        $this->validate($request, [
            'id'        => ['required', 'integer', 'min:0'],
            'activity'  => ['required', 'integer', 'min:0'],
            'layer'     => ['required'],
        ]);

        $original = [];

        try {
            switch ($request->get('layer')) {
                case 'labor':
                    $object = $this->resetLaborRow($request->all());
                    $original['rate'] = $object->rate;
                    $original['amount'] = $object->amount;
                    break;
                case 'material':
                    $object = $this->resetMaterialRow($request->all());
                    $original['rate'] = $object->rate;
                    $original['amount'] = $object->amount;
                    break;
                case 'other':
                    $object = $this->resetOtherRow($request->all());
                    $original['rate'] = $object->rate;
                    $original['amount'] = $object->amount;
                    break;
            }
        } catch (Exception $e) {
            return response()->json(['success' => 0]);
        }

        return response()->json(array_merge(['success' => 1], $original));
    }

    //TODO; placed here fow now
    public function asyncSummary($projectid)
    {
        $project = Project::find($projectid);

        return view('component.less.summary', ['project' => $project, 'section' => 'summary', 'filter' => function($section, $builder) {
            return $builder->whereNull('detail_id')
                           ->where('part_type_id', PartType::where('type_name','calculation')->firstOrFail()->id)
                           ->orderBy('priority');
        }]);
    }

    //TODO; placed here fow now
    public function asyncEndresult($projectid)
    {
        $project = Project::find($projectid);
        return view('component.less.endresult', ['project' => $project, 'section' => 'summary']);
    }
}
