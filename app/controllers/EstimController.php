<?php

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

	public function doNewEstimateMaterial()
	{
		$rules = array(
			'name' => array('required','max:50'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0'),
			'project' => array('required','integer'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$activity = Activity::find(Input::get('activity'));
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$material = EstimateMaterial::create(array(
				"set_material_name" => Input::get('name'),
				"set_unit" => Input::get('unit'),
				"set_rate" => str_replace(',', '.', str_replace('.', '' , Input::get('rate'))),
				"set_amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => $activity->id,
				"original" => false,
				"isset" => true
			));

			$this->updateEstimateStatus(Input::get('project'));

			return json_encode(['success' => 1, 'id' => $material->id]);
		}
	}

	public function doNewEstimateEquipment()
	{
		$rules = array(
			'name' => array('required','max:50'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0'),
			'project' => array('required','integer'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$activity = Activity::find(Input::get('activity'));
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$equipment = EstimateEquipment::create(array(
				"set_equipment_name" => Input::get('name'),
				"set_unit" => Input::get('unit'),
				"set_rate" => str_replace(',', '.', str_replace('.', '' , Input::get('rate'))),
				"set_amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => $activity->id,
				"original" => false,
				"isset" => true
			));

			$this->updateEstimateStatus(Input::get('project'));

			return json_encode(['success' => 1, 'id' => $equipment->id]);
		}
	}

	public function doNewEstimateLabor()
	{
		$rules = array(
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0'),
			'project' => array('required','integer'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$activity = Activity::find(Input::get('activity'));
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$_activity = Activity::find(Input::get('activity'));
			$_chapter = Chapter::find($_activity->chapter_id);
			$_project = Project::find($_chapter->project_id);

			$labor = EstimateLabor::create(array(
				"set_rate" => $_project->hour_rate,
				"set_amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => $activity->id,
				"original" => false,
				"isset" => true
			));

			$this->updateEstimateStatus(Input::get('project'));

			return json_encode(['success' => 1, 'id' => $labor->id]);
		}
	}

	public function doUpdateEstimateMaterial()
	{
		$rules = array(
			'id' => array('integer','min:0'),
			'name' => array('max:50'),
			'unit' => array('max:10'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'project' => array('required','integer'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$material = EstimateMaterial::find(Input::get('id'));
			if (!$material)
				return json_encode(['success' => 0]);
			$activity = Activity::find($material->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$material->set_material_name = Input::get('name');
			$material->set_unit = Input::get('unit');
			$material->set_rate = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$material->set_amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));
			$material->isset = true;

			$material->save();

			$this->updateEstimateStatus(Input::get('project'));

			return json_encode(['success' => 1]);
		}
	}

	public function doUpdateEstimateEquipment()
	{
		$rules = array(
			'id' => array('integer','min:0'),
			'name' => array('max:50'),
			'unit' => array('max:10'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'project' => array('required','integer'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$equipment = EstimateEquipment::find(Input::get('id'));
			if (!$equipment)
				return json_encode(['success' => 0]);
			$activity = Activity::find($equipment->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$equipment->set_equipment_name = Input::get('name');
			$equipment->set_unit = Input::get('unit');
			$equipment->set_rate = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$equipment->set_amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));
			$equipment->isset = true;

			$equipment->save();

			$this->updateEstimateStatus(Input::get('project'));

			return json_encode(['success' => 1]);
		}
	}

	public function doUpdateEstimateLabor()
	{
		$rules = array(
			'id' => array('integer','min:0'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'project' => array('required','integer'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$labor = EstimateLabor::find(Input::get('id'));
			if (!$labor)
				return json_encode(['success' => 0]);
			$activity = Activity::find($labor->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$rate = Input::get('rate');
			if (empty($rate)) {
				$_labor = EstimateLabor::find(Input::get('id'));
				$_activity = Activity::find($_labor->activity_id);
				$_chapter = Chapter::find($_activity->chapter_id);
				$_project = Project::find($_chapter->project_id);
				$rate = $_project->hour_rate;
			} else {
				$rate = str_replace(',', '.', str_replace('.', '' , $rate));
			}

			$labor->set_rate = $rate;
			$labor->set_amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));
			$labor->isset = true;

			$labor->save();

			$this->updateEstimateStatus(Input::get('project'));

			return json_encode(['success' => 1]);
		}
	}

	public function doResetEstimateMaterial()
	{
		$rules = array(
			'id' => array('integer','min:0'),
			'project' => array('required','integer'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$material = EstimateMaterial::find(Input::get('id'));
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

			$this->updateEstimateStatus(Input::get('project'));

			return json_encode(['success' => 1, 'name' => $material->material_name, 'unit' => $material->unit, 'rate' => number_format($material->rate, 2,",","."), 'amount' => number_format($material->amount, 2,",",".")]);
		}
	}

	public function doResetEstimateEquipment()
	{
		$rules = array(
			'id' => array('integer','min:0'),
			'project' => array('required','integer'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$equipment = EstimateEquipment::find(Input::get('id'));
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

			$this->updateEstimateStatus(Input::get('project'));

			return json_encode(['success' => 1, 'name' => $equipment->equipment_name, 'unit' => $equipment->unit, 'rate' => number_format($equipment->rate, 2,",","."), 'amount' => number_format($equipment->amount, 2,",",".")]);
		}
	}

	public function doResetEstimateLabor()
	{
		$rules = array(
			'id' => array('integer','min:0'),
			'project' => array('required','integer'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$labor = EstimateLabor::find(Input::get('id'));
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

			$this->updateEstimateStatus(Input::get('project'));

			return json_encode(['success' => 1, 'rate' => number_format($labor->rate, 2,",","."), 'amount' => number_format($labor->amount, 2,",",".")]);
		}

	}

	public function doDeleteEstimateMaterial()
	{
		$rules = array(
			'id' => array('required','integer','min:0'),
			'project' => array('required','integer'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$rec = EstimateMaterial::find(Input::get('id'));
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

			$this->updateEstimateStatus(Input::get('project'));

			return json_encode(['success' => 1]);
		}
	}

	public function doDeleteEstimateEquipment()
	{
		$rules = array(
			'id' => array('required','integer','min:0'),
			'project' => array('required','integer'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$rec = EstimateEquipment::find(Input::get('id'));
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

			$this->updateEstimateStatus(Input::get('project'));

			return json_encode(['success' => 1]);
		}
	}

	public function doDeleteEstimateLabor()
	{
		$rules = array(
			'id' => array('required','integer','min:0'),
			'project' => array('required','integer'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$rec = EstimateLabor::find(Input::get('id'));
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

			$this->updateEstimateStatus(Input::get('project'));

			return json_encode(['success' => 1]);
		}
	}

}
