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
use \Calctool\Models\ProjectShare;
use \Calctool\Http\Controllers\InvoiceController;

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
			'street' => array('required','alpha','max:60'),
			'address_number' => array('required','alpha_num','max:5'),
			'zipcode' => array('required','size:6'),
			'city' => array('required','alpha_num','max:35'),
			'province' => array('required','numeric'),
			'country' => array('required','numeric'),
			'contractor' => array('required','numeric'),
			'type' => array('required'),
			'hour_rate' => array('required','regex:/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9])?$/'),
			'more_hour_rate' => array('required','regex:/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9])?$/'),
			'profit_material_1' => array('required','numeric','between:0,200'),
			'profit_equipment_1' => array('required','numeric','between:0,200'),
			'profit_material_2' => array('required','numeric','between:0,200'),
			'profit_equipment_2' => array('required','numeric','between:0,200'),
			'more_profit_material_1' => array('required','numeric','between:0,200'),
			'more_profit_equipment_1' => array('required','numeric','between:0,200'),
			'more_profit_material_2' => array('required','numeric','between:0,200'),
			'more_profit_equipment_2' => array('required','numeric','between:0,200')
		]);

		$hour_rate = str_replace(',', '.', str_replace('.', '', $request->input('hour_rate')));
		if ($hour_rate<0 || $hour_rate>999) {
			return back()->withErrors($validator)->withInput($request->all());
		}

		$hour_rate_more = str_replace(',', '.', str_replace('.', '', $request->input('more_hour_rate')));
		if ($hour_rate_more<0 || $hour_rate_more>999) {
			return back()->withErrors($validator)->withInput($request->all());
		}

		$project = new \Calctool\Models\Project;
		$project->project_name = $request->input('name');
		$project->address_street = $request->input('street');
		$project->address_number = $request->input('address_number');
		$project->address_postal = $request->input('zipcode');
		$project->address_city = $request->input('city');
		$project->note = $request->input('note');
		$project->hour_rate = $hour_rate;
		$project->hour_rate_more = $hour_rate_more;
		$project->profit_calc_contr_mat = $request->input('profit_material_1');
		$project->profit_calc_contr_equip = $request->input('profit_equipment_1');
		$project->profit_calc_subcontr_mat = $request->input('profit_material_2');
		$project->profit_calc_subcontr_equip = $request->input('profit_equipment_2');
		$project->profit_more_contr_mat = $request->input('more_profit_material_1');
		$project->profit_more_contr_equip = $request->input('more_profit_equipment_1');
		$project->profit_more_subcontr_mat = $request->input('more_profit_material_2');
		$project->profit_more_subcontr_equip = $request->input('more_profit_equipment_2');
		$project->user_id = Auth::id();
		$project->province_id = $request->input('province');
		$project->country_id = $request->input('country');
		$project->type_id = $request->input('type');
		$project->client_id = $request->input('contractor');

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

		$log = new \Calctool\Models\Audit;
		$log->ip = \Calctool::remoteAddr();
		$log->event = '[NEWPROJECT] [SUCCESS] ' . $request->input('name');
		$log->user_id = Auth::id();
		$log->save();

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
			'street' => array('required','alpha','max:60'),
			'address_number' => array('required','alpha_num','max:5'),
			'zipcode' => array('required','size:6'),
			'city' => array('required','alpha_num','max:35'),
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

		$hour_rate = str_replace(',', '.', str_replace('.', '', $request->input('hour_rate')));
		if ($hour_rate<0 || $hour_rate>999) {
			return back()->withErrors($validator)->withInput($request->all());
		}

		$hour_rate_more = str_replace(',', '.', str_replace('.', '', $request->input('more_hour_rate')));
		if ($hour_rate_more<0 || $hour_rate_more>999) {
			return back()->withErrors($validator)->withInput($request->all());
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

		return back()->with('success', 'Winstpercentages aangepast');
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

		return json_encode(['success' => 1]);
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

		return json_encode(['success' => 1]);
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

		return json_encode(['success' => 1]);
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
}
