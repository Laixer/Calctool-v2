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
    const PREFIX = 'PROJ';

    /**
     * Display a listing of the resource.
     * GET /relation
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $type_name = null;
        switch ($request->get('type')) {
            case 'calculate':
                 $type_name = __('components.calculate');
                break;
            case 'quickinvoice':
                $type_name = __('components.quickinvoice');
                break;
            case 'directwork':
                $type_name = __('components.directwork');
                break;
            default:
                abort(404);
        }

        return view('project.new', [
            'type_name'   => $type_name,
            'debtor_code' => mt_rand(1000000, 9999999)
        ]);
    }

    //TODO: move to helper
    protected function random_name($size = 4)
    {
        return chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90)) . chr(rand(65,90));
    }

    private function relationPreferences($relation, $project)
    {
        if ($relation->hour_rate > 0) {
            $project->hour_rate = $relation->hour_rate;
        }
        if ($relation->hour_rate_more > 0) {
            $project->hour_rate_more = $relation->hour_rate_more;
        }
        if ($relation->profit_calc_contr_mat) {
            $project->profit_calc_contr_mat = $relation->profit_calc_contr_mat;
        }
        if ($relation->profit_calc_contr_equip) {
            $project->profit_calc_contr_equip = $relation->profit_calc_contr_equip;
        }
        if ($relation->profit_calc_subcontr_mat) {
            $project->profit_calc_subcontr_mat = $relation->profit_calc_subcontr_mat;
        }
        if ($relation->profit_calc_subcontr_equip) {
            $project->profit_calc_subcontr_equip = $relation->profit_calc_subcontr_equip;
        }
        if ($relation->profit_more_contr_mat) {
            $project->profit_more_contr_mat = $relation->profit_more_contr_mat;
        }
        if ($relation->profit_more_contr_equip) {
            $project->profit_more_contr_equip = $relation->profit_more_contr_equip;
        }
        if ($relation->profit_more_subcontr_mat) {
            $project->profit_more_subcontr_mat = $relation->profit_more_subcontr_mat;
        }
        if ($relation->profit_more_subcontr_equip) {
            $project->profit_more_subcontr_equip = $relation->profit_more_subcontr_equip;
        }
    }

    public function new(Request $request)
    {
        $validator = $this->validate($request, [
            'street'          => ['required', 'max:60'],
            'address_number'  => ['required', 'alpha_num', 'max:5'],
            'zipcode'         => ['required'],
            'city'            => ['required', 'max:35'],
            'province'        => ['required', 'numeric'],
            'country'         => ['required', 'numeric'],
            'contractor'      => ['required', 'numeric'],
            'name'            => ['max:50'],
            'type'            => ['required'],
        ]);

        $project = new Project;
        $project->address_street             = $request->input('street');
        $project->address_number             = $request->input('address_number');
        $project->address_postal             = $request->input('zipcode');
        $project->address_city               = $request->input('city');
        $project->user_id                    = $request->user()->id;
        $project->province_id                = $request->input('province');
        $project->country_id                 = $request->input('country');
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

        $relation = Relation::findOrFail($project->client_id);
        if ($request->has('tax_reverse')) {
            if (RelationKind::find($relation->kind_id)->kind_name == 'particulier') {
                return back()->withErrors(['error' => 'BTW kan niet worden verlegd naar een particulier opdrachtgever'])->withInput($request->all());
            }

            if (!trim($relation->btw)) {
                return back()->withErrors(['error' => 'Opdrachtgever heeft geen BTW nummer'])->withInput($request->all());
            }

            $project->tax_reverse = true;
        }

        if (!$request->has('name')) {
            $project->project_name = self::PREFIX . date("Ymd") . '-' . $this->random_name();
        } else {
            $project->project_name = $request->input('name');
        }

        switch ($request->get('type')) {
            case 'calculate':
                $project->type_id = 2;
                break;
            case 'directwork':
                $project->type_id = 1;
                break;
            case 'quickinvoice':
                $project->type_id = 3;
                break;
        }

        $this->relationPreferences($relation, $project);

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

        return redirect('project/' . $project->id . '-' . $project->slug() . '/details')->with('success', 'Opgeslagen');
    }

}
