<?php

namespace Calctool\Http\Controllers;

use Illuminate\Http\Request;

use \Calctool\Models\Project;
use \Calctool\Models\Invoice;
use \Calctool\Models\Offer;
use \Calctool\Calculus\InvoiceTerm;

use \Auth;

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

	public function doUpdateCode(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer')
		]);

		$invoice = Invoice::find($request->get('id'));
		if (!$invoice)
			return json_encode(['success' => 0]);
		$offer = Offer::find($invoice->offer_id);
		if (!$offer)
			return json_encode(['success' => 0]);
		$project = Project::find($offer->project_id);
		if (!$project || !$project->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$invoice->reference = $request->get('reference');
		$invoice->book_code = $request->get('bookcode');

		$invoice->save();

		return json_encode(['success' => 1]);
	}

	public function doUpdateDescription(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer')
		]);

		$invoice = Invoice::find($request->get('id'));
		if (!$invoice)
			return json_encode(['success' => 0]);
		$offer = Offer::find($invoice->offer_id);
		if (!$offer)
			return json_encode(['success' => 0]);
		$project = Project::find($offer->project_id);
		if (!$project || !$project->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$invoice->description = $request->get('description');
		$invoice->closure = $request->get('closure');

		$invoice->save();

		return json_encode(['success' => 1]);
	}

	public function doUpdateCondition(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer'),
			'condition' => array('required','integer')
		]);

		$invoice = Invoice::find($request->get('id'));
		if (!$invoice)
			return json_encode(['success' => 0]);
		$offer = Offer::find($invoice->offer_id);
		if (!$offer)
			return json_encode(['success' => 0]);
		$project = Project::find($offer->project_id);
		if (!$project || !$project->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$invoice->payment_condition = $request->get('condition');

		$invoice->save();

		return json_encode(['success' => 1]);
	}

	public function doInvoiceNewTerm(Request $request)
	{
		$this->validate($request, [
			'projectid' => array('required','integer')
		]);

		$project = Project::find($request->get('projectid'));
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

		return back();
	}

	public function doInvoiceDeleteTerm(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer')
		]);

		$invoice = Invoice::find($request->get('id'));
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

		return back();
	}

	public function doUpdateAmount(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer'),
			'project' => array('required','integer')
		]);

		$invoice = Invoice::find($request->get('id'));
		if (!$invoice)
			return json_encode(['success' => 0]);
		$offer = Offer::find($invoice->offer_id);
		if (!$offer)
			return json_encode(['success' => 0]);
		$project = Project::find($offer->project_id);
		if (!$project || !$project->isOwner()) {
			return json_encode(['success' => 0]);
		}
		$project = Project::find($request->get('project'));
		if (!$project || !$project->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));
		$total = str_replace(',', '.', str_replace('.', '' , $request->get('totaal')));

		$invoice->amount = $amount;
		$invoice->rest_21 = InvoiceTerm::partTax1($project, $invoice)*$amount;
		$invoice->rest_6 = InvoiceTerm::partTax2($project, $invoice)*$amount;
		$invoice->rest_0 = InvoiceTerm::partTax3($project, $invoice)*$amount;
		$invoice->save();

		$cnt = Invoice::where('offer_id','=', $invoice->offer_id)->count();
		if ($cnt>1) {
			$invoice = Invoice::where('offer_id','=', $invoice->offer_id)->where('isclose','=',true)->first();
			$invoice->amount = $total;
			$invoice->rest_21 = InvoiceTerm::partTax1($project, $invoice)*$total;
			$invoice->rest_6 = InvoiceTerm::partTax2($project, $invoice)*$total;
			$invoice->rest_0 = InvoiceTerm::partTax3($project, $invoice)*$total;
			$invoice->save();
		}

		return json_encode(['success' => 1]);
	}

	public function doInvoiceClose(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer'),
			'projectid' => array('required','integer'),
		]);

		$invoice = Invoice::find($request->get('id'));
		if (!$invoice)
			return json_encode(['success' => 0]);
		$offer = Offer::find($invoice->offer_id);
		if (!$offer)
			return json_encode(['success' => 0]);
		$project = Project::find($offer->project_id);
		if (!$project || !$project->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$project = Project::find($request->get('projectid'));
		if (!$project || !$project->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$options = [];
		if ($request->get('toggle-note'))
			$options['description'] = 1;
		if ($request->get('toggle-subcontr'))
			$options['total'] = 1;
		if ($request->get('toggle-activity'))
			$options['specification'] = 1;
		if ($request->get('toggle-summary'))
			$options['onlyactivity'] = 1;
		if ($request->get('toggle-tax'))
			$options['displaytax'] = 1;

		if ($request->get('invdateval'))
			$invoice->invoice_make =  date('Y-m-d', strtotime($request->get('invdateval')));
		else
			$invoice->invoice_make = date('Y-m-d');
		$invoice->invoice_close = true;
		$invoice->option_query = http_build_query($options);
		$invoice->invoice_code = InvoiceController::getInvoiceCode($project->id);
		$invoice->bill_date = date('Y-m-d H:i:s');

		$invoice->save();
		Auth::user()->invoice_counter++;
		Auth::user()->save();

		return redirect('/invoice/project-'.$project->id);
	}

	public function doInvoicePay(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer'),
			'projectid' => array('required','integer'),
		]);

		$invoice = Invoice::find($request->get('id'));
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

	public function doInvoiceCloseAjax(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer'),
			'projectid' => array('required','integer'),
		]);

		$invoice = Invoice::find($request->get('id'));
		if (!$invoice)
			return json_encode(['success' => 0]);
		$offer = Offer::find($invoice->offer_id);
		if (!$offer)
			return json_encode(['success' => 0]);
		$project = Project::find($offer->project_id);
		if (!$project || !$project->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$project = Project::find($request->get('projectid'));
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
