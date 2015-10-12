<?php

namespace Calctool\Http\Controllers;

class InvoiceController extends Controller {

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
			if (!$invoice)
				return json_encode(['success' => 0]);
			$offer = Offer::find($invoice->offer_id);
			if (!$offer)
				return json_encode(['success' => 0]);
			$project = Project::find($offer->project_id);
			if (!$project || !$project->isOwner()) {
				return json_encode(['success' => 0]);
			}

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
			if (!$invoice)
				return json_encode(['success' => 0]);
			$offer = Offer::find($invoice->offer_id);
			if (!$offer)
				return json_encode(['success' => 0]);
			$project = Project::find($offer->project_id);
			if (!$project || !$project->isOwner()) {
				return json_encode(['success' => 0]);
			}

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
			if (!$invoice)
				return json_encode(['success' => 0]);
			$offer = Offer::find($invoice->offer_id);
			if (!$offer)
				return json_encode(['success' => 0]);
			$project = Project::find($offer->project_id);
			if (!$project || !$project->isOwner()) {
				return json_encode(['success' => 0]);
			}

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

			$project = Project::find(Input::get('projectid'));
			if (!$project || !$project->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
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
				$ninvoice->invoice_code = 'Concept';
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

			$invoice = Invoice::find(Input::get('id'));
			if (!$invoice)
				return json_encode(['success' => 0]);
			$offer = Offer::find($invoice->offer_id);
			if (!$offer)
				return json_encode(['success' => 0]);
			$project = Project::find($offer->project_id);
			if (!$project || !$project->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$invoice->delete();

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
			if (!$invoice)
				return json_encode(['success' => 0]);
			$offer = Offer::find($invoice->offer_id);
			if (!$offer)
				return json_encode(['success' => 0]);
			$project = Project::find($offer->project_id);
			if (!$project || !$project->isOwner()) {
				return json_encode(['success' => 0]);
			}
			$project = Project::find(Input::get('project'));
			if (!$project || !$project->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$invoice->amount = Input::get('amount');
			$invoice->rest_21 = InvoiceTerm::partTax1($project, $invoice)*Input::get('amount');
			$invoice->rest_6 = InvoiceTerm::partTax2($project, $invoice)*Input::get('amount');
			$invoice->rest_0 = InvoiceTerm::partTax3($project, $invoice)*Input::get('amount');
			$invoice->save();

			$cnt = Invoice::where('offer_id','=', $invoice->offer_id)->count();
			if ($cnt>1) {
				$invoice = Invoice::where('offer_id','=', $invoice->offer_id)->where('isclose','=',true)->first();
				$invoice->amount = Input::get('totaal');
				$invoice->rest_21 = InvoiceTerm::partTax1($project, $invoice)*Input::get('totaal');
				$invoice->rest_6 = InvoiceTerm::partTax2($project, $invoice)*Input::get('totaal');
				$invoice->rest_0 = InvoiceTerm::partTax3($project, $invoice)*Input::get('totaal');
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
			if (!$invoice)
				return json_encode(['success' => 0]);
			$offer = Offer::find($invoice->offer_id);
			if (!$offer)
				return json_encode(['success' => 0]);
			$project = Project::find($offer->project_id);
			if (!$project || !$project->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$project = Project::find(Input::get('projectid'));
			if (!$project || !$project->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$options = [];
			if (Input::get('toggle-note'))
				$options['description'] = 1;
			if (Input::get('toggle-subcontr'))
				$options['total'] = 1;
			if (Input::get('toggle-activity'))
				$options['specification'] = 1;
			if (Input::get('toggle-summary'))
				$options['onlyactivity'] = 1;
			if (Input::get('toggle-tax'))
				$options['displaytax'] = 1;

			if (Input::get('invdateval'))
				$invoice->invoice_make =  date('Y-m-d', strtotime(Input::get('invdateval')));
			else
				$invoice->invoice_make = date('Y-m-d');
			$invoice->invoice_close = true;
			$invoice->option_query = http_build_query($options);
			$invoice->invoice_code = InvoiceController::getInvoiceCode($project->id);
			$invoice->bill_date = date('Y-m-d H:i:s');

			$invoice->save();
			Auth::user()->invoice_counter++;
			Auth::user()->save();

			return Redirect::to('/invoice/project-'.$project->id);
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
			if (!$invoice)
				return json_encode(['success' => 0]);
			$offer = Offer::find($invoice->offer_id);
			if (!$offer)
				return json_encode(['success' => 0]);
			$project = Project::find($offer->project_id);
			if (!$project || !$project->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$invoice->payment_date = date('Y-m-d');

			$invoice->save();

			return json_encode(['success' => 1, 'payment' => date('d-m-Y')]);
		}
	}

	public function doInvoiceCloseAjax()
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
			if (!$invoice)
				return json_encode(['success' => 0]);
			$offer = Offer::find($invoice->offer_id);
			if (!$offer)
				return json_encode(['success' => 0]);
			$project = Project::find($offer->project_id);
			if (!$project || !$project->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$project = Project::find(Input::get('projectid'));
			if (!$project || !$project->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$invoice->invoice_close = true;
			$invoice->invoice_code = InvoiceController::getInvoiceCode($project->id);
			$invoice->bill_date = date('Y-m-d H:i:s');

			$invoice->save();
			Auth::user()->invoice_counter++;
			Auth::user()->save();

			return json_encode(['success' => 1, 'billing' => date('d-m-Y')]);
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
