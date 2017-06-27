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
use BynqIO\Dynq\Calculus\CalculationEndresult;
use BynqIO\Dynq\Models\Offer;
use BynqIO\Dynq\Models\Relation;
use BynqIO\Dynq\Models\Contact;
use BynqIO\Dynq\Models\Resource;
use BynqIO\Dynq\Models\Valid;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Encryptor;
use PDF;

class NewController extends Controller
{
    private function saveReport($user, $quotation, $project)
    {
        $relation = Relation::findOrFail($project->client_id);
        $relation_self = Relation::findOrFail($user->self_id);

        $logo = null;
        if ($relation_self->logo) {
            $logo = Encryptor::base64($relation_self->logo->file_location);
        }

        $data = [
            'logo'     => $logo,
            'company'  => $relation_self->name(),
            'address'  => $relation_self->fullAddress(),
            'phone'    => $relation_self->phone_number,
            'email'    => $relation_self->email,
            'pages'    => ['main'],
        ];

        $letter = [
            'document'         => 'Offerte',
            'document_number'  => $quotation->offer_code,
            'document_date'    => Carbon::now(),
            'project'          => $project,
            'relation'         => $relation,
            'relation_self'    => $relation_self,
            'contact_to'       => Contact::find($quotation->to_contact_id),
            'contact_from'     => Contact::find($quotation->from_contact_id),
            'pretext'          => $quotation->description,
            'posttext'         => $quotation->closure,
        ];

        $terms   = $quotation->invoice_quantity;
        $amount  = $quotation->downpayment_amount;
        $deliver = $quotation->deliver_id;
        $valid   = $quotation->valid_id;

        /* Terms and amount */
        if ($terms > 1 && $amount > 1) {
            $letter['messages'][] = "Indien opdracht gegund wordt, ontvangt u $terms termijnen waarvan de eerste termijn een aanbetaling betreft á € $amount";
        } else if ($terms > 1) {
            $letter['messages'][] = "Indien opdracht gegund wordt, ontvangt u $terms termijnen waarvan de laatste een eindfactuur.";
        } else {
            $letter['messages'][] = "Indien opdracht gegund wordt, ontvangt u één eindfactuur.";
        }

        /* Delivery options */
        if ($deliver == 1 || $deliver == 2) {
            $letter['messages'][] = "De werkzaamheden starten na uw opdrachtbevestiging.";
        } else if (isset($deliver)) {
            $name = DeliverTime::findOrFail($deliver)->delivertime_name;
            $letter['messages'][] = "De werkzaamheden starten binnen $name.";
        }

        /* Valid options */
        if (isset($valid)) {
            $name = Valid::findOrFail($valid)->valid_name;
            $letter['messages'][] = "Deze offerte is geldig tot $name na dagtekening.";
        }

        /* Additional pages */
        if ($quotation->display_specification) {
            $data['pages'][] = 'specification';
        }
        if ($quotation->display_worktotals) {
            $data['pages'][] = 'levelcost';
        }
        if ($quotation->display_description) {
            $data['pages'][] = 'description';
        }

        $pdf = PDF::loadView('letter', array_merge($data, $letter));
        $pdf->setOption('footer-font-size', 8);
        $pdf->setOption('footer-left', $relation_self->name());
        $pdf->setOption('footer-right', 'Pagina [page]/[toPage]');
        $pdf->setOption('encoding', 'utf-8');
        $pdf->setOption('lowquality', false);

        $file = Encryptor::putAuto($user->ownCompany->encodedName(), 'pdf', $pdf->output());

        $resource = new Resource;
        $resource->resource_name  = 'quotation.pdf';
        $resource->file_location  = $file;
        $resource->file_size      = mb_strlen($pdf->output());
        $resource->user_id        = $user->id;
        $resource->description    = 'Offerteversie';

        $resource->save();

        $quotation->resource_id = $resource->id;
    }

    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'project'       => ['required', 'integer'],
            'terms'         => ['integer'],
            'amount'        => ['regex:/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9])?$/'],
            'contact_to'    => ['required'],
            'contact_from'  => ['required'],
        ], [
            'contact_to.required'    => 'Geef een contactpersoon op en klik op Bijwerken',
            'contact_from.required'  => 'Geef een afzender op en klik op Bijwerken',
        ]);

        $project = Project::findOrFail($request->get('project'));
        if (!$project->isOwner()) {
            return back()->withInput($request->all());
        }

        $offer = new Offer;
        $offer->to_contact_id = $request->get('contact_to');
        $offer->from_contact_id = $request->get('contact_from');
        $offer->description = $request->get('pretext');

        $offer->offer_code = sprintf("%s%05d-%03d-%s", $request->user()->offernumber_prefix, $project->id, $request->user()->offer_counter, date('y'));
        // $offer->extracondition = $request->get('extracondition');
        $offer->closure = $request->get('posttext');

        if ($request->get('offdateval')) {
            $offer->offer_make = date('Y-m-d', strtotime($request->get('offdateval')));
        } else {
            $offer->offer_make = date('Y-m-d');
        }

        if ($request->get('toggle-payment')) {
            $offer->downpayment = $request->get('toggle-payment');
        }

        if ($request->get('amount')) {
            $offer->downpayment_amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));
        }

        $offer->auto_email_reminder = false;
        $offer->deliver_id = $request->get('deliver') || 1;
        $offer->valid_id = $request->get('valid') || 1;

        if ($request->get('terms')) {
            $offer->invoice_quantity = $request->get('terms');
        }

        $offer->project_id = $project->id;;

        //TODO: remove from db
        // if ($request->get('include-tax'))
        //     $offer->include_tax = true;
        // else
        //     $offer->include_tax = false;

        // if ($request->get('only-totals'))
        //     $offer->only_totals = true;
        // else
        //     $offer->only_totals = false;

        if ($request->has('seperate-subcon')) {
            $offer->seperate_subcon = true;
        } else {
            $offer->seperate_subcon = false;
        }

        if ($request->has('display-worktotals')) {
            $offer->display_worktotals = true;
        } else {
            $offer->display_worktotals = false;
        }

        if ($request->has('display-specification')) {
            $offer->display_specification = true;
        } else {
            $offer->display_specification = false;
        }

        if ($request->has('display-description')) {
            $offer->display_description = true;
        } else {
            $offer->display_description = false;
        }

        $offer->offer_total = CalculationEndresult::totalProject($project);
        $offer->save();

        $this->saveReport($request->user(), $offer, $project);

        $offer->save();

        $request->user()->offer_counter++;
        $request->user()->save();

        return redirect("project/{$project->id}-{$project->slug()}/quotations");
    }

}
