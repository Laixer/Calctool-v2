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
			$rate = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));
			$material = CalculationMaterial::find(Input::get('id'));
			if ($rate > $material->rate)
				return json_encode(['success' => 0, 'message' => 'rate too large', 'rate' => $material->rate, 'amount' => $material->amount]);

			$material->less_rate = $rate;
			if ($amount > $material->amount)
				return json_encode(['success' => 0, 'message' => 'amount too large', 'rate' => $material->rate, 'amount' => $material->amount]);

			$material->less_amount = $amount;
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
			$rate = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));
			$equipment = CalculationEquipment::find(Input::get('id'));
			if ($rate > $equipment->rate)
				return json_encode(['success' => 0, 'message' => 'rate too large', 'rate' => $equipment->rate, 'amount' => $equipment->amount]);

			$equipment->less_rate = $rate;
			if ($amount > $equipment->amount)
				return json_encode(['success' => 0, 'message' => 'amount too large', 'rate' => $equipment->rate, 'amount' => $equipment->amount]);

			$equipment->less_amount = $amount;
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
			$amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));
			$labor = CalculationLabor::find(Input::get('id'));
			if ($amount > $labor->amount)
				return json_encode(['success' => 0, 'message' => 'amount too large', 'amount' => $labor->amount]);

			$labor->less_amount = $amount;
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

}
