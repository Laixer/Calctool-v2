<?php

namespace Calctool\Http\Controllers;

use Illuminate\Http\Request;
use \Calctool\Models\Project;
use \Calctool\Models\Offer;
use \Calctool\Models\OfferPost;
use \Calctool\Calculus\CalculationEndresult;
use \Calctool\Models\Resource;
use \Calctool\Http\Controllers\InvoiceController;
use \Calctool\Models\Invoice;
use \Calctool\Models\ProjectShare;
use \Calctool\Models\Contact;
use \Calctool\Calculus\ResultEndresult;
use \Calctool\Calculus\InvoiceTerm;

use \Auth;
use \PDF;
use \Mailgun;

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
			$offer->offer_make = date('Y-m-d', strtotime($request->get('offdateval')));
		else
			$offer->offer_make = date('Y-m-d');
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

		$page = 0;
		$newname = Auth::id().'-'.substr(md5(uniqid()), 0, 5).'-'.OfferController::getOfferCode($request->input('project_id')).'-offer.pdf';
		$pdf = PDF::loadView('calc.offer_pdf', ['offer' => $offer]);
		$pdf->setOption('footer-html','http://localhost/c4586v34674v4&vwasrt/footer_pdf?uid='.Auth::id()."&page=".$page++);
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

		for ($i=0; $i < $offer->invoice_quantity; $i++) {
			$invoice = new Invoice;
			$invoice->priority = $i;
			$invoice->invoice_code = InvoiceController::getInvoiceCodeConcept($project->id);
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

		return json_encode(['success' => 1]);
	}

	public function doSendOffer(Request $request)
	{
		$offer = Offer::find($request->input('offer'));
		if (!$offer)
			return json_encode(['success' => 0]);
		$project = Project::find($offer->project_id);
		if (!$project || !$project->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$share = ProjectShare::where('project_id', $project->id)->first();
		if (!$share) {
			$share = new ProjectShare;
			$share->project_id = $project->id;

			$share->save();
		}

		$res = Resource::find($offer->resource_id);
		$contact_client = Contact::find($offer->to_contact_id);
		$contact_user = Contact::find($offer->from_contact_id);

		$user_logo = '';
		$relation_self = Relation::find(Auth::user()->self_id);
		if ($relation_self->logo_id)
			$user_logo = Resource::find($relation_self->logo_id)->file_location;

		$data = array(
			'email' => $contact_client->email,
			'pdf' => $res->file_location,
			'preview' => false,
			'offer_id' => $offer->id,
			'token' => $share->token,
			'client' => $contact_client->getFormalName(),
			'user' => $contact_user->getFormalName(),
			'project_name' => $project->project_name,
			'pref_email_offer' => Auth::User()->pref_email_offer,
			'user_logo' => $user_logo
		);
		Mailgun::send('mail.offer_send', $data, function($message) use ($data) {
			$message->to($data['email'], strtolower(trim($data['client'])));
			$message->attach($data['pdf']);
			$message->subject('Offerte ' . $data['project_name']);
			$message->from('info@calculatietool.com', 'CalculatieTool.com');
			$message->replyTo('info@calculatietool.com', 'CalculatieTool.com');
		});

		return json_encode(['success' => 1]);
	}

	public function getSendOfferPreview(Request $request, $project_id, $offer_id)
	{
		$offer = Offer::find($offer_id);
		if (!$offer)
			return json_encode(['success' => 0]);
		$project = Project::find($project_id);
		if (!$project || !$project->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$share = ProjectShare::where('project_id', $project->id)->first();
		if (!$share) {
			$share = new ProjectShare;
			$share->project_id = $project->id;

			$share->save();
		}

		$contact_client = Contact::find($offer->to_contact_id);
		$contact_user = Contact::find($offer->from_contact_id);

		$user_logo = '';
		$relation_self = Relation::find(Auth::user()->self_id);
		if ($relation_self->logo_id)
			$user_logo = Resource::find($relation_self->logo_id)->file_location;

		$data = array(
			'preview' => true,
			'offer_id' => $offer->id,
			'client'=> $contact_client->getFormalName(),
			'token' => $share->token,
			'project_name' => $project->project_name,
			'user' => $contact_user->getFormalName(),
			'pref_email_offer' => Auth::User()->pref_email_offer,
			'user_logo' => $user_logo
		);
		return view('mail.offer_send', $data);
	}

	public function doSendPostOffer(Request $request)
	{
		$offer = Offer::find($request->input('offer'));
		if (!$offer)
			return json_encode(['success' => 0]);
		$project = Project::find($offer->project_id);
		if (!$project || !$project->isOwner()) {
			return json_encode(['success' => 0]);
		}

		if (OfferPost::where('offer_id', $offer->id)->count()>0) {
			return json_encode(['success' => 0,'message' => 'Offerte al aangeboden']);
		}
		$post = new OfferPost;
		$post->offer_id = $offer->id;

		$post->save();

/*    $data = array(
        'email' => $contact_client->email,
        'project_name' => $project->project_name,
        'client' => $contact_client->getFormalName(),
        'pref_email_invoice_last_reminder' => $user->pref_email_invoice_last_reminder,
        'user' => $contact_user->getFormalName()
    );
    Mailgun::send('mail.invoice_last_reminder', $data, function($message) use ($data) {
        $message->to($data['email'], strtolower(trim($data['client'])));
        $message->subject('CalculatieTool.com - Tweede betalingsherinnering');
        $message->from('info@calculatietool.com', 'CalculatieTool.com');
        $message->replyTo('info@calculatietool.com', 'CalculatieTool.com');
    });

    $message = new MessageBox;
    $message->subject = 'Factuur over betalingsdatum';
    $message->message = 'Een 2e betalingsherinnering voor '.$project->project_name.' is verzonden naar '.$contact_client->getFormalName().'.';
    $message->from_user = User::where('username', 'system')->first()['id'];
    $message->user_id = $project->user_id;*/

        $message->save();

		return json_encode(['success' => 1]);
	}

	/* id = $project->id */
	public static function getOfferCode($id)
	{
		return sprintf("%s%05d-%03d-%s", Auth::user()->offernumber_prefix, $id, Auth::user()->offer_counter, date('y'));
	}
}
