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

namespace BynqIO\Dynq\Http\Controllers\Invoice;

use Illuminate\Http\Request;
use BynqIO\Dynq\Models\Offer;
use BynqIO\Dynq\Models\Invoice;
use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\Http\Controllers\Controller;

class NewTermController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'id' => ['required','integer'],
        ]);

        $project = Project::findOrFail($request->get('id'));
        if (!$project->isOwner()) {
            return response()->json(['success' => 0]);
        }

        $offer_last = Offer::where('project_id',$project->id)->orderBy('created_at', 'desc')->first();
        /* Copy last term */
        if (Invoice::where('offer_id', $offer_last->id)->where('isclose',false)->count() > 0) {
            $invoice = Invoice::where('offer_id',$offer_last->id)->where('isclose',false)->orderBy('priority', 'desc')->first();
            $ninvoice = new Invoice;
            $ninvoice->payment_condition = $invoice->payment_condition;
            $ninvoice->invoice_code = $invoice->invoice_code;
            $ninvoice->priority = $invoice->priority + 1;
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

        return back()->with(['success' => 'Termijn toegevoegd']);
    }

}
