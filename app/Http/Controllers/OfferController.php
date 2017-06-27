<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the Dynq project.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  Dynq
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
 */

namespace BynqIO\Dynq\Http\Controllers;

use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\Models\Offer;
use BynqIO\Dynq\Models\OfferPost;
use BynqIO\Dynq\Calculus\CalculationEndresult;
use BynqIO\Dynq\Models\Resource;
use BynqIO\Dynq\Http\Controllers\InvoiceController;
use BynqIO\Dynq\Models\Invoice;
use BynqIO\Dynq\Models\ProjectShare;
use BynqIO\Dynq\Models\Contact;
use BynqIO\Dynq\Models\User;
use BynqIO\Dynq\Models\UserType;
use BynqIO\Dynq\Models\Relation;
use BynqIO\Dynq\Models\MessageBox;
use BynqIO\Dynq\Calculus\ResultEndresult;
use BynqIO\Dynq\Calculus\InvoiceTerm;
use Illuminate\Http\Request;

use Auth;
use PDF;
use Mail;

class OfferController extends Controller
{
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

    // public function doNewOffer(Request $request, $projectid)
    // {
    //     $this->validate($request, [
    //         'deliver' => array('required','integer','min:0'),
    //         'terms' => array('integer','min:0','max:50'),
    //         'valid' => array('required','integer','min:0'),
    //         'to_contact' => array('required'),
    //         'from_contact' => array('required'),
    //         'amount' => array('regex:/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9])?$/'),
    //     ]);

    //     $project = Project::find($projectid);
    //     if (!$project || !$project->isOwner()) {
    //         return back()->withInput($request->all());
    //     }

    //     $offer = new Offer;
    //     $offer->to_contact_id = $request->get('to_contact');
    //     $offer->from_contact_id = $request->get('from_contact');
    //     $offer->description = $request->get('description');
    //     $offer->offer_code = OfferController::getOfferCode($project->id);
    //     $offer->extracondition = $request->get('extracondition');
    //     $offer->closure = $request->get('closure');
    //     if ($request->get('offdateval'))
    //         $offer->offer_make = date('Y-m-d', strtotime($request->get('offdateval')));
    //     else
    //         $offer->offer_make = date('Y-m-d');
    //     if ($request->get('toggle-payment'))
    //         $offer->downpayment = $request->get('toggle-payment');
    //     if ($request->get('amount'))
    //         $offer->downpayment_amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));
    //     $offer->auto_email_reminder = false;
    //     $offer->deliver_id = $request->get('deliver');
    //     $offer->valid_id = $request->get('valid');
    //     if ($request->get('terms'))
    //         $offer->invoice_quantity = $request->get('terms');
    //     $offer->project_id = $project->id;;

    //     if ($request->get('include-tax'))
    //         $offer->include_tax = true;
    //     else
    //         $offer->include_tax = false;
    //     if ($request->get('only-totals'))
    //         $offer->only_totals = true;
    //     else
    //         $offer->only_totals = false;
    //     if ($request->get('seperate-subcon'))
    //         $offer->seperate_subcon = true;
    //     else
    //         $offer->seperate_subcon = false;
    //     if ($request->get('display-worktotals'))
    //         $offer->display_worktotals = true;
    //     else
    //         $offer->display_worktotals = false;
    //     if ($request->get('display-specification'))
    //         $offer->display_specification = true;
    //     else
    //         $offer->display_specification = false;
    //     if ($request->get('display-description'))
    //         $offer->display_description = true;
    //     else
    //         $offer->display_description = false;

    //     $offer->offer_total = CalculationEndresult::totalProject($project);
    //     $offer->save();

    //     $page = 0;
    //     $newname = Auth::id().'-'.mb_substr(md5(uniqid()), 0, 5).'-'.OfferController::getOfferCode($request->input('project_id')).'-offer.pdf';
    //     $pdf = PDF::loadView('calc.offer_pdf', ['offer' => $offer]);

    //     $relation_self = Relation::find(Auth::User()->self_id);
    //     $footer_text = $relation_self->company_name;
    //     if ($relation_self->iban)
    //         $footer_text .= ' | IBAN: ' . $relation_self->iban;
    //     if ($relation_self->kvk)
    //         $footer_text .= ' | KVK: ' . $relation_self->kvk;
    //     if ($relation_self->btw)
    //         $footer_text .= ' | BTW: ' . $relation_self->btw;

    //     $pdf->setOption('zoom', 1.1);
    //     $pdf->setOption('footer-font-size', 8);
    //     $pdf->setOption('footer-left', $footer_text);
    //     $pdf->setOption('footer-right', 'Pagina [page]/[toPage]');
    //     $pdf->setOption('lowquality', false);
    //     $pdf->save('user-content/'.$newname);

    //     $resource = new Resource;
    //     $resource->resource_name = $newname;
    //     $resource->file_location = 'user-content/' . $newname;
    //     $resource->file_size = filesize('user-content/' . $newname);
    //     $resource->user_id = Auth::id();
    //     $resource->description = 'Offerteversie';

    //     $resource->save();

    //     $offer->resource_id = $resource->id;

    //     $offer->save();

    //     Auth::user()->offer_counter++;
    //     Auth::user()->save();

