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

class ProjectController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */
	public function getNew(Request $request)
	{
		return view('user.new_project');
	}

	public function getEdit(Request $request)
	{
		return view('user.edit_project');
	}

	public function downloadResource(Request $request, $resourceid)
	{
		$res = Resource::find($resourceid);
		if ($res) {
			return response()->download($res->file_location);
		}
		return;
	}

	public function doNew(Request $request)
	{
		$validator = $this->validate($request, [
			'name' => array('required','max:50'),
			'street' => array('required','max:60'),
			'address_number' => array('required','alpha_num','max:5'),
			'zipcode' => array('required','size:6'),
			'city' => array('required','max:35'),
			'province' => array('required','numeric'),
			'country' => array('required','numeric'),
			'contractor' => array('required','numeric'),
		]);

		if (!Relation::find(Auth::user()->self_id)) {
			return back()->withErrors(['error' => 'Mijn bedrijf bestaat niet'])->withInput($request->all());
		}

		$project = new \Calctool\Models\Project;
		$project->project_name = $request->input('name');
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
			$relation = Relation::find($project->client_id);
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

	public function getAll(Request $request)
	{
		return view('user.project');
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

		return back()->with('success', 'Projectomschrijving aangepast');
	}

	public function doUpdateProfit(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer'),
			'hour_rate' => array('regex:/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9])?$/'),
			'more_hour_rate' => array('required','regex:/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9])?$/'),
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
			return back()->withInput($request->all());
		}


		$hour_rate_more = floatval(str_replace(',', '.', str_replace('.', '', $request->input('more_hour_rate'))));
		if ($hour_rate_more<0 || $hour_rate_more>999) {
			return back()->withInput($request->all());
		}

		if ($hour_rate)
			$project->hour_rate = $hour_rate;
			$project->hour_rate_more = $hour_rate_more;
		if ($request->input('profit_material_1') != "")
			$project->profit_calc_contr_mat = $request->input('profit_material_1');
		if ($request->input('profit_equipment_1') != "")
			$project->profit_calc_contr_equip = $request->input('profit_equipment_1');
		if ($request->input('profit_material_2') != "")
			$project->profit_calc_subcontr_mat = $request->input('profit_material_2');
		if ($request->input('profit_equipment_2') != "")
			$project->profit_calc_subcontr_equip = $request->input('profit_equipment_2');
		$project->profit_more_contr_mat = $request->input('more_profit_material_1');
		$project->profit_more_contr_equip = $request->input('more_profit_equipment_1');
		$project->profit_more_subcontr_mat = $request->input('more_profit_material_2');
		$project->profit_more_subcontr_equip = $request->input('more_profit_equipment_2');

		$project->save();

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
		
		foreach(Chapter::where('project_id','=', $project->id)->get() as $chap) {
			foreach(Activity::where('chapter_id','=', $chap->id)->get() as $activity) {
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

		if ($request->input('hide_null'))
			$project->hide_null = true;
		else
			$project->hide_null = false;

		if ($request->input('mail_reminder'))
			$project->pref_email_reminder = true;
		else
			$project->pref_email_reminder = false;

		$project->save();

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

		return response()->json(['success' => 1]);
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

		$project_share = ProjectShare::where('project_id', $project->id)->first();
		if (! $project_share) {
			return back()->withInput($request->all());
		}

		$project_share->user_note = $request->input('user_note');
		
		$project_share->save();

		return back();
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
