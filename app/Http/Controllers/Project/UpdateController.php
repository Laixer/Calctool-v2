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
use BynqIO\Dynq\Models\Resource;
use BynqIO\Dynq\Models\Relation;
use BynqIO\Dynq\Models\Contact;
use BynqIO\Dynq\Models\Chapter;
use BynqIO\Dynq\Models\Audit;
use BynqIO\Dynq\Models\Activity;
use BynqIO\Dynq\Models\RelationKind;
use BynqIO\Dynq\Models\FavoriteActivity;
use BynqIO\Dynq\Models\ProjectShare;
use BynqIO\Dynq\Http\Controllers\InvoiceController;
use BynqIO\Dynq\Models\EstimateLabor;
use BynqIO\Dynq\Models\EstimateMaterial;
use BynqIO\Dynq\Models\EstimateEquipment;
use BynqIO\Dynq\Models\MoreLabor;
use BynqIO\Dynq\Models\MoreMaterial;
use BynqIO\Dynq\Models\MoreEquipment;
use BynqIO\Dynq\Models\CalculationLabor;
use BynqIO\Dynq\Models\CalculationMaterial;
use BynqIO\Dynq\Models\CalculationEquipment;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Mail;
use Storage;

class UpdateController extends Controller
{
    public function updateDetails(Request $request)
    {
        $this->validate($request, [
            'id' => array('required','integer'),
            'name' => array('required','max:50'),
            'street' => array('required','max:60'),
            'address_number' => array('required','alpha_num','max:5'),
            'zipcode' => array('required','size:6'),
            'city' => array('required','max:35'),
            'province' => array('required','numeric'),
            'country' => array('required','numeric'),
        ]);

        $project = Project::find($request->input('id'));
        if (!$project || !$project->isOwner()) {
            return back()->withInput($request->all());
        }

        $project->project_name = $request->input('name');
        $project->address_street = $request->input('street');
        $project->address_number = $request->input('address_number');
        $project->address_postal = $request->input('zipcode');
        $project->address_city = $request->input('city');
        $project->note = $request->input('note');
        $project->province_id = $request->input('province');
        $project->country_id = $request->input('country');
        $project->client_id = $request->input('contractor');

        if ($request->input('toggle-mail-reminder')) {
            $project->pref_email_reminder = true;
        } else {
            $project->pref_email_reminder = false;
        }

        $project->save();

        Audit::CreateEvent('project.update.success', 'Settings by project ' . $project->project_name . ' updated');

        return back()->with('success', 'Projectgegevens aangepast');
    }

    // public function updateNote(Request $request)
    // {
    //     $this->validate($request, [
    //         'id' => array('required','integer'),
    //     ]);

    //     $project = Project::find($request->input('id'));
    //     if (!$project || !$project->isOwner()) {
    //         return back()->withInput($request->all());
    //     }
    //     $project->note = $request->input('note');

    //     $project->save();

    //     Audit::CreateEvent('project.update.note.success', 'Note by project ' . $project->project_name . ' updated');

    //     return back()->with('success', 'Projectomschrijving aangepast');
    // }

    public function updateProfit(Request $request)
    {
        $this->validate($request, [
            'id' => array('required','integer'),
            'hour_rate' => array('regex:/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9]?)?$/'),
            'more_hour_rate' => array('required','regex:/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9]?)?$/'),
            'profit_material_1' => array('numeric','between:0,200'),
            'profit_equipment_1' => array('numeric','between:0,200'),
            'profit_material_2' => array('numeric','between:0,200'),
            'profit_equipment_2' => array('numeric','between:0,200'),
            'more_profit_material_1' => array('required','numeric','between:0,200'),
            'more_profit_equipment_1' => array('required','numeric','between:0,200'),
            'more_profit_material_2' => array('required','numeric','between:0,200'),
            'more_profit_equipment_2' => array('required','numeric','between:0,200')
        ]);

        $project = Project::find($request->input('id'));
        if (!$project || !$project->isOwner()) {
            return back()->withInput($request->all());
        }

        $hour_rate = floatval(str_replace(',', '.', str_replace('.', '', $request->input('hour_rate'))));
        if ($hour_rate<0 || $hour_rate>999) {
            return back()->withInput($request->all())->withErrors(['error' => "Ongeldige invoer, vervang punten door comma's"]);
        }

        $hour_rate_more = floatval(str_replace(',', '.', str_replace('.', '', $request->input('more_hour_rate'))));
        if ($hour_rate_more<0 || $hour_rate_more>999) {
            return back()->withInput($request->all())->withErrors(['error' => "Ongeldige invoer, vervang punten door comma's"]);
        }

        if ($hour_rate)
            $project->hour_rate = $hour_rate;
            $project->hour_rate_more = $hour_rate_more;
        if ($request->input('profit_material_1') != "")
            $project->profit_calc_contr_mat = round($request->input('profit_material_1'));
        if ($request->input('profit_equipment_1') != "")
            $project->profit_calc_contr_equip = round($request->input('profit_equipment_1'));
        if ($request->input('profit_material_2') != "")
            $project->profit_calc_subcontr_mat = round($request->input('profit_material_2'));
        if ($request->input('profit_equipment_2') != "")
            $project->profit_calc_subcontr_equip = round($request->input('profit_equipment_2'));
        $project->profit_more_contr_mat = round($request->input('more_profit_material_1'));
        $project->profit_more_contr_equip = round($request->input('more_profit_equipment_1'));
        $project->profit_more_subcontr_mat = round($request->input('more_profit_material_2'));
        $project->profit_more_subcontr_equip = round($request->input('more_profit_equipment_2'));

        $project->save();

        Audit::CreateEvent('project.update.profit.success', 'Profits by project ' . $project->project_name . ' updated');

        return back()->with('success', 'Uurtarief & winstpercentages aangepast');
    }