    //     return redirect('/offer/project-'.$project->id.'/offer-'.$offer->id);
    // }

    // public function doOfferClose(Request $request)
    // {
    //     $this->validate($request, [
    //         'date' => array('required'),
    //         'offer' => array('required','integer'),
    //         'project' => array('required','integer'),
    //     ]);

    //     $offer = Offer::find($request->get('offer'));
    //     if (!$offer)
    //         return response()->json(['success' => 0]);
    //     $project = Project::find($offer->project_id);
    //     if (!$project || !$project->isOwner()) {
    //         return response()->json(['success' => 0]);
    //     }

    //     $project = Project::find($request->get('project'));
    //     if (!$project || !$project->isOwner()) {
    //         return response()->json(['success' => 0]);
    //     }

    //     $offer->offer_finish = date('Y-m-d', strtotime($request->get('date')));
    //     $offer->save();

    //     for ($i=0; $i < $offer->invoice_quantity; $i++) {
    //         $invoice = new Invoice;
    //         $invoice->priority = $i;
    //         $invoice->invoice_code = InvoiceController::getInvoiceCodeConcept($project->id);
    //         $invoice->payment_condition = 30;
    //         $invoice->offer_id = $offer->id;
    //         $invoice->to_contact_id = $offer->to_contact_id;
    //         $invoice->from_contact_id = $offer->from_contact_id;
    //         if (($i+1) == $offer->invoice_quantity) {
    //             $invoice->isclose = true;
    //             $invoice->priority = 100;
    //         }
    //         if ($i == 0 && $offer->downpayment) {
    //             $invoice->amount = $offer->downpayment_amount;
    //             $invoice->rest_21 = InvoiceTerm::partTax1($project, $invoice) * $offer->downpayment_amount;
    //             $invoice->rest_6 = InvoiceTerm::partTax2($project, $invoice) * $offer->downpayment_amount;
    //             $invoice->rest_0 = InvoiceTerm::partTax3($project, $invoice) * $offer->downpayment_amount;
    //         }
    //         $invoice->save();
    //     }

    //     return response()->json(['success' => 1]);
    // }

    public function doSendOffer(Request $request)
    {
        $offer = Offer::find($request->input('offer'));
        if (!$offer)
            return response()->json(['success' => 0]);
        $project = Project::find($offer->project_id);
        if (!$project || !$project->isOwner()) {
            return response()->json(['success' => 0]);
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
        $user_agreement = null;
        $relation_self = Relation::find(Auth::user()->self_id);
        if ($relation_self->logo_id)
            $user_logo = Resource::find($relation_self->logo_id)->file_location;
        if ($relation_self->agreement_id)
            $user_agreement = Resource::find($relation_self->agreement_id)->file_location;

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
            'client' => $contact_client->getFormalName(),
            'other_contacts' => $other_contacts,
            'mycomp' => $relation_self->company_name,
            'pdf' => $res->file_location,
            'agreement' => $user_agreement,
            'preview' => false,
            'offer_id' => $offer->id,
            'token' => $share->token,
            'user' => $contact_user->firstname . ' ' . $contact_user->lastname,
            'project_name' => $project->project_name,
            'pref_email_offer' => Auth::User()->pref_email_offer,
            'user_logo' => $user_logo
        );
        Mail::send('mail.offer_send', $data, function($message) use ($data) {
            $message->to($data['email'], mb_strtolower(trim($data['client'])));
            foreach ($data['other_contacts'] as $email => $name) {
                $message->cc($email, mb_strtolower(trim($name)));
            }
            $message->bcc($data['email_from'], $data['mycomp']);
            $message->attach($data['pdf']);
            if (!empty($data['agreement']))
                $message->attach($data['agreement']);
            $message->subject('Offerte ' . $data['project_name']);
            $message->from('noreply@calculatietool.com', $data['mycomp']);
            $message->replyTo($data['email_from'], $data['mycomp']);
        });

        return response()->json(['success' => 1]);
    }

    public function getSendOfferPreview(Request $request, $project_id, $offer_id)
    {
        $offer = Offer::find($offer_id);
        if (!$offer)
            return response()->json(['success' => 0]);
        $project = Project::find($project_id);
        if (!$project || !$project->isOwner()) {
            return response()->json(['success' => 0]);
        }
        $relation = Relation::find($project->client_id);
        $contacts = Contact::where('relation_id',$relation->id)->where('id','<>',$offer->to_contact_id)->get();

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
            'email' => $contact_client->email,
            'client'=> $contact_client->getFormalName(),
            'contacts' => $contacts,
            'preview' => true,
            'offer_id' => $offer->id,
            'token' => $share->token,
            'project_name' => $project->project_name,
            'user' => $contact_user->firstname . ' ' . $contact_user->lastname,
            'pref_email_offer' => Auth::User()->pref_email_offer,
            'user_logo' => $user_logo
        );
        return view('mail.offer_send', $data);
    }

    /* id = $project->id */
    // public static function getOfferCode($id)
    // {
    //     return sprintf("%s%05d-%03d-%s", Auth::user()->offernumber_prefix, $id, Auth::user()->offer_counter, date('y'));
    // }
}
