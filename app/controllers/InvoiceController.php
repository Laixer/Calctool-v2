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

	public function doUpdateDescription()
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
			$invoice->description = Input::get('description');
			$invoice->closure = Input::get('closure');

			$invoice->save();

			return json_encode(['success' => 1]);
		}
	}

	public function doUpdateCondition()
	{
		$rules = array(
			'id' => array('required','integer'),
			'condition' => array('required','integer')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			$invoice = Invoice::find(Input::get('id'));
			$invoice->payment_condition = Input::get('condition');

			$invoice->save();

			return json_encode(['success' => 1]);
		}
	}

	public function doUpdateAmount()
	{
		$rules = array(
			'id' => array('required','integer'),
			'project' => array('required','integer')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			$invoice = Invoice::find(Input::get('id'));
			$invoice->amount = Input::get('amount');
			$invoice->rest_21 = InvoiceTerm::partTax1(Project::find(Input::get('project')), $invoice)*Input::get('amount');
			$invoice->rest_6 = InvoiceTerm::partTax2(Project::find(Input::get('project')), $invoice)*Input::get('amount');
			$invoice->rest_0 = InvoiceTerm::partTax3(Project::find(Input::get('project')), $invoice)*Input::get('amount');
			$invoice->save();

			$cnt = Invoice::where('offer_id','=', $invoice->offer_id)->count();
			if ($cnt>1) {
				$invoice = Invoice::where('offer_id','=', $invoice->offer_id)->where('isclose','=',true)->first();
				$invoice->amount = Input::get('totaal');
				$invoice->rest_21 = InvoiceTerm::partTax1(Project::find(Input::get('project')), $invoice)*Input::get('totaal');
				$invoice->rest_6 = InvoiceTerm::partTax2(Project::find(Input::get('project')), $invoice)*Input::get('totaal');
				$invoice->rest_0 = InvoiceTerm::partTax3(Project::find(Input::get('project')), $invoice)*Input::get('totaal');
				$invoice->save();
			}

			return json_encode(['success' => 1]);
		}
	}

	public function doInvoiceClose()
	{
		$rules = array(
			'id' => array('required','integer'),
			'projectid' => array('required','integer'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			$invoice = Invoice::find(Input::get('id'));
			$invoice->invoice_close = true;

			$invoice->save();

			return Redirect::to('/invoice/project-'.Input::get('projectid'));
		}
	}

}