    public function updateOptions(Request $request)
    {
        $project = Project::find($request->input('id'));
        if (!$project || !$project->isOwner()) {
            return back()->withInput($request->all());
        }

        $invoice_end = null;
        $offer_last = Offer::where('project_id',$project->id)->first();
        if ($offer_last){
            $invoice_end = Invoice::where('offer_id','=', $offer_last->id)->where('isclose',true)->first();
        }
                                
        // $estim_total = 0;
        $more_total = 0;
        // $less_total = 0;
        // $disable_estim = false;
        $disable_more = false;
        // $disable_less = false;
        
        foreach (Chapter::where('project_id','=', $project->id)->get() as $chap) {
            foreach (Activity::where('chapter_id','=', $chap->id)->get() as $activity) {
                // $estim_total += EstimateLabor::where('activity_id','=', $activity->id)->count('id');
                // $estim_total += EstimateMaterial::where('activity_id','=', $activity->id)->count('id');
                // $estim_total += EstimateEquipment::where('activity_id','=', $activity->id)->count('id');

                $more_total += MoreLabor::where('activity_id','=', $activity->id)->count('id');
                $more_total += MoreMaterial::where('activity_id','=', $activity->id)->count('id');
                $more_total += MoreEquipment::where('activity_id','=', $activity->id)->count('id');	

                // $less_total += CalculationLabor::where('activity_id','=', $activity->id)->where('isless',true)->count('id');
                // $less_total += CalculationMaterial::where('activity_id','=', $activity->id)->where('isless',true)->count('id');
                // $less_total += CalculationEquipment::where('activity_id','=', $activity->id)->where('isless',true)->count('id');	
            }
        }

        //
        // if ($offer_last) {
        //     $disable_estim = true;
        // }
        // if ($estim_total>0) {
        //     $disable_estim = true;
        // }

        //
        if ($invoice_end && $invoice_end->invoice_close) {
            $disable_more = true;
        }
        if ($more_total>0) {
            $disable_more = true;
        }

        //
        // if ($invoice_end && $invoice_end->invoice_close) {
        //     $disable_less = true;
        // }
        // if ($less_total>0) {
        //     $disable_less = true;
        // }

        // if (!$disable_estim) {
        //     if ($request->input('use_estimate'))
        //         $project->use_estimate = true;
        //     else
        //         $project->use_estimate = false;
        // }

        if (!$disable_more) {
            if ($request->input('use_more')) {
                $project->use_more = true;
                $project->use_less = true;
            } else {
                $project->use_more = false;
                $project->use_less = false;
            }
        }

        // if ($request->input('use_subcontract'))
        //     $project->use_subcontract = true;

        if ($request->input('use_equipment')) {
            $project->use_equipment = true;
        }

        // if ($request->input('hide_null'))
        //     $project->hide_null = true;
        // else
        //     $project->hide_null = false;

        // if ($request->input('mail_reminder'))
        //     $project->pref_email_reminder = true;
        // else
        //     $project->pref_email_reminder = false;

        $project->save();

        Audit::CreateEvent('project.update.advanced.success', 'Advanced options by project ' . $project->project_name . ' updated');

        return back()->with('success', 'Geavanceerde opties opgeslagen');
    }

