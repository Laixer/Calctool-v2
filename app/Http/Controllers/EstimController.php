<?php

namespace Calctool\Http\Controllers;

use \Illuminate\Http\Request;
use \Calctool\Models\Activity;
use \Calctool\Models\Chapter;
use \Calctool\Models\Project;
use \Calctool\Models\EstimateLabor;
use \Calctool\Models\EstimateMaterial;
use \Calctool\Models\EstimateEquipment;


class EstimController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function updateEstimateStatus($id)
	{
		$proj = Project::find($id);
		if (!$proj->start_estimate)
			$proj->start_estimate = date('Y-m-d');
		$proj->update_estimate = date('Y-m-d');
		$proj->save();
	}

	public function doNewEstimateMaterial(Request $request)
	{
		$this->validate($request, [
			'name' => array('required','max:100'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0'),
			'project' => array('required','integer'),
		]);

			$activity = Activity::find($request->get('activity'));
		if (!$activity)
			return json_encode(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$material = EstimateMaterial::create(array(
			"set_material_name" => $request->get('name'),
			"set_unit" => $request->get('unit'),
			"set_rate" => str_replace(',', '.', str_replace('.', '' , $request->get('rate'))),
			"set_amount" => str_replace(',', '.', str_replace('.', '' , $request->get('amount'))),
			"activity_id" => $activity->id,
			"original" => false,
			"isset" => true
		));

		$this->updateEstimateStatus($request->get('project'));

		return json_encode(['success' => 1, 'id' => $material->id]);
	}

	public function doNewEstimateEquipment(Request $request)
	{
		$this->validate($request, [
			'name' => array('required','max:100'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0'),
			'project' => array('required','integer'),
		]);

		$activity = Activity::find($request->get('activity'));
		if (!$activity)
			return json_encode(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$equipment = EstimateEquipment::create(array(
			"set_equipment_name" => $request->get('name'),
			"set_unit" => $request->get('unit'),
			"set_rate" => str_replace(',', '.', str_replace('.', '' , $request->get('rate'))),
			"set_amount" => str_replace(',', '.', str_replace('.', '' , $request->get('amount'))),
			"activity_id" => $activity->id,
			"original" => false,
			"isset" => true
		));

		$this->updateEstimateStatus($request->get('project'));

		return json_encode(['success' => 1, 'id' => $equipment->id]);
	}

	public function doNewEstimateLabor(Request $request)
	{
		$this->validate($request, [
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0'),
			'project' => array('required','integer'),
		]);

		$activity = Activity::find($request->get('activity'));
		if (!$activity)
			return json_encode(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$_activity = Activity::find($request->get('activity'));
		$_chapter = Chapter::find($_activity->chapter_id);
		$_project = Project::find($_chapter->project_id);

		$labor = EstimateLabor::create(array(
			"set_rate" => $_project->hour_rate,
			"set_amount" => str_replace(',', '.', str_replace('.', '' , $request->get('amount'))),
			"activity_id" => $activity->id,
			"original" => false,
			"isset" => true
		));

		$this->updateEstimateStatus($request->get('project'));

		return json_encode(['success' => 1, 'id' => $labor->id]);
	}

	public function doUpdateEstimateMaterial(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'name' => array('max:100'),
			'unit' => array('max:10'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'project' => array('required','integer'),
		]);

		$material = EstimateMaterial::find($request->get('id'));
		if (!$material)
			return json_encode(['success' => 0]);
		$activity = Activity::find($material->activity_id);
		if (!$activity)
			return json_encode(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$material->set_material_name = $request->get('name');
		$material->set_unit = $request->get('unit');
		$material->set_rate = str_replace(',', '.', str_replace('.', '' , $request->get('rate')));
		$material->set_amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));
		$material->isset = true;

		$material->save();

		$this->updateEstimateStatus($request->get('project'));

		return json_encode(['success' => 1]);
	}

	public function doUpdateEstimateEquipment(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'name' => array('max:100'),
			'unit' => array('max:10'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'project' => array('required','integer'),
		]);

			$equipment = EstimateEquipment::find($request->get('id'));
			if (!$equipment)
				return json_encode(['success' => 0]);
			$activity = Activity::find($equipment->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$equipment->set_equipment_name = $request->get('name');
			$equipment->set_unit = $request->get('unit');
			$equipment->set_rate = str_replace(',', '.', str_replace('.', '' , $request->get('rate')));
			$equipment->set_amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));
			$equipment->isset = true;

			$equipment->save();

			$this->updateEstimateStatus($request->get('project'));

			return json_encode(['success' => 1]);
	}

	public function doUpdateEstimateLabor(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'project' => array('required','integer'),
		]);

		$labor = EstimateLabor::find($request->get('id'));
		if (!$labor)
			return json_encode(['success' => 0]);
		$activity = Activity::find($labor->activity_id);
		if (!$activity)
			return json_encode(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$rate = $request->get('rate');
		if (empty($rate)) {
			$_labor = EstimateLabor::find($request->get('id'));
			$_activity = Activity::find($_labor->activity_id);
			$_chapter = Chapter::find($_activity->chapter_id);
			$_project = Project::find($_chapter->project_id);
			$rate = $_project->hour_rate;
		} else {
			$rate = str_replace(',', '.', str_replace('.', '' , $rate));
		}

		$labor->set_rate = $rate;
		$labor->set_amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));
		$labor->isset = true;

		$labor->save();

		$this->updateEstimateStatus($request->get('project'));

		return json_encode(['success' => 1]);
	}

	public function doResetEstimateMaterial(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'project' => array('required','integer'),
		]);

			$material = EstimateMaterial::find($request->get('id'));
			if (!$material)
				return json_encode(['success' => 0]);
			$activity = Activity::find($material->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$material->set_material_name = NULL;
			$material->set_unit =  NULL;
			$material->set_rate =  NULL;
			$material->set_amount =  NULL;
			$material->isset = False;

			$material->save();

			$this->updateEstimateStatus($request->get('project'));

			return json_encode(['success' => 1, 'name' => $material->material_name, 'unit' => $material->unit, 'rate' => number_format($material->rate, 2,",","."), 'amount' => number_format($material->amount, 2,",",".")]);
	}

	public function doResetEstimateEquipment(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'project' => array('required','integer'),
		]);

			$equipment = EstimateEquipment::find($request->get('id'));
			if (!$equipment)
				return json_encode(['success' => 0]);
			$activity = Activity::find($equipment->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$equipment->set_equipment_name = NULL;
			$equipment->set_unit =  NULL;
			$equipment->set_rate =  NULL;
			$equipment->set_amount =  NULL;
			$equipment->isset = False;

			$equipment->save();

			$this->updateEstimateStatus($request->get('project'));

			return json_encode(['success' => 1, 'name' => $equipment->equipment_name, 'unit' => $equipment->unit, 'rate' => number_format($equipment->rate, 2,",","."), 'amount' => number_format($equipment->amount, 2,",",".")]);
	}

	public function doResetEstimateLabor(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'project' => array('required','integer'),
		]);

			$labor = EstimateLabor::find($request->get('id'));
			if (!$labor)
				return json_encode(['success' => 0]);
			$activity = Activity::find($labor->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$labor->set_rate =  NULL;
			$labor->set_amount =  NULL;
			$labor->isset = False;

			$labor->save();

			$this->updateEstimateStatus($request->get('project'));

			return json_encode(['success' => 1, 'rate' => number_format($labor->rate, 2,",","."), 'amount' => number_format($labor->amount, 2,",",".")]);
	}

	public function doDeleteEstimateMaterial(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
			'project' => array('required','integer'),
		]);

			$rec = EstimateMaterial::find($request->get('id'));
			if (!$rec)
				return json_encode(['success' => 0]);
			$activity = Activity::find($rec->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$rec->delete();

			$this->updateEstimateStatus($request->get('project'));

			return json_encode(['success' => 1]);
	}

	public function doDeleteEstimateEquipment(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
			'project' => array('required','integer'),
		]);

			$rec = EstimateEquipment::find($request->get('id'));
			if (!$rec)
				return json_encode(['success' => 0]);
			$activity = Activity::find($rec->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$rec->delete();

			$this->updateEstimateStatus($request->get('project'));

			return json_encode(['success' => 1]);
	}

	public function doDeleteEstimateLabor(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
			'project' => array('required','integer'),
		]);

			$rec = EstimateLabor::find($request->get('id'));
			if (!$rec)
				return json_encode(['success' => 0]);
			$activity = Activity::find($rec->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$rec->delete();

			$this->updateEstimateStatus($request->get('project'));

			return json_encode(['success' => 1]);
	}

}
