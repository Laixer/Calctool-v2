<?php

namespace Calctool\Http\Controllers;

use Illuminate\Http\Request;
use \Calctool\Models\Project;
use \Calctool\Models\Offer;
use \Calctool\Calculus\CalculationEndresult;
use \Calctool\Models\Resource;
use \Calctool\Http\Controllers\InvoiceController;
use \Calctool\Models\Invoice;
use \Calctool\Calculus\ResultEndresult;
use \Calctool\Calculus\InvoiceTerm;

use \Auth;
use \PDF;


class OfferController extends Controller {

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

	public function doNewOffer(Request $request, $projectid)
	{
		$this->validate($request, [
			'deliver' => array('required','integer','min:0'),
			'terms' => array('integer','min:0'),
			'valid' => array('required','integer','min:0'),
			'to_contact' => array('required'),
			'from_contact' => array('required'),
		]);

		$project = Project::find($projectid);
		if (!$project || !$project->isOwner()) {
			return back()->withInput($request->all());
		}

		$offer = new Offer;
		$offer->to_contact_id = $request->get('to_contact');
		$offer->from_contact_id = $request->get('from_contact');
		$offer->description = $request->get('description');
		$offer->offer_code = OfferController::getOfferCode($project->id);
		$offer->extracondition = $request->get('extracondition');
		$offer->closure = $request->get('closure');
		if ($request->get('offdateval'))
			$offer->offer_make =  date('Y-m-d', strtotime($request->get('offdateval')));
		if ($request->get('toggle-payment'))
			$offer->downpayment = $request->get('toggle-payment');
		if ($request->get('amount'))
			$offer->downpayment_amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));
		$offer->auto_email_reminder = false;
		$offer->deliver_id = $request->get('deliver');
		$offer->valid_id = $request->get('valid');
		if ($request->get('terms'))
			$offer->invoice_quantity = $request->get('terms');
		$offer->project_id = $project->id;;

		if ($request->get('include-tax'))
			$offer->include_tax = true;
		else
			$offer->include_tax = false;
		if ($request->get('only-totals'))
			$offer->only_totals = true;
		else
			$offer->only_totals = false;
		if ($request->get('seperate-subcon'))
			$offer->seperate_subcon = true;
		else
			$offer->seperate_subcon = false;
		if ($request->get('display-worktotals'))
			$offer->display_worktotals = true;
		else
			$offer->display_worktotals = false;
		if ($request->get('display-specification'))
			$offer->display_specification = true;
		else
			$offer->display_specification = false;
		if ($request->get('display-description'))
			$offer->display_description = true;
		else
			$offer->display_description = false;

		$offer->offer_total = CalculationEndresult::totalProject($project);
		$offer->save();

		$newname = Auth::id().'-'.substr(md5(uniqid()), 0, 5).'-'.OfferController::getOfferCode($request->input('project_id')).'-offer.pdf';
		$pdf = PDF::loadView('calc.offer_pdf');
		$pdf->setOption('footer-html','http://localhost/c4586v34674v4&vwasrt/footer_pdf?uid='.Auth::id());
		$pdf->save('user-content/'.$newname);

		$resource = new Resource;
		$resource->resource_name = $newname;
		$resource->file_location = 'user-content/' . $newname;
		$resource->file_size = filesize('user-content/' . $newname);
		$resource->user_id = Auth::id();
		$resource->description = 'Offerteversie';

		$resource->save();

		$offer->resource_id = $resource->id;

		$offer->save();

		Auth::user()->offer_counter++;
		Auth::user()->save();

		return redirect('/offer/project-'.$project->id.'/offer-'.$offer->id);
	}

	public function doOfferClose(Request $request)
	{
		
		$this->validate($request, [
			'date' => array('required'),
			'offer' => array('required','integer'),
			'project' => array('required','integer'),
		]);

		$offer = Offer::find($request->get('offer'));
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

		$offer->offer_finish = date('Y-m-d', strtotime($request->get('date')));
		$offer->save();

		$first_invoice = null;

		for ($i=0; $i < $offer->invoice_quantity; $i++) {
			$invoice = new Invoice;
			$invoice->priority = $i;
			$invoice->invoice_code = InvoiceController::getInvoiceCodeConcept($project->id);
			$invoice->payment_condition = 30;
			$invoice->offer_id = $offer->id;
			if (($i+1) == $offer->invoice_quantity) {
				$project_total = ResultEndresult::totalProject($project);
				$project_total -= $offer->downpayment_amount;
				$invoice->amount = $project_total;
				$invoice->rest_21 = InvoiceTerm::partTax1($project, $invoice) * $project_total;
				$invoice->rest_6 = InvoiceTerm::partTax2($project, $invoice) * $project_total;
				$invoice->rest_0 = InvoiceTerm::partTax3($project, $invoice) * $project_total;
				$invoice->isclose = true;
			}
			if ($i == 0 && $offer->downpayment) {
				$invoice->amount = $offer->downpayment_amount;
				$invoice->rest_21 = InvoiceTerm::partTax1($project, $invoice) * $offer->downpayment_amount;
				$invoice->rest_6 = InvoiceTerm::partTax2($project, $invoice) * $offer->downpayment_amount;
				$invoice->rest_0 = InvoiceTerm::partTax3($project, $invoice) * $offer->downpayment_amount;
			}
			$invoice->save();
			if ($i == 0)
				$first_invoice = $invoice;
		}

		return json_encode(['success' => 1]);
	}

	/* id = $project->id */
	public static function getOfferCode($id)
	{
		return sprintf("%s%05d-%03d-%s", Auth::user()->offernumber_prefix, $id, Auth::user()->offer_counter, date('y'));
	}
}
