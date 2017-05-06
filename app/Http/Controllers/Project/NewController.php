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

namespace BynqIO\Dynq\Http\Controllers\Project;

use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\Models\Offer;
use BynqIO\Dynq\Models\Invoice;
use BynqIO\Dynq\Models\ProjectType;
use BynqIO\Dynq\Models\Relation;
use BynqIO\Dynq\Models\Contact;
use BynqIO\Dynq\Models\Chapter;
use BynqIO\Dynq\Models\Audit;
use BynqIO\Dynq\Models\RelationKind;
use BynqIO\Dynq\Http\Controllers\InvoiceController;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /relation
     *
     * @return Response
     */
    public function index(Request $request)
    {
        return view('project.new', ['debtor_code' => mt_rand(1000000, 9999999)]);
    }

    public function new(Request $request)
    {
        $validator = $this->validate($request, [
            'street' => array('required','max:60'),
            'address_number' => array('required','alpha_num','max:5'),
            'zipcode' => array('required'),
            'city' => array('required','max:35'),
            'province' => array('required','numeric'),
            'country' => array('required','numeric'),
            'contractor' => array('required','numeric'),
            'name' => array('max:50'),
        ]);

        if (!Relation::find($request->user()->self_id)) {
            return back()->withErrors(['error' => 'Mijn bedrijf bestaat niet'])->withInput($request->all());
        }

        $project = new Project;
        $project->address_street             = $request->input('street');
        $project->address_number             = $request->input('address_number');
        $project->address_postal             = $request->input('zipcode');
        $project->address_city               = $request->input('city');
        $project->user_id                    = $request->user()->id;
        $project->province_id                = $request->input('province');
        $project->country_id                 = $request->input('country');
        $project->type_id                    = $request->input('type');
        $project->client_id                  = $request->input('contractor');
        $project->hour_rate                  = $request->user()->pref_hourrate_calc;
        $project->hour_rate_more             = $request->user()->pref_hourrate_more;
        $project->profit_calc_contr_mat      = $request->user()->pref_profit_calc_contr_mat;
        $project->profit_calc_contr_equip    = $request->user()->pref_profit_calc_contr_equip;
        $project->profit_calc_subcontr_mat   = $request->user()->pref_profit_calc_subcontr_mat;
        $project->profit_calc_subcontr_equip = $request->user()->pref_profit_calc_subcontr_equip;
        $project->profit_more_contr_mat      = $request->user()->pref_profit_more_contr_mat;
        $project->profit_more_contr_equip    = $request->user()->pref_profit_more_contr_equip;
        $project->profit_more_subcontr_mat   = $request->user()->pref_profit_more_subcontr_mat;
        $project->profit_more_subcontr_equip = $request->user()->pref_profit_more_subcontr_equip;

        $relation = Relation::find($project->client_id);
        if ($request->input('tax_reverse')) {
            if (RelationKind::find($relation->kind_id)->kind_name == 'particulier') {
                return back()->withErrors(['error' => 'BTW kan niet worden verlegd naar een particulier opdrachtgever'])->withInput($request->all());
            }

            if (!trim($relation->btw)) {
                return back()->withErrors(['error' => 'Opdrachtgever heeft geen BTW nummer'])->withInput($request->all());
            }
        }

        if (!$request->has('name')) {
            $project->project_name = 'PROJ-' . date("Ymd-s");
        } else {
            $project->project_name = $request->input('name');
        }

        if (!$project->hour_rate) {
            $project->hour_rate = 0;
        }

        if ($request->input('tax_reverse'))
            $project->tax_reverse = true;
        else
            $project->tax_reverse = false;

        if (!$request->input('type')) {
            $project->type_id = 2;
        }

        if ($relation->hour_rate)
            $project->hour_rate = $relation->hour_rate;
        if ($relation->hour_rate_more)
            $project->hour_rate_more = $relation->hour_rate_more;
        if ($relation->profit_calc_contr_mat)
            $project->profit_calc_contr_mat = $relation->profit_calc_contr_mat;
        if ($relation->profit_calc_contr_equip)
            $project->profit_calc_contr_equip = $relation->profit_calc_contr_equip;
        if ($relation->profit_calc_subcontr_mat)
            $project->profit_calc_subcontr_mat = $relation->profit_calc_subcontr_mat;
        if ($relation->profit_calc_subcontr_equip)
            $project->profit_calc_subcontr_equip = $relation->profit_calc_subcontr_equip;
        if ($relation->profit_more_contr_mat)
            $project->profit_more_contr_mat = $relation->profit_more_contr_mat;
        if ($relation->profit_more_contr_equip)
            $project->profit_more_contr_equip = $relation->profit_more_contr_equip;
        if ($relation->profit_more_subcontr_mat)
            $project->profit_more_subcontr_mat = $relation->profit_more_subcontr_mat;
        if ($relation->profit_more_subcontr_equip)
            $project->profit_more_subcontr_equip = $relation->profit_more_subcontr_equip;

        $project->save();

        $type = ProjectType::find($project->type_id);
        if ($type->type_name == 'regie') {
            $relation_self = Relation::find($request->user()->self_id);
            $contact = Contact::where('relation_id','=', $relation->id)->first();
            $contact_self = Contact::where('relation_id','=', $relation_self->id)->first();

            $offer = new Offer;
            $offer->to_contact_id = $contact->id;
            $offer->from_contact_id = $contact_self->id;
            $offer->offer_code = 'REGIE';
            $offer->auto_email_reminder = false;
            $offer->deliver_id = 1;
            $offer->valid_id = 1;
            $offer->offer_finish = date('Y-m-d');
            $offer->project_id = $project->id;;
            $offer->offer_total = 0;
            $offer->save();

            $invoice = new Invoice;
            $invoice->priority = 0;
            $invoice->invoice_code = InvoiceController::getInvoiceCodeConcept($project->id);
            $invoice->payment_condition = 30;
            $invoice->offer_id = $offer->id;
            $invoice->to_contact_id = $contact->id;
            $invoice->from_contact_id = $contact_self->id;
            $invoice->isclose = true;
            $invoice->priority = 100;
            $invoice->save();
        }

        Audit::CreateEvent('project.new.success', 'Created project: ' . $project->project_name);

        $title = str_slug($project->project_name, '-');
        return redirect('project/' . $project->id . '-' . $title . '/details')->with('success', 'Opgeslagen');
    }

}
