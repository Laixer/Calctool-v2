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

namespace BynqIO\Dynq\Http\Controllers\Quotation;

use Carbon\Carbon;
use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\Models\Invoice;
use BynqIO\Dynq\Models\Offer;
use BynqIO\Dynq\Models\Relation;
use BynqIO\Dynq\Models\Contact;
use BynqIO\Dynq\Models\Resource;
use BynqIO\Dynq\Calculus\InvoiceTerm;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Encryptor;
use PDF;

class ConfirmController extends Controller
{
    protected function invoiceNumber($id, $user)
    {
        return sprintf("%s%05d-%03d-%s", $user->offernumber_prefix, $id, $user->offer_counter, date('y'));
    }

    protected function invoiceConceptNumber($id, $user)
    {
        return sprintf("%s%05d-CONCEPT-%03d-%s", $user->invoicenumber_prefix, $id, $user->invoice_counter, date('y'));
    }

    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'date'    => ['required'],
            'project' => ['required','integer'],
        ]);

        $project = Project::findOrFail($request->get('project'));
        if (!$project->isOwner()) {
            return back();
        }

        $offer = Offer::where('project_id', $project->id)->orderBy('created_at', 'desc')->firstOrFail();
        $offer->offer_finish     = date('Y-m-d', strtotime($request->get('date')));
        $offer->save();

        for ($i = 0; $i < $offer->invoice_quantity; ++$i) {
            $invoice = new Invoice;
            $invoice->priority          = $i;
            $invoice->invoice_code      = $this->invoiceConceptNumber($project->id, $request->user());
            $invoice->payment_condition = 30;
            $invoice->offer_id          = $offer->id;
            $invoice->to_contact_id     = $offer->to_contact_id;
            $invoice->from_contact_id   = $offer->from_contact_id;

            if (($i + 1) == $offer->invoice_quantity) {
                $invoice->isclose = true;
                $invoice->priority = 100;
            }

            if ($i == 0 && $offer->downpayment) {
                $invoice->amount = $offer->downpayment_amount;
                $invoice->rest_21 = InvoiceTerm::partTax1($project, $invoice) * $offer->downpayment_amount;
                $invoice->rest_6 = InvoiceTerm::partTax2($project, $invoice) * $offer->downpayment_amount;
                $invoice->rest_0 = InvoiceTerm::partTax3($project, $invoice) * $offer->downpayment_amount;
            }

            $invoice->save();
        }

        return back()->with('success', 'Opdracht bevestigd');
    }

}
