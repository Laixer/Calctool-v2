<?php

class MoreController extends Controller {

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

	public function updateMoreStatus($id)
	{
		$proj = Project::find($id);
		if (!$proj->start_more)
			$proj->start_more = date('Y-m-d');
		$proj->update_more = date('Y-m-d');
		$proj->save();
	}

	public function doNewMoreActivity()
	{
		$rules = array(
			'activity' => array('required','max:50'),
			'project' => array('required','integer'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {

			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$chapter = Chapter::find(Route::Input('chapter_id'));
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return Redirect::back()->withInput(Input::all());
			}

			$part = Part::where('part_name','=','contracting')->first();
			$part_type = PartType::where('type_name','=','calculation')->first();
			$detail = Detail::where('detail_name','=','more')->first();
			$tax = Tax::where('tax_rate','=',21)->first();

			$activity = new Activity;
			$activity->activity_name = Input::get('activity');
			$activity->priority = 0;
			$activity->chapter_id = $chapter->id;
			$activity->part_id = $part->id;
			$activity->part_type_id = $part_type->id;
			$activity->detail_id = $detail->id;
			$activity->tax_calc_labor_id = $tax->id;
			$activity->tax_calc_material_id = $tax->id;
			$activity->tax_calc_equipment_id = $tax->id;
			$activity->tax_more_labor_id = $tax->id;
			$activity->tax_more_material_id = $tax->id;
			$activity->tax_more_equipment_id = $tax->id;
			$activity->tax_estimate_labor_id = $tax->id;
			$activity->tax_estimate_material_id = $tax->id;
			$activity->tax_estimate_equipment_id = $tax->id;

			$activity->save();

			$this->updateMoreStatus(Input::get('project'));

			return Redirect::back()->with('success', 1);

		}
	}

	public function doNewMaterial()
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

			$material = MoreMaterial::create(array(
				"material_name" => Input::get('name'),
				"unit" => Input::get('unit'),
				"rate" => str_replace(',', '.', str_replace('.', '' , Input::get('rate'))),
				"amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => $activity->id,
			));

			$this->updateMoreStatus(Input::get('project'));

			return json_encode(['success' => 1, 'id' => $material->id]);
		}
	}

	public function doNewEquipment()
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

			$equipment = MoreEquipment::create(array(
				"equipment_name" => Input::get('name'),
				"unit" => Input::get('unit'),
				"rate" => str_replace(',', '.', str_replace('.', '' , Input::get('rate'))),
				"amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => $activity->id,
			));

			$this->updateMoreStatus(Input::get('project'));

			return json_encode(['success' => 1, 'id' => $equipment->id]);
		}
	}

	public function doNewLabor()
	{
		$rules = array(
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
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

			$rate = Input::get('rate');
			if (empty($rate)) {
				$_activity = Activity::find(Input::get('activity'));
				$_chapter = Chapter::find($_activity->chapter_id);
				$_project = Project::find($_chapter->project_id);
				$rate = $_project->hour_rate_more;
			} else {
				$rate = str_replace(',', '.', str_replace('.', '' , $rate));
			}
			$labor = MoreLabor::create(array(
				"rate" => $rate,
				"amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => $activity->id,
			));

			$this->updateMoreStatus(Input::get('project'));

			return json_encode(['success' => 1, 'id' => $labor->id]);
		}
	}

	public function doDeleteMaterial()
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

			$rec = MoreMaterial::find(Input::get('id'));
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

			$this->updateMoreStatus(Input::get('project'));

			return json_encode(['success' => 1]);
		}
	}

	public function doDeleteEquipment()
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

			$rec = MoreEquipment::find(Input::get('id'));
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

			$this->updateMoreStatus(Input::get('project'));

			return json_encode(['success' => 1]);
		}
	}

	public function doDeleteLabor()
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

			$rec = MoreEquipment::find(Input::get('id'));
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

			$this->updateMoreStatus(Input::get('project'));

			return json_encode(['success' => 1]);
		}
	}

	public function doUpdateMaterial()
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

			$material = MoreMaterial::find(Input::get('id'));
			if (!$material)
				return json_encode(['success' => 0]);
			$activity = Activity::find($material->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$material->material_name = Input::get('name');
			$material->unit = Input::get('unit');
			$material->rate = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$material->amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			$material->save();

			$this->updateMoreStatus(Input::get('project'));

			return json_encode(['success' => 1]);
		}
	}

	public function doUpdateEquipment()
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

			$equipment = MoreEquipment::find(Input::get('id'));
			if (!$equipment)
				return json_encode(['success' => 0]);
			$activity = Activity::find($equipment->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$equipment->equipment_name = Input::get('name');
			$equipment->unit = Input::get('unit');
			$equipment->rate = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$equipment->amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			$equipment->save();

			$this->updateMoreStatus(Input::get('project'));

			return json_encode(['success' => 1]);
		}
	}

	public function doUpdateLabor()
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

			$labor = MoreLabor::find(Input::get('id'));
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
				$_labor = MoreLabor::find(Input::get('id'));
				$_activity = Activity::find($_labor->activity_id);
				$_chapter = Chapter::find($_activity->chapter_id);
				$_project = Project::find($_chapter->project_id);
				$rate = $_project->hour_rate;
			} else {
				$rate = str_replace(',', '.', str_replace('.', '' , $rate));
			}

			$labor->rate = $rate;
			$labor->amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			$labor->save();

			$this->updateMoreStatus(Input::get('project'));

			return json_encode(['success' => 1]);
		}
	}
}
