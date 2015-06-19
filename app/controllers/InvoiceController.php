<?php

class InvoiceController extends BaseController {

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

	public function doUpdateCode()
	{
		$rules = array(
			'id' => array('required','integer')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			$invoice = Invoice::find(Input::get('id'));
			$invoice->reference = Input::get('reference');
			$invoice->book_code = Input::get('bookcode');

			$invoice->save();

			return json_encode(['success' => 1]);
		}
	}

	public function doUpdateAmount()
	{
		$rules = array(
			'id' => array('required','integer'),
			'idend' => array('required','integer')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			$invoice = Invoice::find(Input::get('id'));
			$invoice->amount = Input::get('amount');
			$invoice->save();
			$invoice = Invoice::find(Input::get('idend'));
			$invoice->amount = Input::get('totaal');
			$invoice->save();

			return json_encode(['success' => 1]);
		}
	}

}
