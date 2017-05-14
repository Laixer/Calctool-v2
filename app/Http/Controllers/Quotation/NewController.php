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

use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\Calculus\CalculationEndresult;
use BynqIO\Dynq\Models\Offer;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'project' => ['integer'],
            'terms' => ['integer'],
            'amount' => ['regex:/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9])?$/'],
            'deliver' => ['integer'],
            'valid' => ['integer'],
        ]);

        $project = Project::findOrFail($request->get('project'));
        if (!$project->isOwner()) {
            return back()->withInput($request->all());
        }

        $offer = new Offer;
        $offer->to_contact_id = $request->get('contact_to');
        $offer->from_contact_id = $request->get('contact_from');
        // $offer->description = $request->get('description');

        $offer->offer_code = sprintf("%s%05d-%03d-%s", $request->user()->offernumber_prefix, $project->id, $request->user()->offer_counter, date('y'));
        // $offer->extracondition = $request->get('extracondition');
        // $offer->closure = $request->get('closure');

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

        // $page = 0;
        // $newname = Auth::id().'-'.substr(md5(uniqid()), 0, 5).'-'.OfferController::getOfferCode($request->input('project_id')).'-offer.pdf';
        // $pdf = PDF::loadView('calc.offer_pdf', ['offer' => $offer]);

        // $relation_self = Relation::find(Auth::User()->self_id);
        // $footer_text = $relation_self->company_name;
        // if ($relation_self->iban)
        //     $footer_text .= ' | IBAN: ' . $relation_self->iban;
        // if ($relation_self->kvk)
        //     $footer_text .= ' | KVK: ' . $relation_self->kvk;
        // if ($relation_self->btw)
        //     $footer_text .= ' | BTW: ' . $relation_self->btw;

        // $pdf->setOption('zoom', 1.1);
        // $pdf->setOption('footer-font-size', 8);
        // $pdf->setOption('footer-left', $footer_text);
        // $pdf->setOption('footer-right', 'Pagina [page]/[toPage]');
        // $pdf->setOption('lowquality', false);
        // $pdf->save('user-content/'.$newname);

        // $resource = new Resource;
        // $resource->resource_name = $newname;
        // $resource->file_location = 'user-content/' . $newname;
        // $resource->file_size = filesize('user-content/' . $newname);
        // $resource->user_id = Auth::id();
        // $resource->description = 'Offerteversie';

        // $resource->save();

        // $offer->resource_id = $resource->id;

        $offer->save();

        $request->user()->offer_counter++;
        $request->user()->save();

        return redirect("project/{$project->id}-{$project->slug()}/quotations");
    }

}
