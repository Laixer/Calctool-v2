<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the BynqIO\CalculatieTool.com.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  CalculatieTool
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
 */

namespace BynqIO\CalculatieTool\Http\Controllers\Project;

use BynqIO\CalculatieTool\Models\Project;
use BynqIO\CalculatieTool\Models\Offer;
use BynqIO\CalculatieTool\Models\Invoice;
use BynqIO\CalculatieTool\Models\ProjectType;
use BynqIO\CalculatieTool\Models\Relation;
use BynqIO\CalculatieTool\Models\Contact;
use BynqIO\CalculatieTool\Models\Chapter;
use BynqIO\CalculatieTool\Models\Audit;
use BynqIO\CalculatieTool\Models\Activity;
use BynqIO\CalculatieTool\Http\Controllers\InvoiceController;
use BynqIO\CalculatieTool\Models\EstimateLabor;
use BynqIO\CalculatieTool\Models\EstimateMaterial;
use BynqIO\CalculatieTool\Models\EstimateEquipment;
use BynqIO\CalculatieTool\Models\MoreLabor;
use BynqIO\CalculatieTool\Models\MoreMaterial;
use BynqIO\CalculatieTool\Models\MoreEquipment;
use BynqIO\CalculatieTool\Models\CalculationLabor;
use BynqIO\CalculatieTool\Models\CalculationMaterial;
use BynqIO\CalculatieTool\Models\CalculationEquipment;
use BynqIO\CalculatieTool\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;