    public function updateWorkExecution(Request $request)
    {
        $this->validate($request, [
            'project' => array('required','integer'),
            'date' => array('required'),
        ]);

        $project = Project::find($request->input('project'));
        if (!$project || !$project->isOwner()) {
            return back()->withInput($request->all());
        }
        $project->work_execution = date('Y-m-d', strtotime($request->input('date')));

        $project->save();

        Audit::CreateEvent('project.work.started.success', 'Work date started by project ' . $project->project_name);

        return response()->json(['success' => 1]);
    }

    public function updateWorkCompletion(Request $request)
    {
        $this->validate($request, [
            'project' => array('required','integer'),
            'date' => array('required'),
        ]);

        $project = Project::find($request->input('project'));
        if (!$project || !$project->isOwner()) {
            return back()->withInput($request->all());
        }
        $project->work_completion = date('Y-m-d', strtotime($request->input('date')));

        $project->save();

        Audit::CreateEvent('project.work.complete.success', 'Work date complete by project ' . $project->project_name);

        return response()->json(['success' => 1]);
    }

    public function updateProjectClose(Request $request)
    {
        $this->validate($request, [
            'id' => array('required','integer'),
        ]);

        if (csrf_token() != $request->get('csrf')) {
            return back();
        }

        $project = Project::findOrFail($request->get('id'));
        if (!$project->isOwner()) {
            return back();
        }

        $project->project_close = date("Y-m-d H:i:s");
        $project->save();

        Audit::CreateEvent('project.close.success', 'Project ' . $project->project_name . ' closed');

        return back()->with('success', 'Project gesloten');
    }

    public function cancel(Request $request)
    {
        $this->validate($request, [
            'id' => array('required','integer'),
        ]);

        if (csrf_token() != $request->get('csrf')) {
            return back();
        }

        $project = Project::findOrFail($request->get('id'));
        if (!$project->isOwner()) {
            return back();
        }

        /* Project must be closed before canceling is possible */
        if (!$project->project_close) {
            return back();
        }

        $project->is_dilapidated = true;
        $project->save();

        Audit::CreateEvent('project.dilapidated.success', 'Project ' . $project->project_name . ' dilapidated');

        return redirect()->route('dashboard');
    }

    public function doCommunication(Request $request)
    {
        $this->validate($request, [
            'project' => array('required','integer'),
        ]);

        $project = Project::find($request->input('project'));
        if (!$project || !$project->isOwner()) {
            return back()->withInput($request->all());
        }

        $share = ProjectShare::where('project_id', $project->id)->first();
        if (!$share) {
            return back()->withInput($request->all());
        }

        $share->user_note = $request->input('user_note');
        $share->save();

        $offer = Offer::where('project_id', $project->id)->orderBy('created_at','desc')->first();
        
        $contact_client = Contact::find($offer->to_contact_id);
        $contact_user = Contact::find($offer->from_contact_id);

        $user_logo = '';
        $relation_self = Relation::find(Auth::user()->self_id);
        if ($relation_self->logo_id)
            $user_logo = Resource::find($relation_self->logo_id)->file_location;

        $data = array(
            'email' => $contact_client->email,
            'email_from' => $contact_user->email,
            'client' => $contact_client->getFormalName(),
            'mycomp' => $relation_self->company_name,
            'token' => $share->token,
            'user' => $contact_user->getFormalName(),
            'project_name' => $project->project_name,
            'user_logo' => $user_logo,
            'note' => nl2br($request->input('user_note'))
        );
        Mail::send('mail.user_reacted', $data, function($message) use ($data) {
            $message->to($data['email'], strtolower(trim($data['client'])));
            $message->subject(config('app.name') . ' - Uw vakman heeft gereageerd');
            $message->from(APP_EMAIL);
            $message->replyTo($data['email_from'], $data['mycomp']);
        });

        Audit::CreateEvent('project.communication.success', 'New project communication by project ' . $project->project_name);

        return back()->with('success', 'Opmerking toegevoegd aan project');
    }

    public function getRelationDetails(Request $request, $relation_id)
    {
        $relation = Relation::find($relation_id);
        if (!$relation || !$relation->isOwner()) {
            return response()->json(['success' => 0]);
        }

        $arr = [
            'success' => 1,
            'address_street' => $relation->address_street,
            'address_number' => $relation->address_number,
            'address_postal' => $relation->address_postal,
            'address_city' => $relation->address_city,
            'province_id' => $relation->province_id,
            'country_id' => $relation->country_id,
        ];

        return response()->json($arr);
    }

}
