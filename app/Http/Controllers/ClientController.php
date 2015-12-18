<?php

namespace Calctool\Http\Controllers;

use Illuminate\Http\Request;

use \Calctool\Models\User;
use \Calctool\Models\MessageBox;
use \Calctool\Models\Project;
use \Calctool\Models\Offer;
use \Calctool\Models\Invoice;
use \Calctool\Models\ProjectShare;

use \Auth;

class ClientController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */

	public function doUpdateCommunication(Request $request, $token)
	{

		$project_share = ProjectShare::where('token', $token)->first();
		if (!$project_share) {
			return back();
		}
		$project = Project::find($project_share->project_id);

		$project_share->client_note = $request->input('client_note');		
		$project_share->save();

		$message = new MessageBox;
		$message->subject = 'Opdrachtgever heeft gereageerd';
		$message->message = 'De opdrachtgever heeft de volgende opmerking geplaatst bij project <a href="/project-' . $project->id . '/edit">' . $project->project_name . '</a>:<br /><br />' . nl2br($request->input('client_note'));
		$message->from_user = User::where('username', 'system')->first()['id'];
		$message->user_id =	$project->user_id;

		$message->save();

		return back()->with('success', 'Opmerking toegevoegd aan project');
	}

	public function doOfferAccept(Request $request, $token)
	{
		$project_share = ProjectShare::where('token', $token)->first();
		if (!$project_share) {
			return back();
		}
		$project = Project::find($project_share->project_id);

		$offer = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();

		$offer->offer_finish = date('Y-m-d');
		$offer->save();

		for ($i=0; $i < $offer->invoice_quantity; $i++) {
			$invoice = new Invoice;
			$invoice->priority = $i;
			$invoice->invoice_code = InvoiceController::getInvoiceCodeConcept($project->id, User::find($project->user_id));
			$invoice->payment_condition = 30;
			$invoice->offer_id = $offer->id;
			$invoice->to_contact_id = $offer->to_contact_id;
			$invoice->from_contact_id = $offer->from_contact_id;
			if (($i+1) == $offer->invoice_quantity) {
				$invoice->isclose = true;
			}
			if ($i == 0 && $offer->downpayment) {
				$invoice->amount = $offer->downpayment_amount;
				$invoice->rest_21 = InvoiceTerm::partTax1($project, $invoice) * $offer->downpayment_amount;
				$invoice->rest_6 = InvoiceTerm::partTax2($project, $invoice) * $offer->downpayment_amount;
				$invoice->rest_0 = InvoiceTerm::partTax3($project, $invoice) * $offer->downpayment_amount;
			}
			$invoice->save();
		}

		$message = new MessageBox;
		$message->subject = 'Opdrachtgever heeft offerte bevestigd';
		$message->message = 'De opdrachtgever heeft de offerte bij project <a href="/project-' . $project->id . '/edit">' . $project->project_name . '</a> bevestigd';
		$message->from_user = User::where('username', 'system')->first()['id'];
		$message->user_id =	$project->user_id;

		$message->save();

		return back()->with('success', 'Offerte is bevestigd');
	}

}
