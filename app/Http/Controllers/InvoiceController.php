<?php

namespace Calctool\Http\Controllers;

use Illuminate\Http\Request;

use \Calctool\Models\Project;
use \Calctool\Models\Invoice;
use \Calctool\Models\InvoiceVersion;
use \Calctool\Models\Offer;
use \Calctool\Models\Contact;
use \Calctool\Models\Resource;
use \Calctool\Models\InvoicePost;
use \Calctool\Models\Relation;
use \Calctool\Models\User;
use \Calctool\Models\UserType;
use \Calctool\Models\MessageBox;
use \Calctool\Calculus\InvoiceTerm;

use \Auth;
use \PDF;
use \Mailgun;

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
			'id' => array('required','integer'),
			'reference' => array('max:30'),
			'bookcode' => array('max:30'),
		]);

		$invoice = Invoice::find($request->get('id'));
		if (!$invoice)
			return response()->json(['success' => 0]);
		$offer = Offer::find($invoice->offer_id);
		if (!$offer)
			return response()->json(['success' => 0]);
		$project = Project::find($offer->project_id);
		if (!$project || !$project->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$invoice->reference = $request->get('reference');
		$invoice->book_code = $request->get('bookcode');

		$invoice->save();

		return response()->json(['success' => 1]);
	}

	public function doUpdateDescription(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer')
		]);

		$invoice = Invoice::find($request->get('id'));
		if (!$invoice)
			return response()->json(['success' => 0]);
		$offer = Offer::find($invoice->offer_id);
		if (!$offer)
			return response()->json(['success' => 0]);
		$project = Project::find($offer->project_id);
		if (!$project || !$project->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$invoice->description = $request->get('description');
		$invoice->closure = $request->get('closure');

		$invoice->save();

		return response()->json(['success' => 1]);
	}

	public function doUpdateCondition(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer'),
			'condition' => array('required','integer')
		]);

		$invoice = Invoice::find($request->get('id'));
		if (!$invoice)
			return response()->json(['success' => 0]);
		$offer = Offer::find($invoice->offer_id);
		if (!$offer)
			return response()->json(['success' => 0]);
		$project = Project::find($offer->project_id);
		if (!$project || !$project->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$invoice->payment_condition = $request->get('condition');

		$invoice->save();

		return response()->json(['success' => 1]);
	}

	public function doInvoiceVersionNew(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer'),
			'projectid' => array('required','integer'),
		]);

		$invoice = Invoice::find($request->get('id'));
		if (!$invoice)
			return response()->json(['success' => 0]);
		$offer = Offer::find($invoice->offer_id);
		if (!$offer)
			return response()->json(['success' => 0]);
		$project = Project::find($offer->project_id);
		if (!$project || !$project->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$project = Project::find($request->get('projectid'));
		if (!$project || !$project->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$invoice_version = new InvoiceVersion;

		if ($request->get('include-tax'))
			$invoice_version->include_tax = true;
		else
			$invoice_version->include_tax = false;
		if ($request->get('only-totals'))
			$invoice_version->only_totals = true;
		else
			$invoice_version->only_totals = false;
		if ($request->get('seperate-subcon'))
			$invoice_version->seperate_subcon = true;
		else
			$invoice_version->seperate_subcon = false;
		if ($request->get('display-worktotals'))
			$invoice_version->display_worktotals = true;
		else
			$invoice_version->display_worktotals = false;
		if ($request->get('display-specification'))
			$invoice_version->display_specification = true;
		else
			$invoice_version->display_specification = false;
		if ($request->get('display-description'))
			$invoice_version->display_description = true;
		else
			$invoice_version->display_description = false;

		$invoice_version->amount = $invoice->amount;
		$invoice_version->rest_21 = $invoice->rest_21;
		$invoice_version->rest_6 = $invoice->rest_6;
		$invoice_version->rest_0 = $invoice->rest_0;
		$invoice_version->description = $request->get('description');
		$invoice_version->closure = $request->get('closure');
		$invoice_version->reference = $invoice->reference;
		$invoice_version->book_code = $invoice->book_code;
		$invoice_version->invoice_code = $invoice->invoice_code;
		$invoice_version->payment_condition = $invoice->payment_condition;
		$invoice_version->to_contact_id = $request->get('to_contact');
		$invoice_version->from_contact_id = $request->get('from_contact');
		$invoice_version->invoice_id = $invoice->id;

		$invoice_version->save();

		$newname = Auth::id().'-'.substr(md5(uniqid()), 0, 5).'-'.$invoice_version->invoice_code.'-invoice.pdf';
		if ($invoice->isclose) {
			$pdf = PDF::loadView('calc.invoice_pdf', ['invoice' => $invoice_version]);
		} else {
			$pdf = PDF::loadView('calc.invoice_term_pdf', ['invoice' => $invoice_version]);
		}

		$relation_self = Relation::find(Auth::User()->self_id);
		$footer_text = $relation_self->company_name;
		if ($relation_self->iban)
			$footer_text .= ' | Rekeningnummer: ' . $relation_self->iban;
		if ($relation_self->kvk)
			$footer_text .= ' | KVK: ' . $relation_self->kvk;
		if ($relation_self->btw)
			$footer_text .= ' | BTW: ' . $relation_self->btw;

		$pdf->setOption('footer-font-size', 8);
		$pdf->setOption('footer-left', $footer_text);
		$pdf->setOption('footer-right', 'Pagina [page]/[toPage]');
		$pdf->setOption('lowquality', false);
		$pdf->save('user-content/'.$newname);

		$resource = new Resource;
		$resource->resource_name = $newname;
		$resource->file_location = 'user-content/' . $newname;
		$resource->file_size = filesize('user-content/' . $newname);
		$resource->user_id = Auth::id();
		$resource->description = 'Factuurversie';

		$resource->save();

		$invoice_version->resource_id = $resource->id;

		$invoice_version->save();

		return redirect('invoice/project-'.$project->id.'/invoice-version-'.$invoice_version->id);
	}

	public function doInvoiceNewTerm(Request $request)
	{
		$this->validate($request, [
			'projectid' => array('required','integer')
		]);

		$project = Project::find($request->get('projectid'));
		if (!$project || !$project->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$offer_last = Offer::where('project_id',$project->id)->orderBy('created_at', 'desc')->first();
		$cnt = Invoice::where('offer_id', $offer_last->id)->where('isclose',false)->count();
		if ($cnt>0) {
			$invoice = Invoice::where('offer_id',$offer_last->id)->where('isclose',false)->orderBy('priority', 'desc')->first();

			$ninvoice = new Invoice;
			$ninvoice->payment_condition = $invoice->payment_condition;
			$ninvoice->invoice_code = $invoice->invoice_code;
			$ninvoice->priority = $invoice->priority+1;
			$ninvoice->offer_id = $invoice->offer_id;
			$ninvoice->to_contact_id = $invoice->to_contact_id;
			$ninvoice->from_contact_id = $invoice->from_contact_id;
			$ninvoice->save();
		} else {
			$invoice = Invoice::where('offer_id',$offer_last->id)->where('isclose',true)->first();

			$ninvoice = new Invoice;
			$ninvoice->payment_condition = $invoice->payment_condition;
			$ninvoice->invoice_code = $invoice->invoice_code;
			$ninvoice->priority = 0;
			$ninvoice->offer_id = $invoice->offer_id;
			$ninvoice->to_contact_id = $invoice->to_contact_id;
			$ninvoice->from_contact_id = $invoice->from_contact_id;
			$ninvoice->save();
		}

		return back();
	}

	public function doCreditInvoiceNew(Request $request)
	{
		$this->validate($request, [
			'projectid' => array('required','integer'),	
			'id' => array('required','integer'),
		]);

		$project = Project::find($request->get('projectid'));
		if (!$project || !$project->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$invoice = Invoice::find($request->get('id'));
		if (!$invoice) {
			return response()->json(['success' => 0]);
		}

		$ninvoice = new Invoice;
		$ninvoice->amount = ($invoice->amount - $invoice->amount - $invoice->amount);
		$ninvoice->payment_condition = 0;
		$ninvoice->invoice_code = $invoice->invoice_code;
		$ninvoice->priority = $invoice->priority;
		$ninvoice->offer_id = $invoice->offer_id;
		$ninvoice->to_contact_id = $invoice->to_contact_id;
		$ninvoice->from_contact_id = $invoice->from_contact_id;
		$ninvoice->invoice_close = true;
		$ninvoice->invoice_code = InvoiceController::getInvoiceCode($project->id);
		$ninvoice->bill_date = date('Y-m-d H:i:s');
		$ninvoice->rest_21 = InvoiceTerm::partTax1($project, $invoice)*$ninvoice->amount;
		$ninvoice->rest_6 = InvoiceTerm::partTax2($project, $invoice)*$ninvoice->amount;
		$ninvoice->rest_0 = InvoiceTerm::partTax3($project, $invoice)*$ninvoice->amount;
		$ninvoice->save();


		$newname = Auth::id().'-'.substr(md5(uniqid()), 0, 5).'-'.$invoice->invoice_code.'-credit-invoice.pdf';
		$pdf = PDF::loadView('calc.invoice_credit_final_pdf', ['invoice' => $ninvoice]);

		$relation_self = Relation::find(Auth::User()->self_id);
		$footer_text = $relation_self->company_name;
		if ($relation_self->iban)
			$footer_text .= ' | Rekeningnummer: ' . $relation_self->iban;
		if ($relation_self->kvk)
			$footer_text .= ' | KVK: ' . $relation_self->kvk;
		if ($relation_self->btw)
			$footer_text .= ' | BTW: ' . $relation_self->btw;

		$pdf->setOption('footer-font-size', 8);
		$pdf->setOption('footer-left', $footer_text);
		$pdf->setOption('footer-right', 'Pagina [page]/[toPage]');
		$pdf->setOption('lowquality', false);
		$pdf->save('user-content/'.$newname);

		$resource = new Resource;
		$resource->resource_name = $newname;
		$resource->file_location = 'user-content/' . $newname;
		$resource->file_size = filesize('user-content/' . $newname);
		$resource->user_id = Auth::id();
		$resource->description = 'Factuurversie';

		$resource->save();

		$ninvoice->resource_id = $resource->id;

		$ninvoice->save();

		Auth::user()->invoice_counter++;
		Auth::user()->save();

		return response()->json(['success' => 1]);
	}

	public function doInvoiceDeleteTerm(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer')
		]);

		$invoice = Invoice::find($request->get('id'));
		if (!$invoice)
			return response()->json(['success' => 0]);
		$offer = Offer::find($invoice->offer_id);
		if (!$offer)
			return response()->json(['success' => 0]);
		$project = Project::find($offer->project_id);
		if (!$project || !$project->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$invoice->delete();

		return back()->with('success', 'Termijnfactuur verwijderd');
	}

	public function doUpdateAmount(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer'),
			'project' => array('required','integer'),
			'amount' => array('required'),
			'totaal' => array('required'),
		]);

		$invoice = Invoice::find($request->get('id'));
		if (!$invoice)
			return response()->json(['success' => 0]);
		$offer = Offer::find($invoice->offer_id);
		if (!$offer)
			return response()->json(['success' => 0]);
		$project = Project::find($offer->project_id);
		if (!$project || !$project->isOwner()) {
			return response()->json(['success' => 0]);
		}
		$project = Project::find($request->get('project'));
		if (!$project || !$project->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));
		$total = str_replace(',', '.', str_replace('.', '' , $request->get('totaal')));

		$invoice->amount = $amount;
		$invoice->rest_21 = InvoiceTerm::partTax1($project, $invoice)*$amount;
		$invoice->rest_6 = InvoiceTerm::partTax2($project, $invoice)*$amount;
		$invoice->rest_0 = InvoiceTerm::partTax3($project, $invoice)*$amount;
		$invoice->save();

		return response()->json(['success' => 1]);
	}

	public function doInvoiceClose(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer'),
			'projectid' => array('required','integer'),
		]);

		$invoice = Invoice::find($request->get('id'));
		if (!$invoice)
			return response()->json(['success' => 0]);
		$offer = Offer::find($invoice->offer_id);
		if (!$offer)
			return response()->json(['success' => 0]);
		$project = Project::find($offer->project_id);
		if (!$project || !$project->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$project = Project::find($request->get('projectid'));
		if (!$project || !$project->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$invoice_version = InvoiceVersion::where('invoice_id',$invoice->id)->orderBy('created_at','desc')->first();
		$invoice->include_tax = $invoice_version->include_tax;
		$invoice->only_totals = $invoice_version->only_totals;
		$invoice->seperate_subcon = $invoice_version->seperate_subcon;
		$invoice->display_worktotals = $invoice_version->display_worktotals;
		$invoice->display_specification = $invoice_version->display_specification;
		$invoice->display_description = $invoice_version->display_description;
		$invoice->description = $invoice_version->description;
		$invoice->closure = $invoice_version->closure;
		$invoice->to_contact_id = $invoice_version->to_contact_id;
		$invoice->from_contact_id = $invoice_version->from_contact_id;
		$invoice->invoice_close = true;
		$invoice->invoice_code = InvoiceController::getInvoiceCode($project->id);
		$invoice->bill_date = date('Y-m-d H:i:s');

		$invoice->save();

		$newname = Auth::id().'-'.substr(md5(uniqid()), 0, 5).'-'.$invoice->invoice_code.'-invoice.pdf';
		if ($invoice->isclose) {
			$pdf = PDF::loadView('calc.invoice_final_pdf', ['invoice' => $invoice]);
		} else {
			$pdf = PDF::loadView('calc.invoice_term_final_pdf', ['invoice' => $invoice]);
		}

		$relation_self = Relation::find(Auth::User()->self_id);
		$footer_text = $relation_self->company_name;
		if ($relation_self->iban)
			$footer_text .= ' | Rekeningnummer: ' . $relation_self->iban;
		if ($relation_self->kvk)
			$footer_text .= ' | KVK: ' . $relation_self->kvk;
		if ($relation_self->btw)
			$footer_text .= ' | BTW: ' . $relation_self->btw;

		$pdf->setOption('footer-font-size', 8);
		$pdf->setOption('footer-left', $footer_text);
		$pdf->setOption('footer-right', 'Pagina [page]/[toPage]');
		$pdf->setOption('lowquality', false);
		$pdf->save('user-content/'.$newname);

		$resource = new Resource;
		$resource->resource_name = $newname;
		$resource->file_location = 'user-content/' . $newname;
		$resource->file_size = filesize('user-content/' . $newname);
		$resource->user_id = Auth::id();
		$resource->description = 'Factuurversie';

		$resource->save();

		$invoice->resource_id = $resource->id;

		$invoice->save();

		Auth::user()->invoice_counter++;
		Auth::user()->save();

		return redirect('/invoice/project-'.$project->id.'/pdf-invoice-'.$invoice->id);
	}

	public function doInvoicePay(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer'),
			'projectid' => array('required','integer'),
		]);

		$invoice = Invoice::find($request->get('id'));
		if (!$invoice)
			return response()->json(['success' => 0]);
		$offer = Offer::find($invoice->offer_id);
		if (!$offer)
			return response()->json(['success' => 0]);
		$project = Project::find($offer->project_id);
		if (!$project || !$project->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$invoice->payment_date = date('Y-m-d');

		$invoice->save();

		return response()->json(['success' => 1, 'payment' => date('d-m-Y')]);
	}

	public function doInvoiceCloseAjax(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer'),
			'projectid' => array('required','integer'),
		]);

		$invoice = Invoice::find($request->get('id'));
		if (!$invoice)
			return response()->json(['success' => 0]);
		$offer = Offer::find($invoice->offer_id);
		if (!$offer)
			return response()->json(['success' => 0]);
		$project = Project::find($offer->project_id);
		if (!$project || !$project->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$project = Project::find($request->get('projectid'));
		if (!$project || !$project->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$invoice->invoice_close = true;
		$invoice->invoice_code = InvoiceController::getInvoiceCode($project->id);
		$invoice->bill_date = date('Y-m-d H:i:s');

		$invoice->save();
		Auth::user()->invoice_counter++;
		Auth::user()->save();

		return response()->json(['success' => 1, 'billing' => date('d-m-Y')]);
	}

	public function doSendOffer(Request $request)
	{
		$invoice = Invoice::find($request->input('invoice'));
		if (!$invoice)
			return response()->json(['success' => 0]);
		$offer = Offer::find($invoice->offer_id);
		if (!$offer)
			return response()->json(['success' => 0]);
		$project = Project::find($offer->project_id);
		if (!$project || !$project->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$res = Resource::find($invoice->resource_id);
		$contact_client = Contact::find($invoice->to_contact_id);
		$contact_user = Contact::find($invoice->from_contact_id);

		$user_logo = '';
		$relation_self = Relation::find(Auth::user()->self_id);
		if ($relation_self->logo_id)
			$user_logo = Resource::find($relation_self->logo_id)->file_location;

		$other_contacts = [];
		if ($request->has('contacts')) {
			foreach ($request->get('contacts') as $key) {
				$contact = Contact::find($key[0]);
				if (!in_array($contact->email, $other_contacts)) {
					$other_contacts[$contact->email] = $contact->getFormalName();
				}
			}
		}

		$data = array(
			'email' => $contact_client->email,
			'email_from' => $contact_user->email,
			'mycomp' => $relation_self->company_name,
			'pdf' => $res->file_location,
			'other_contacts' => $other_contacts,
			'preview' => false,
			'invoice_id' => $invoice->id,
			'client'=> $contact_client->getFormalName(),
			'project_name' => $project->project_name,
			'user' => $contact_user->getFormalName(),
			'pref_email_invoice' => Auth::User()->pref_email_invoice,
			'user_logo' => $user_logo
		);
		Mailgun::send('mail.invoice_send', $data, function($message) use ($data) {
			$message->to($data['email'], strtolower(trim($data['client'])));
			foreach ($data['other_contacts'] as $email => $name) {
				$message->cc($email, strtolower(trim($name)));
			}
			$message->bcc($data['email_from'], $data['mycomp']);
			$message->attach($data['pdf']);
			$message->subject('Factuur ' . $data['project_name']);
			$message->from('noreply@calculatietool.com', $data['mycomp']);
			$message->replyTo($data['email_from'], $data['mycomp']);
		});

		return response()->json(['success' => 1]);
	}

	public function getSendOfferPreview(Request $request, $project_id, $invoice_id)
	{
		$invoice = Invoice::find($invoice_id);
		if (!$invoice)
			return response()->json(['success' => 0]);
		$offer = Offer::find($invoice->offer_id);
		if (!$offer)
			return response()->json(['success' => 0]);
		$project = Project::find($offer->project_id);
		if (!$project || !$project->isOwner()) {
			return response()->json(['success' => 0]);
		}
		$relation = Relation::find($project->client_id);
		$contacts = Contact::where('relation_id',$relation->id)->where('id','<>',$invoice->to_contact_id)->get();		

		$contact_client = Contact::find($invoice->to_contact_id);
		$contact_user = Contact::find($invoice->from_contact_id);

		$user_logo = '';
		$relation_self = Relation::find(Auth::user()->self_id);
		if ($relation_self->logo_id)
			$user_logo = Resource::find($relation_self->logo_id)->file_location;

		$data = array(
			'email' => $contact_client->email,
			'email_from' => $contact_user->email,
			'preview' => true,
			'invoice_id' => $invoice->id,
			'contacts' => $contacts,
			'client'=> $contact_client->getFormalName(),
			'project_name' => $project->project_name,
			'user' => $contact_user->getFormalName(),
			'pref_email_invoice' => Auth::User()->pref_email_invoice,
			'user_logo' => $user_logo
		);
		return view('mail.invoice_send', $data);
	}

	public function doSendPostOffer(Request $request)
	{
		$invoice = Invoice::find($request->get('invoice'));
		if (!$invoice)
			return response()->json(['success' => 0]);
		$offer = Offer::find($invoice->offer_id);
		if (!$offer)
			return response()->json(['success' => 0]);
		$project = Project::find($offer->project_id);
		if (!$project || !$project->isOwner()) {
			return response()->json(['success' => 0]);
		}
		$user = User::find($project->user_id);

		if (InvoicePost::where('invoice_id', $invoice->id)->count()>0) {
			return response()->json(['success' => 0,'message' => 'Factuur al aangeboden']);
		}
		$post = new InvoicePost;
		$post->invoice_id = $invoice->id;

		$post->save();

		foreach (User::where('user_type','=',UserType::where('user_type','=','admin')->first()->id)->get() as $admin) {

			$message = new MessageBox;
			$message->subject = 'Te printen factuur';
			$message->message = 'Factuur ' . $invoice->invoice_code . ' van gebruiker ' . $user->username . ' staat klaar om geprint te worden';
			$message->from_user = User::where('username', 'admin')->first()['id'];
			$message->user_id =	$admin->id;

			$message->save();
		}

	    $data = array(
	        'code' => $invoice->invoice_code,
	        'user' => $user->username
	    );
	    Mailgun::send('mail.print', $data, function($message) use ($data) {
	        $message->to('info@calculatietool.com', 'CalculatieTool.com');
	        $message->subject('CalculatieTool.com - Printopdracht');
	        $message->from('info@calculatietool.com', 'CalculatieTool.com');
	        $message->replyTo('info@calculatietool.com', 'CalculatieTool.com');
	    });

		return response()->json(['success' => 1]);
	}

	/* id = $project->id */
	public static function getInvoiceCode($id)
	{
		return sprintf("%s%05d-%03d-%s", Auth::user()->invoicenumber_prefix, $id, Auth::user()->invoice_counter, date('y'));
	}

	/* id = $project->id */
	public static function getInvoiceCodeConcept($id, $user = null)
	{
		if (!$user) {
			$user = Auth::user();
		}
		return sprintf("%s%05d-CONCEPT-%s", $user->invoicenumber_prefix, $id, date('y'));
	}

}
