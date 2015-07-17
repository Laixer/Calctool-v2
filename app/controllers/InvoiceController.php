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

	public function doInvoiceNewTerm()
	{
		$rules = array(
			'projectid' => array('required','integer')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			$offer_last = Offer::where('project_id','=',Input::get('projectid'))->orderBy('created_at', 'desc')->first();
			$cnt = Invoice::where('offer_id','=', $offer_last->id)->count();
			if ($cnt>1) {
				$invoice = Invoice::where('offer_id','=', $offer_last->id)->where('isclose','=',false)->orderBy('priority', 'desc')->first();

				$ninvoice = new Invoice;
				$ninvoice->payment_condition = $invoice->payment_condition;
				$ninvoice->invoice_code = $invoice->invoice_code;
				$ninvoice->priority = $invoice->priority+1;
				$ninvoice->offer_id = $invoice->offer_id;
				$ninvoice->save();
			} else {
				$ninvoice = new Invoice;
				$ninvoice->payment_condition = 1;
				$ninvoice->invoice_code = 'XYZ';
				$ninvoice->priority = 1;
				$ninvoice->offer_id = $offer_last->id;
				$ninvoice->save();
			}

			return Redirect::back();
		}
	}

	public function doInvoiceDeleteTerm()
	{
		$rules = array(
			'id' => array('required','integer')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			Invoice::destroy(Input::get('id'));

			return Redirect::back();
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
			$invoice->invoice_code = InvoiceController::getInvoiceCode(Input::get('projectid'));
			$invoice->bill_date = date('Y-m-d H:i:s');

			$invoice->save();
			Auth::user()->invoice_counter++;
			Auth::user()->save();

			return Redirect::to('/invoice/project-'.Input::get('projectid'));
		}
	}

	public function doInvoicePay()
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
			$invoice->payment_date = date('Y-m-d');

			$invoice->save();

			return json_encode(['success' => 1, 'payment' => date('d-m-Y')]);
		}
	}

	/* id = $project->id */
	public static function getInvoiceCode($id)
	{
		return sprintf("%s%05d-%03d-%s", Auth::user()->invoicenumber_prefix, $id, Auth::user()->invoice_counter, date('y'));
	}

	/* id = $project->id */
	public static function getInvoiceCodeConcept($id)
	{
		return sprintf("%s%05d-CONCEPT-%s", Auth::user()->invoicenumber_prefix, $id, date('y'));
	}

}
