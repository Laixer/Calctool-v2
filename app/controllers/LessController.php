<?php

class LessController extends BaseController {

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

	/*public function doNewLessMaterial()
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
				"set_material_name" => Input::get('name'),
				"set_unit" => Input::get('unit'),
				"set_rate" => str_replace(',', '.', str_replace('.', '' , Input::get('rate'))),
				"set_amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => Input::get('activity'),
				"original" => false,l
				"isset" => true
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
				"set_equipment_name" => Input::get('name'),
				"set_unit" => Input::get('unit'),
				"set_rate" => str_replace(',', '.', str_replace('.', '' , Input::get('rate'))),
				"set_amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => Input::get('activity'),
				"original" => false,
				"isset" => true
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
				"set_rate" => $rate,
				"set_amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"set_activity_id" => Input::get('activity'),
				"original" => false,
				"isset" => true
			));

			return json_encode(['success' => 1, 'id' => $labor->id]);
		}
	}*/

	public function doUpdateMaterial()
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
			$material = CalculationMaterial::find(Input::get('id'));
			$material->less_rate = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$material->less_amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));
			$material->isless = True;

			$material->save();

			return json_encode(['success' => 1, 'less_rate' => number_format($material->less_rate, 2,",","."), 'less_amount' => number_format($material->less_amount, 2,",",".")]);
		}
	}

	public function doUpdateEquipment()
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
			$equipment = CalculationEquipment::find(Input::get('id'));
			$equipment->less_rate = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$equipment->less_amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));
			$equipment->isless = True;

			$equipment->save();

			return json_encode(['success' => 1, 'less_rate' => number_format($equipment->less_rate, 2,",","."), 'less_amount' => number_format($equipment->less_amount, 2,",",".")]);
		}
	}

	public function doUpdateLabor()
	{
		$rules = array(
			'id' => array('integer','min:0'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			$labor = CalculationLabor::find(Input::get('id'));
			$labor->less_amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));
			$labor->isless = True;

			$labor->save();

			return json_encode(['success' => 1, 'less_amount' => number_format($labor->less_amount, 2,",",".")]);
		}
	}

	public function doResetMaterial()
	{
		$rules = array(
			'id' => array('integer','min:0'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			$material = CalculationMaterial::find(Input::get('id'));
			$material->less_rate = NULL;
			$material->less_amount = NULL;
			$material->isless = False;

			$material->save();

			return json_encode(['success' => 1, 'rate' => number_format($material->rate, 2,",","."), 'amount' => number_format($material->amount, 2,",",".")]);
		}
	}

	public function doResetEquipment()
	{
		$rules = array(
			'id' => array('integer','min:0'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$equipment = CalculationEquipment::find(Input::get('id'));
			$equipment->less_rate = NULL;
			$equipment->less_amount = NULL;
			$equipment->isless = False;

			$equipment->save();

			return json_encode(['success' => 1, 'rate' => number_format($equipment->rate, 2,",","."), 'amount' => number_format($equipment->amount, 2,",",".")]);
		}
	}

	public function doResetLabor()
	{
		$rules = array(
			'id' => array('integer','min:0'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$labor = CalculationLabor::find(Input::get('id'));
			$labor->less_amount = NULL;
			$labor->isless = False;

			$labor->save();

			return json_encode(['success' => 1, 'amount' => number_format($labor->amount, 2,",",".")]);
		}

	}
/*
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
			EstimaetEquipment::destroy(Input::get('id'));

			return json_encode(['success' => 1]);
		}
	}
*/
}
