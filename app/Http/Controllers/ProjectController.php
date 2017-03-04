<?php

namespace Calctool\Http\Controllers;

use Illuminate\Http\Request;

use \Calctool\Models\Project;
use \Calctool\Models\Offer;
use \Calctool\Models\Invoice;
use \Calctool\Models\ProjectType;
use \Calctool\Models\Resource;
use \Calctool\Models\Relation;
use \Calctool\Models\Contact;
use \Calctool\Models\Chapter;
use \Calctool\Models\Audit;
use \Calctool\Models\Activity;
use \Calctool\Models\FavoriteActivity;
use \Calctool\Models\ProjectShare;
use \Calctool\Http\Controllers\InvoiceController;
use \Calctool\Models\EstimateLabor;
use \Calctool\Models\EstimateMaterial;
use \Calctool\Models\EstimateEquipment;
use \Calctool\Models\MoreLabor;
use \Calctool\Models\MoreMaterial;
use \Calctool\Models\MoreEquipment;
use \Calctool\Models\CalculationLabor;
use \Calctool\Models\CalculationMaterial;
use \Calctool\Models\CalculationEquipment;

use \Auth;
use \Mailgun;

class ProjectController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */
	public function getNew(Request $request)
	{
		return view('user.new_project', ['debtor_code' => mt_rand(1000000, 9999999)]);
	}

	public function getEdit(Request $request)
	{
		return view('user.edit_project');
	}

	public function getFavoriteManagement()
	{
		return view('user.favorite');
	}

	public function getOfferInvoiceList(Request $request)
	{
		return view('user.offer_invoice');
	}

	public function downloadResource(Request $request, $resourceid)
	{
		$res = Resource::find($resourceid);
		if (!$res || !$res->isOwner()) {
			return back();
		}

		return response()->download($res->file_location);
	}

	public function doNew(Request $request)
	{
		$validator = $this->validate($request, [
			'street' => array('required','max:60'),
			'address_number' => array('required','alpha_num','max:5'),
			'zipcode' => array('required','size:6'),
			'city' => array('required','max:35'),
			'province' => array('required','numeric'),
			'country' => array('required','numeric'),
			'contractor' => array('required','numeric'),
			'name' => array('max:50'),
		]);

		if (!Relation::find(Auth::user()->self_id)) {
			return back()->withErrors(['error' => 'Mijn bedrijf bestaat niet'])->withInput($request->all());
		}

		$project = new \Calctool\Models\Project;
		$project->address_street = $request->input('street');
		$project->address_number = $request->input('address_number');
		$project->address_postal = $request->input('zipcode');
		$project->address_city = $request->input('city');
		$project->note = $request->input('note');
		$project->hour_rate = Auth::user()->pref_hourrate_calc;
		$project->hour_rate_more = Auth::user()->pref_hourrate_more;
		$project->profit_calc_contr_mat = Auth::user()->pref_profit_calc_contr_mat;
		$project->profit_calc_contr_equip = Auth::user()->pref_profit_calc_contr_equip;
		$project->profit_calc_subcontr_mat = Auth::user()->pref_profit_calc_subcontr_mat;
		$project->profit_calc_subcontr_equip = Auth::user()->pref_profit_calc_subcontr_equip;
		$project->profit_more_contr_mat = Auth::user()->pref_profit_more_contr_mat;
		$project->profit_more_contr_equip = Auth::user()->pref_profit_more_contr_equip;
		$project->profit_more_subcontr_mat = Auth::user()->pref_profit_more_subcontr_mat;
		$project->profit_more_subcontr_equip = Auth::user()->pref_profit_more_subcontr_equip;
		$project->user_id = Auth::id();
		$project->province_id = $request->input('province');
		$project->country_id = $request->input('country');
		$project->type_id = $request->input('type');
		$project->client_id = $request->input('contractor');

		$relation = Relation::find($project->client_id);
		if ($request->input('tax_reverse')) {
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

		$project->save();

		$type = ProjectType::find($project->type_id);
		if ($type->type_name == 'regie') {
			$relation_self = Relation::find(Auth::user()->self_id);
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
			$invoice->save();
		}

		Audit::CreateEvent('project.new.success', 'Created project: ' . $project->project_name);

		return redirect('project-'.$project->id.'/edit')->with('success', 'Opgeslagen');
	}

	public function getProjectCopy(Request $request, $project_id)
	{
		$orig_project = Project::find($project_id);

		if (!$orig_project->isOwner()) {
			return back()->withErrors(['error' => 'Project bestaat niet']);
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
					$calc_equipment = new EstimateEquipment;
					$calc_equipment->equipment_name = $orig_estim_equipment->equipment_name;
					$calc_equipment->unit = $orig_estim_equipment->unit;
					$calc_equipment->rate = $orig_estim_equipment->rate;
					$calc_equipment->amount = $orig_estim_equipment->amount;
					$calc_equipment->original = $orig_estim_equipment->original;
					$calc_equipment->isset = $orig_estim_equipment->isset;
					$calc_equipment->activity_id = $activity->id;

					$calc_equipment->save();
				}
			}
		}

		Audit::CreateEvent('project.copy.success', 'Duplicated project: ' . $project->project_name);

		return redirect('project-'.$project->id.'/edit')->with('success', 'Project is gekopieerd en toegevoegd aan je projectenoverzicht op het dashboard. U bevindt zich nu in het gekopieerde project.');
	}

	public function getAll(Request $request)
	{
		return view('user.project');
	}

	public function doDeleteResource(Request $request, $resourceid)
	{
		$res = Resource::find($resourceid);
		if (!$res || !$res->isOwner()) {
			return back();
		}

		$res->delete();

		Audit::CreateEvent('resource.delete.success', 'Resource ' . $res->id . ' deleted');

		return back();
	}

	public function doUpdate(Request $request)
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

	public function doUpdateNote(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer'),
		]);

		$project = Project::find($request->input('id'));
		if (!$project || !$project->isOwner()) {
			return back()->withInput($request->all());
		}
		$project->note = $request->input('note');

		$project->save();

		Audit::CreateEvent('project.update.note.success', 'Note by project ' . $project->project_name . ' updated');

		return back()->with('success', 'Projectomschrijving aangepast');
	}

	public function doUpdateProfit(Request $request)
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

	public function doUpdateAdvanced(Request $request)
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
								
		$estim_total = 0;
		$more_total = 0;
		$less_total = 0;
		$disable_estim = false;
		$disable_more = false;
		$disable_less = false;
		
		foreach (Chapter::where('project_id','=', $project->id)->get() as $chap) {
			foreach (Activity::where('chapter_id','=', $chap->id)->get() as $activity) {
				$estim_total += EstimateLabor::where('activity_id','=', $activity->id)->count('id');
				$estim_total += EstimateMaterial::where('activity_id','=', $activity->id)->count('id');
				$estim_total += EstimateEquipment::where('activity_id','=', $activity->id)->count('id');

				$more_total += MoreLabor::where('activity_id','=', $activity->id)->count('id');
				$more_total += MoreMaterial::where('activity_id','=', $activity->id)->count('id');
				$more_total += MoreEquipment::where('activity_id','=', $activity->id)->count('id');	

				$less_total += CalculationLabor::where('activity_id','=', $activity->id)->where('isless',true)->count('id');
				$less_total += CalculationMaterial::where('activity_id','=', $activity->id)->where('isless',true)->count('id');
				$less_total += CalculationEquipment::where('activity_id','=', $activity->id)->where('isless',true)->count('id');	
			}
		}

		//
		if ($offer_last) {
			$disable_estim = true;
		}
		if ($estim_total>0) {
			$disable_estim = true;
		}

		//
		if ($invoice_end && $invoice_end->invoice_close) {
			$disable_more = true;
		}
		if ($more_total>0) {
			$disable_more = true;
		}

		//
		if ($invoice_end && $invoice_end->invoice_close) {
			$disable_less = true;
		}
		if ($less_total>0) {
			$disable_less = true;
		}

		if (!$disable_estim) {
			if ($request->input('use_estimate'))
				$project->use_estimate = true;
			else
				$project->use_estimate = false;
		}

		if (!$disable_more) {
			if ($request->input('use_more'))
				$project->use_more = true;
			else
				$project->use_more = false;
		}

		if (!$disable_less) {
			if ($request->input('use_less'))
				$project->use_less = true;
			else
				$project->use_less = false;
		}

		if ($request->input('use_subcontract'))
			$project->use_subcontract = true;

		if ($request->input('use_equipment'))
			$project->use_equipment = true;

		if ($request->input('hide_null'))
			$project->hide_null = true;
		else
			$project->hide_null = false;

		if ($request->input('mail_reminder'))
			$project->pref_email_reminder = true;
		else
			$project->pref_email_reminder = false;

		$project->save();

		Audit::CreateEvent('project.update.advanced.success', 'Advanced options by project ' . $project->project_name . ' updated');

		return back()->with('success', 'Geavanceerde opties opgeslagen');
	}

	public function doUpdateWorkExecution(Request $request)
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

	public function doUpdateWorkCompletion(Request $request)
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

	public function doUpdateProjectClose(Request $request)
	{
		$this->validate($request, [
			'project' => array('required','integer'),
			'date' => array('required'),
		]);

		$project = Project::find($request->input('project'));
		if (!$project || !$project->isOwner()) {
			return back()->withInput($request->all());
		}
		$project->project_close = date('Y-m-d', strtotime($request->input('date')));

		$project->save();

		Audit::CreateEvent('project.close.success', 'Project ' . $project->project_name . ' closed');

		return response()->json(['success' => 1]);
	}

	public function getUpdateProjectDilapidated(Request $request, $project_id)
	{
		$project = Project::find($project_id);
		if (!$project || !$project->isOwner()) {
			return back()->withInput($request->all());
		}
		if (!$project->project_close) {
			return back()->withInput($request->all());
		}
		$project->is_dilapidated = true;

		$project->save();

		Audit::CreateEvent('project.dilapidated.success', 'Project ' . $project->project_name . ' dilapidated');

		return redirect('/');
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
		Mailgun::send('mail.user_reacted', $data, function($message) use ($data) {
			$message->to($data['email'], strtolower(trim($data['client'])));
			$message->subject('CalculatieTool.com - Uw vakman heeft gereageerd');
			$message->from('info@calculatietool.com', 'CalculatieTool.com');
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

	public function doUploadProjectDocument(Request $request)
	{
		$this->validate($request, [
			'projectfile' => array('required', 'mimes:jpeg,jpg,bmp,png,gif,pdf,doc,docx,xls,xlsx,csv,txt,ppt,pptx,xml,zip,7z,tar,gz,rar,wav,mp3,flac,mkv,mp4,avi,css,html', 'file'),
			'project' => array('required'),
		]);

		$project = Project::find($request->input('project'));
		if (!$project || !$project->isOwner()) {
			return back()->withInput($request->all());
		}

		if ($request->hasFile('projectfile')) {
			$file = $request->file('projectfile');

			$newname = Auth::id().'-'.md5(mt_rand()).'.'.$file->getClientOriginalExtension();
			$file->move('user-content', $newname);

			$resource = new Resource;
			$resource->resource_name = $file->getClientOriginalName();
			$resource->file_location = 'user-content/' . $newname;
			$resource->file_size = $file->getClientSize();
			$resource->user_id = Auth::id();
			$resource->project_id = $project->id;
			$resource->description = 'Project document';

			$resource->save();

			Audit::CreateEvent('project.new.document.upload.success', 'Document ' . $resource->resource_name . ' attached to project ' . $project->project_name);

			return back()->with('success', 'Document aan project toegevoegd');
		} else {
			// redirect our user back to the form with the errors from the validator
			return back()->withErrors('Geen bestand geupload');
		}
	}

	public function getPackingSlip(Request $request, $project_id)
	{
		$pdf = \PDF::loadView('calc.packslip_pdf', [
			'project_id' => $project_id,
			'relation_self' => $relation_self = Relation::find(Auth::user()->self_id),
			'list_id' => $project_id . date('Y') . mt_rand(10,99),
		]);

		return $pdf->inline();
	}

	public function getPackList(Request $request, $project_id)
	{
		$pdf = \PDF::loadView('user.packlist_pdf', [
			'project_id' => $project_id,
			'user_id' => Auth::id(),
			'relation_self' => $relation_self = Relation::find(Auth::user()->self_id),
			'list_id' => $project_id . date('Y') . mt_rand(10,99),
		]);

		return $pdf->inline();
	}
}
