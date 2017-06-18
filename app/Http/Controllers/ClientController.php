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

use BynqIO\Dynq\Models\User;
use BynqIO\Dynq\Models\MessageBox;
use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\Models\Offer;
use BynqIO\Dynq\Models\Invoice;
use BynqIO\Dynq\Calculus\InvoiceTerm;
use BynqIO\Dynq\Models\ProjectShare;
use Illuminate\Http\Request;

use Auth;
use Mail;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /relation
     *
     * @return Response
     */
    public function getClientPage(Request $request)
    {
        return view('user.client_page');
    }

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
        $message->message = 'De opdrachtgever heeft een opmerking geplaatst bij project <a href="/project-' . $project->id . '/edit">' . $project->project_name . '</a>.
            <br />Voor het geven van een reactie gaat u naar uw projectgegevens. Daar staat een tabblad <b><i>communicatie opdrachgever</i></b>.';
        $message->from_user = User::where('username', 'admin')->first()['id'];
        $message->user_id =	$project->user_id;

        $message->save();

        $user = User::find($project->user_id);

        $data = array(
            'email' => $user->email,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'project_name' => $project->project_name,
            'note' => nl2br($request->input('client_note'))
        );
        Mail::send('mail.client_reacted', $data, function($message) use ($data) {
            $message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
            $message->subject(config('app.name') . ' - Uw opdrachtgever heeft gereageerd');
            $message->from(APP_EMAIL);
        });

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
        $message->from_user = User::where('username', 'admin')->first()['id'];
        $message->user_id =	$project->user_id;

        $message->save();

        $user = User::find($project->user_id);

        $data = array('email' => $user->email, 'firstname' => $user->firstname, 'lastname' => $user->lastname, 'project_name' => $project->project_name);
        Mail::send('mail.offer_accepted', $data, function($message) use ($data) {
            $message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
            $message->subject(config('app.name') . ' - Offerte bevestigd');
            $message->from(APP_EMAIL);
        });
        
        return back()->with('success', 'Offerte is bevestigd');
    }

}
