<?php

namespace Calctool\Http\Controllers;

use Illuminate\Http\Request;

use \Calctool\Models\Project;
use \Calctool\Models\Chapter;
use \Calctool\Models\Activity;
use \Calctool\Models\CalculationEquipment;
use \Calctool\Models\CalculationLabor;
use \Calctool\Models\CalculationMaterial;

class LessController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	*/

	public function updateLessStatus($id)
	{
		$proj = Project::find($id);
		if (!$proj || !$proj->isOwner())
			return;
		if (!$proj->start_less)
			$proj->start_less = date('Y-m-d');
		$proj->update_less = date('Y-m-d');
		$proj->save();
	}

	public function doUpdateMaterial(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'project' => array('required','integer'),
		]);

		$material = CalculationMaterial::find($request->get('id'));
		if (!$material)
			return json_encode(['success' => 0]);
		$activity = Activity::find($material->activity_id);
		if (!$activity)
			return json_encode(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$rate = str_replace(',', '.', str_replace('.', '' , $request->get('rate')));
		$amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));

		if ($rate > $material->rate)
			return json_encode(['success' => 0, 'message' => 'rate too large', 'rate' => $material->rate, 'amount' => $material->amount]);

		$material->less_rate = $rate;
		if ($amount > $material->amount)
			return json_encode(['success' => 0, 'message' => 'amount too large', 'rate' => $material->rate, 'amount' => $material->amount]);

		$material->less_amount = $amount;
		$material->isless = True;

		$material->save();

		$this->updateLessStatus($request->get('project'));

		return json_encode(['success' => 1, 'less_rate' => number_format($material->less_rate, 2,",","."), 'less_amount' => number_format($material->less_amount, 2,",",".")]);
	}

	public function doUpdateEquipment(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'project' => array('required','integer'),
		]);

		$equipment = CalculationEquipment::find($request->get('id'));
		if (!$equipment)
			return json_encode(['success' => 0]);
		$activity = Activity::find($equipment->activity_id);
		if (!$activity)
			return json_encode(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$rate = str_replace(',', '.', str_replace('.', '' , $request->get('rate')));
		$amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));

		if ($rate > $equipment->rate)
			return json_encode(['success' => 0, 'message' => 'rate too large', 'rate' => $equipment->rate, 'amount' => $equipment->amount]);

		$equipment->less_rate = $rate;
		if ($amount > $equipment->amount)
			return json_encode(['success' => 0, 'message' => 'amount too large', 'rate' => $equipment->rate, 'amount' => $equipment->amount]);

		$equipment->less_amount = $amount;
		$equipment->isless = True;

		$equipment->save();

		$this->updateLessStatus($request->get('project'));

		return json_encode(['success' => 1, 'less_rate' => number_format($equipment->less_rate, 2,",","."), 'less_amount' => number_format($equipment->less_amount, 2,",",".")]);
	}

	public function doUpdateLabor(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'project' => array('required','integer'),
		]);

		$labor = CalculationLabor::find($request->get('id'));
		if (!$labor)
			return json_encode(['success' => 0]);
		$activity = Activity::find($labor->activity_id);
		if (!$activity)
			return json_encode(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));
		if ($amount > $labor->amount)
			return json_encode(['success' => 0, 'message' => 'amount too large', 'amount' => $labor->amount]);

		$labor->less_amount = $amount;
		$labor->isless = True;

		$labor->save();

		$this->updateLessStatus($request->get('project'));

		return json_encode(['success' => 1, 'less_amount' => number_format($labor->less_amount, 2,",",".")]);
	}

	public function doResetMaterial(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'project' => array('required','integer'),
		]);

		$material = CalculationMaterial::find($request->get('id'));
		if (!$material)
			return json_encode(['success' => 0]);
		$activity = Activity::find($material->activity_id);
		if (!$activity)
			return json_encode(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$material->less_rate = NULL;
		$material->less_amount = NULL;
		$material->isless = False;

		$material->save();

		$this->updateLessStatus($request->get('project'));

		return json_encode(['success' => 1, 'rate' => number_format($material->rate, 2,",","."), 'amount' => number_format($material->amount, 2,",",".")]);
	}

	public function doResetEquipment(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'project' => array('required','integer'),
		]);

		$equipment = CalculationEquipment::find($request->get('id'));
		if (!$equipment)
			return json_encode(['success' => 0]);
		$activity = Activity::find($equipment->activity_id);
		if (!$activity)
			return json_encode(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$equipment->less_rate = NULL;
		$equipment->less_amount = NULL;
		$equipment->isless = False;

		$equipment->save();

		$this->updateLessStatus($request->get('project'));

		return json_encode(['success' => 1, 'rate' => number_format($equipment->rate, 2,",","."), 'amount' => number_format($equipment->amount, 2,",",".")]);
	}

	public function doResetLabor(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'project' => array('required','integer'),
		]);

		$labor = CalculationLabor::find($request->get('id'));
		if (!$labor)
			return json_encode(['success' => 0]);
		$activity = Activity::find($labor->activity_id);
		if (!$activity)
			return json_encode(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$labor->less_amount = NULL;
		$labor->isless = False;

		$labor->save();

		$this->updateLessStatus($request->get('project'));

		return json_encode(['success' => 1, 'amount' => number_format($labor->amount, 2,",",".")]);

	}

}
