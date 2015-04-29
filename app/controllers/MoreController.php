<?php

class MoreController extends BaseController {

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

	public function doNewMoreActivity()
	{
		$rules = array(
			'activity' => array('required','max:50'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {

			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$part = Part::where('part_name','=','contracting')->first();
			$part_type = PartType::where('type_name','=','calculation')->first();
			$detail = Detail::where('detail_name','=','more')->first();
			$tax = Tax::where('tax_rate','=',21)->first();

			$activity = new Activity;
			$activity->activity_name = Input::get('activity');
			$activity->priority = 0;
			$activity->chapter_id = Route::Input('chapter_id');
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
			'activity' => array('required','integer','min:0')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			$material = MoreMaterial::create(array(
				"material_name" => Input::get('name'),
				"unit" => Input::get('unit'),
				"rate" => str_replace(',', '.', str_replace('.', '' , Input::get('rate'))),
				"amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => Input::get('activity'),
			));

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
			'activity' => array('required','integer','min:0')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			$equipment = MoreEquipment::create(array(
				"equipment_name" => Input::get('name'),
				"unit" => Input::get('unit'),
				"rate" => str_replace(',', '.', str_replace('.', '' , Input::get('rate'))),
				"amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => Input::get('activity'),
			));

			return json_encode(['success' => 1, 'id' => $equipment->id]);
		}
	}

	public function doNewLabor()
	{
		$rules = array(
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			$rate = Input::get('rate');
			if (empty($rate)) {
				$rate = Project::where('user_id','=', Auth::user()->id)->first()->hour_rate_more;
			} else {
				$rate = str_replace(',', '.', str_replace('.', '' , $rate));
			}
			$labor = MoreLabor::create(array(
				"rate" => $rate,
				"amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => Input::get('activity'),
			));

			return json_encode(['success' => 1, 'id' => $labor->id]);
		}
	}

	public function doDeleteMaterial()
	{
		$rules = array(
			'id' => array('required','integer','min:0'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			MoreMaterial::destroy(Input::get('id'));

			return json_encode(['success' => 1]);
		}
	}

	public function doDeleteEquipment()
	{
		$rules = array(
			'id' => array('required','integer','min:0'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			MoreEquipment::destroy(Input::get('id'));

			return json_encode(['success' => 1]);
		}
	}

	public function doDeleteLabor()
	{
		$rules = array(
			'id' => array('required','integer','min:0'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			MoreLabor::destroy(Input::get('id'));

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
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$material = MoreMaterial::find(Input::get('id'));
			$material->material_name = Input::get('name');
			$material->unit = Input::get('unit');
			$material->rate = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$material->amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			$material->save();

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
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$equipment = MoreEquipment::find(Input::get('id'));
			$equipment->equipment_name = Input::get('name');
			$equipment->unit = Input::get('unit');
			$equipment->rate = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$equipment->amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			$equipment->save();

			return json_encode(['success' => 1]);
		}
	}

	public function doUpdateLabor()
	{
		$rules = array(
			'id' => array('integer','min:0'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			$rate = Input::get('rate');
			if (empty($rate)) {
				$rate = Project::where('user_id','=', Auth::user()->id)->first()->hour_rate;
			} else {
				$rate = str_replace(',', '.', str_replace('.', '' , $rate));
			}
			$labor = MoreLabor::find(Input::get('id'));
			$labor->rate = $rate;
			$labor->amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			$labor->save();

			return json_encode(['success' => 1]);
		}
	}
}
