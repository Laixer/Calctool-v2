<?php

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
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function updateLessStatus($id)
	{
		$proj = Project::find($id);
		if (!$project || !$project->isOwner())
			return;
		if (!$proj->start_less)
			$proj->start_less = date('Y-m-d');
		$proj->update_less = date('Y-m-d');
		$proj->save();
	}

	public function doUpdateMaterial()
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

			$material = CalculationMaterial::find(Input::get('id'));
			if (!$material)
				return json_encode(['success' => 0]);
			$activity = Activity::find($material->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$rate = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			if ($rate > $material->rate)
				return json_encode(['success' => 0, 'message' => 'rate too large', 'rate' => $material->rate, 'amount' => $material->amount]);

			$material->less_rate = $rate;
			if ($amount > $material->amount)
				return json_encode(['success' => 0, 'message' => 'amount too large', 'rate' => $material->rate, 'amount' => $material->amount]);

			$material->less_amount = $amount;
			$material->isless = True;

			$material->save();

			$this->updateLessStatus(Input::get('project'));

			return json_encode(['success' => 1, 'less_rate' => number_format($material->less_rate, 2,",","."), 'less_amount' => number_format($material->less_amount, 2,",",".")]);
		}
	}

	public function doUpdateEquipment()
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

			$equipment = CalculationEquipment::find(Input::get('id'));
			if (!$equipment)
				return json_encode(['success' => 0]);
			$activity = Activity::find($equipment->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$rate = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			if ($rate > $equipment->rate)
				return json_encode(['success' => 0, 'message' => 'rate too large', 'rate' => $equipment->rate, 'amount' => $equipment->amount]);

			$equipment->less_rate = $rate;
			if ($amount > $equipment->amount)
				return json_encode(['success' => 0, 'message' => 'amount too large', 'rate' => $equipment->rate, 'amount' => $equipment->amount]);

			$equipment->less_amount = $amount;
			$equipment->isless = True;

			$equipment->save();

			$this->updateLessStatus(Input::get('project'));

			return json_encode(['success' => 1, 'less_rate' => number_format($equipment->less_rate, 2,",","."), 'less_amount' => number_format($equipment->less_amount, 2,",",".")]);
		}
	}

	public function doUpdateLabor()
	{
		$rules = array(
			'id' => array('integer','min:0'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'project' => array('required','integer'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$labor = CalculationLabor::find(Input::get('id'));
			if (!$labor)
				return json_encode(['success' => 0]);
			$activity = Activity::find($labor->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));
			if ($amount > $labor->amount)
				return json_encode(['success' => 0, 'message' => 'amount too large', 'amount' => $labor->amount]);

			$labor->less_amount = $amount;
			$labor->isless = True;

			$labor->save();

			$this->updateLessStatus(Input::get('project'));

			return json_encode(['success' => 1, 'less_amount' => number_format($labor->less_amount, 2,",",".")]);
		}
	}

	public function doResetMaterial()
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

			$material = CalculationMaterial::find(Input::get('id'));
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

			$this->updateLessStatus(Input::get('project'));

			return json_encode(['success' => 1, 'rate' => number_format($material->rate, 2,",","."), 'amount' => number_format($material->amount, 2,",",".")]);
		}
	}

	public function doResetEquipment()
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

			$equipment = CalculationEquipment::find(Input::get('id'));
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

			$this->updateLessStatus(Input::get('project'));

			return json_encode(['success' => 1, 'rate' => number_format($equipment->rate, 2,",","."), 'amount' => number_format($equipment->amount, 2,",",".")]);
		}
	}

	public function doResetLabor()
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

			$labor = CalculationLabor::find(Input::get('id'));
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

			$this->updateLessStatus(Input::get('project'));

			return json_encode(['success' => 1, 'amount' => number_format($labor->amount, 2,",",".")]);
		}

	}

}