class CopyController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'id' => array('required','integer'),
        ]);

        if (csrf_token() != $request->get('csrf')) {
            return back();
        }

        $orig_project = Project::findOrFail($request->get('id'));
        if (!$orig_project->isOwner()) {
            return back();
        }

        $project = new Project;
        $project->user_id = Auth::id();
        $project->project_name = substr($orig_project->project_name . "-Kopie", 0, 50);
        $project->address_street = $orig_project->address_street;
        $project->address_number = $orig_project->address_number;
        $project->address_postal = $orig_project->address_postal;
        $project->address_city = $orig_project->address_city;
        $project->note = $orig_project->note;
        $project->hour_rate = $orig_project->hour_rate;
        $project->hour_rate_more = $orig_project->hour_rate_more;
        $project->profit_calc_contr_mat = $orig_project->profit_calc_contr_mat;
        $project->profit_calc_contr_equip = $orig_project->profit_calc_contr_equip;
        $project->profit_calc_subcontr_mat = $orig_project->profit_calc_subcontr_mat;
        $project->profit_calc_subcontr_equip = $orig_project->profit_calc_subcontr_equip;
        $project->profit_more_contr_mat = $orig_project->profit_more_contr_mat;
        $project->profit_more_contr_equip = $orig_project->profit_more_contr_equip;
        $project->profit_more_subcontr_mat = $orig_project->profit_more_subcontr_mat;
        $project->profit_more_subcontr_equip = $orig_project->profit_more_subcontr_equip;
        $project->province_id = $orig_project->province_id;
        $project->country_id = $orig_project->country_id;
        $project->type_id = $orig_project->type_id;
        $project->client_id = $orig_project->client_id;
        $project->tax_reverse = $orig_project->tax_reverse;
        $project->hide_null = $orig_project->hide_null;
        $project->use_equipment = $orig_project->use_equipment;
        $project->use_subcontract = $orig_project->use_subcontract;
        $project->use_subcontract = $orig_project->use_subcontract;
        $project->use_more = $orig_project->use_more;
        $project->use_less = $orig_project->use_less;
        $project->use_estimate = $orig_project->use_estimate;

        $project->save();

        $type = ProjectType::find($project->type_id);

        foreach (Chapter::where('project_id', $orig_project->id)->where('more', false)->get() as $orig_chapter) {
            $chapter = new Chapter;
            $chapter->chapter_name = $orig_chapter->chapter_name;
            $chapter->priority = $orig_chapter->priority;
            $chapter->project_id = $project->id;

            $chapter->save();

            foreach (Activity::where('chapter_id', $orig_chapter->id)->whereNull('detail_id')->get() as $orig_activity) {
                $activity = new Activity;
                $activity->chapter_id = $chapter->id;
                $activity->activity_name = $orig_activity->activity_name;
                $activity->priority = $orig_activity->priority;
                $activity->note = $orig_activity->note;
                $activity->use_timesheet = $orig_activity->use_timesheet;
                $activity->part_id = $orig_activity->part_id;
                $activity->part_type_id = $orig_activity->part_type_id;
                $activity->detail_id = $orig_activity->detail_id;
                $activity->tax_labor_id = $orig_activity->tax_labor_id;
                $activity->tax_material_id = $orig_activity->tax_material_id;
                $activity->tax_equipment_id = $orig_activity->tax_equipment_id;

                $activity->save();

                foreach(CalculationLabor::where('activity_id', $orig_activity->id)->get() as $orig_calc_labor) {
                    $calc_labor = new CalculationLabor;
                    $calc_labor->rate = $orig_calc_labor->rate;
                    $calc_labor->amount = $orig_calc_labor->amount;
                    $calc_labor->activity_id = $activity->id;

                    $calc_labor->save();
                }
                
                foreach(CalculationMaterial::where('activity_id', $orig_activity->id)->get() as $orig_calc_material) {
                    $calc_material = new CalculationMaterial;
                    $calc_material->material_name = $orig_calc_material->material_name;
                    $calc_material->unit = $orig_calc_material->unit;
                    $calc_material->rate = $orig_calc_material->rate;
                    $calc_material->amount = $orig_calc_material->amount;
                    $calc_material->activity_id = $activity->id;

                    $calc_material->save();
                }

                foreach(CalculationEquipment::where('activity_id', $orig_activity->id)->get() as $orig_calc_equipment) {
                    $calc_equipment = new CalculationEquipment;
                    $calc_equipment->equipment_name = $orig_calc_equipment->equipment_name;
                    $calc_equipment->unit = $orig_calc_equipment->unit;
                    $calc_equipment->rate = $orig_calc_equipment->rate;
                    $calc_equipment->amount = $orig_calc_equipment->amount;
                    $calc_equipment->activity_id = $activity->id;

                    $calc_equipment->save();
                }

                foreach(EstimateLabor::where('activity_id', $orig_activity->id)->get() as $orig_estim_labor) {
                    $estim_labor = new EstimateLabor;
                    $estim_labor->rate = $orig_estim_labor->rate;
                    $estim_labor->amount = $orig_estim_labor->amount;
                    $estim_labor->original = $orig_estim_labor->original;
                    $estim_labor->isset = $orig_estim_labor->isset;
                    $estim_labor->activity_id = $activity->id;

                    $estim_labor->save();
                }

                foreach(EstimateMaterial::where('activity_id', $orig_activity->id)->get() as $orig_estim_material) {
                    $estim_material = new EstimateMaterial;
                    $estim_material->material_name = $orig_estim_material->material_name;
                    $estim_material->unit = $orig_estim_material->unit;
                    $estim_material->rate = $orig_estim_material->rate;
                    $estim_material->amount = $orig_estim_material->amount;
                    $estim_material->original = $orig_estim_material->original;
                    $estim_material->isset = $orig_estim_material->isset;
                    $estim_material->activity_id = $activity->id;

                    $estim_material->save();
                }

                foreach(EstimateEquipment::where('activity_id', $orig_activity->id)->get() as $orig_estim_equipment) {
                    $estim_equipment = new EstimateEquipment;
                    $estim_equipment->equipment_name = $orig_estim_equipment->equipment_name;
                    $estim_equipment->unit = $orig_estim_equipment->unit;
                    $estim_equipment->rate = $orig_estim_equipment->rate;
                    $estim_equipment->amount = $orig_estim_equipment->amount;
                    $estim_equipment->original = $orig_estim_equipment->original;
                    $estim_equipment->isset = $orig_estim_equipment->isset;
                    $estim_equipment->activity_id = $activity->id;

                    $estim_equipment->save();
                }
            }
        }

        if ($type->type_name == 'regie') {
            foreach (Chapter::where('project_id', $orig_project->id)->where('more', true)->get() as $orig_chapter) {
                $chapter = new Chapter;
                $chapter->chapter_name = $orig_chapter->chapter_name;
                $chapter->priority = $orig_chapter->priority;
                $chapter->project_id = $project->id;

                $chapter->save();

                foreach (Activity::where('chapter_id', $orig_chapter->id)->whereNotNull('detail_id')->get() as $orig_activity) {
                    $activity = new Activity;
                    $activity->chapter_id = $chapter->id;
                    $activity->activity_name = $orig_activity->activity_name;
                    $activity->priority = $orig_activity->priority;
                    $activity->note = $orig_activity->note;
                    $activity->use_timesheet = $orig_activity->use_timesheet;
                    $activity->part_id = $orig_activity->part_id;
                    $activity->part_type_id = $orig_activity->part_type_id;
                    $activity->detail_id = $orig_activity->detail_id;
                    $activity->tax_labor_id = $orig_activity->tax_labor_id;
                    $activity->tax_material_id = $orig_activity->tax_material_id;
                    $activity->tax_equipment_id = $orig_activity->tax_equipment_id;

                    $activity->save();

                    foreach(MoreLabor::where('activity_id', $orig_activity->id)->get() as $orig_more_labor) {
                        $more_labor = new MoreLabor;
                        $more_labor->rate = $orig_more_labor->rate;
                        $more_labor->amount = $orig_more_labor->amount;
                        $more_labor->activity_id = $activity->id;

                        $more_labor->save();
                    }

                    foreach(MoreMaterial::where('activity_id', $orig_activity->id)->get() as $orig_more_material) {
                        $more_material = new MoreMaterial;
                        $more_material->material_name = $orig_more_material->material_name;
                        $more_material->unit = $orig_more_material->unit;
                        $more_material->rate = $orig_more_material->rate;
                        $more_material->amount = $orig_more_material->amount;
                        $more_material->activity_id = $activity->id;

                        $more_material->save();
                    }

                    foreach(MoreEquipment::where('activity_id', $orig_activity->id)->get() as $orig_more_equipment) {
                        $more_equipment = new MoreEquipment;
                        $more_equipment->equipment_name = $orig_more_equipment->equipment_name;
                        $more_equipment->unit = $orig_more_equipment->unit;
                        $more_equipment->rate = $orig_more_equipment->rate;
                        $more_equipment->amount = $orig_more_equipment->amount;
                        $more_equipment->activity_id = $activity->id;

                        $more_equipment->save();
                    }
                }
            }
        }

        if ($type->type_name == 'regie') {
            $relation = Relation::find($project->client_id);
            $relation_self = Relation::find(Auth::user()->self_id);
            $contact = Contact::where('relation_id',$relation->id)->first();
            $contact_self = Contact::where('relation_id',$relation_self->id)->first();

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

        Audit::CreateEvent('project.copy.success', 'Duplicated project: ' . $project->project_name);

        return redirect("project/{$project->id}-{$project->project_name}/details")->with('success', 'Project is gekopieerd en toegevoegd aan je projectenoverzicht op het dashboard. U bevindt zich nu in het gekopieerde project.');
    }

}
