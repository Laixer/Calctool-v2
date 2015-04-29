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
				$rate = Project::where('user_id','=', Auth::user()->id)->first()->hour_rate;
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

	/*public function doNewEstimateMaterial()
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
			$material = EstimateMaterial::create(array(
				"material_name" => Input::get('name'),
				"unit" => Input::get('unit'),
				"rate" => str_replace(',', '.', str_replace('.', '' , Input::get('rate'))),
				"amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => Input::get('activity'),
				"original" => true,
				"isset" => false
			));

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
			'activity' => array('required','integer','min:0')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			$equipment = EstimateEquipment::create(array(
				"equipment_name" => Input::get('name'),
				"unit" => Input::get('unit'),
				"rate" => str_replace(',', '.', str_replace('.', '' , Input::get('rate'))),
				"amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => Input::get('activity'),
				"original" => true,
				"isset" => false
			));

			return json_encode(['success' => 1, 'id' => $equipment->id]);
		}
	}

	public function doNewEstimateLabor()
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
				$rate = Project::where('user_id','=', Auth::user()->id)->first()->hour_rate;
			} else {
				$rate = str_replace(',', '.', str_replace('.', '' , $rate));
			}
			$labor = EstimateLabor::create(array(
				"rate" => $rate,
				"amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => Input::get('activity'),
				"original" => true,
				"isset" => false
			));

			return json_encode(['success' => 1, 'id' => $labor->id]);
		}
	}

	public function doDeleteEstimateMaterial()
	{
		$rules = array(
			'id' => array('required','integer','min:0'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			EstimateMaterial::destroy(Input::get('id'));

			return json_encode(['success' => 1]);
		}
	}

	public function doDeleteEstimateEquipment()
	{
		$rules = array(
			'id' => array('required','integer','min:0'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			EstimateEquipment::destroy(Input::get('id'));

			return json_encode(['success' => 1]);
		}
	}

	public function doUpdateEstimateMaterial()
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

			$material = EstimateMaterial::find(Input::get('id'));
			$material->material_name = Input::get('name');
			$material->unit = Input::get('unit');
			$material->rate = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$material->amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			$material->save();

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
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$equipment = EstimateEquipment::find(Input::get('id'));
			$equipment->equipment_name = Input::get('name');
			$equipment->unit = Input::get('unit');
			$equipment->rate = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$equipment->amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			$equipment->save();

			return json_encode(['success' => 1]);
		}
	}

	public function doUpdateEstimateLabor()
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
			$labor = EstimateLabor::find(Input::get('id'));
			$labor->rate = $rate;
			$labor->amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			$labor->save();

			return json_encode(['success' => 1]);
		}
	}*/
}
