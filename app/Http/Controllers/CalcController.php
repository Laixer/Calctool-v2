<?php

namespace Calctool\Http\Controllers;

use \Illuminate\Http\Request;
use \Calctool\Models\Project;
use \Calctool\Models\Chapter;
use \Calctool\Models\Part;
use \Calctool\Models\PartType;
use \Calctool\Models\ProjectType;
use \Calctool\Models\Tax;
use \Calctool\Models\Activity;
use \Calctool\Models\FavoriteActivity;
use \Calctool\Calculus\InvoiceTerm;
use \Calctool\Calculus\ResultEndresult;
use \Calctool\Calculus\CalculationRegister;
use \Calctool\Models\CalculationMaterial;
use \Calctool\Models\CalculationEquipment;
use \Calctool\Models\CalculationLabor;
use \Calctool\Models\FavoriteLabor;
use \Calctool\Models\FavoriteMaterial;
use \Calctool\Models\FavoriteEquipment;
use \Calctool\Models\EstimateLabor;
use \Calctool\Models\EstimateMaterial;
use \Calctool\Models\EstimateEquipment;
use \Calctool\Models\Invoice;
use \Calctool\Models\Offer;

use \Auth;
use \PDF;

class CalcController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controlluse \Calctool\Models\Invoice;er
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function getCalculation(Request $request, $projectid)
	{
		$project = Project::find($projectid);
		if ($project) {
			if ($project->project_close)
				return response()->view('calc.calculation_closed');
			$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
			if ($offer_last && $offer_last->offer_finish)
				return response()->view('calc.calculation_closed');
		}
		return view('calc.calculation');
	}

	public function getCalculationWithFavorite(Request $request, $projectid, $chapterid, $favid)
	{
		$chapter = Chapter::find($chapterid);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return back();
		}

		$favact = FavoriteActivity::find($favid);
		if (!$favact || !$favact->isOwner()) {
			return back();
		}

		$part = Part::where('part_name','=','contracting')->first();
		$part_type = PartType::where('type_name','=','calculation')->first();
		$project = Project::find($chapter->project_id);

		$activity = new Activity;
		$activity->activity_name = $favact->activity_name;
		$activity->priority = 0;
		$activity->chapter_id = $chapter->id;
		$activity->part_id = $part->id;
		$activity->part_type_id = $part_type->id;

		if ($project->tax_reverse) {
			$tax_id = Tax::where('tax_rate','0')->first()['id'];
			$activity->tax_labor_id = $tax_id;
			$activity->tax_material_id = $tax_id;
			$activity->tax_equipment_id = $tax_id;
		} else {
			$activity->tax_labor_id = $favact->tax_labor_id;
			$activity->tax_material_id = $favact->tax_material_id;
			$activity->tax_equipment_id = $favact->tax_equipment_id;
		}

		$activity->save();

		foreach (FavoriteLabor::where('activity_id', $favact->id)->get() as $fav_calc_labor) {
			CalculationLabor::create(array(
				"rate" => $fav_calc_labor->rate,
				"amount" => $fav_calc_labor->amount,
				"activity_id" => $activity->id,
			));
		}

		foreach (FavoriteMaterial::where('activity_id', $favact->id)->get() as $fav_calc_material) {
			CalculationMaterial::create(array(
				"material_name" => $fav_calc_material->material_name,
				"unit" => $fav_calc_material->unit,
				"rate" => $fav_calc_material->rate,
				"amount" => $fav_calc_material->amount,
				"activity_id" => $activity->id,
			));
		}

		foreach (FavoriteEquipment::where('activity_id', $favact->id)->get() as $fav_calc_equipment) {
			CalculationEquipment::create(array(
				"equipment_name" => $fav_calc_equipment->equipment_name,
				"unit" => $fav_calc_equipment->unit,
				"rate" => $fav_calc_equipment->rate,
				"amount" => $fav_calc_equipment->amount,
				"activity_id" => $activity->id,
			));
		}

		return back();
	}

	public function getEstimateWithFavorite(Request $request, $projectid, $chapterid, $favid)
	{
		$chapter = Chapter::find($chapterid);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return back();
		}

		$favact = FavoriteActivity::find($favid);
		if (!$favact || !$favact->isOwner()) {
			return back();
		}

		$part = Part::where('part_name','=','contracting')->first();
		$part_type = PartType::where('type_name','=','estimate')->first();
		$project = Project::find($chapter->project_id);

		$activity = new Activity;
		$activity->activity_name = $favact->activity_name;
		$activity->priority = 0;
		$activity->chapter_id = $chapter->id;
		$activity->part_id = $part->id;
		$activity->part_type_id = $part_type->id;

		if ($project->tax_reverse) {
			$tax_id = Tax::where('tax_rate','0')->first()['id'];
			$activity->tax_labor_id = $tax_id;
			$activity->tax_material_id = $tax_id;
			$activity->tax_equipment_id = $tax_id;
		} else {
			$activity->tax_labor_id = $favact->tax_labor_id;
			$activity->tax_material_id = $favact->tax_material_id;
			$activity->tax_equipment_id = $favact->tax_equipment_id;
		}

		$activity->save();

		foreach (FavoriteLabor::where('activity_id', $favact->id)->get() as $fav_calc_labor) {
			EstimateLabor::create(array(
				"rate" => $fav_calc_labor->rate,
				"amount" => $fav_calc_labor->amount,
				"activity_id" => $activity->id,
				"original" => true,
				"isset" => false,
			));
		}

		foreach (FavoriteMaterial::where('activity_id', $favact->id)->get() as $fav_calc_material) {
			EstimateMaterial::create(array(
				"material_name" => $fav_calc_material->material_name,
				"unit" => $fav_calc_material->unit,
				"rate" => $fav_calc_material->rate,
				"amount" => $fav_calc_material->amount,
				"activity_id" => $activity->id,
				"original" => true,
				"isset" => false,
			));
		}

		foreach (FavoriteEquipment::where('activity_id', $favact->id)->get() as $fav_calc_equipment) {
			EstimateEquipment::create(array(
				"equipment_name" => $fav_calc_equipment->equipment_name,
				"unit" => $fav_calc_equipment->unit,
				"rate" => $fav_calc_equipment->rate,
				"amount" => $fav_calc_equipment->amount,
				"activity_id" => $activity->id,
				"original" => true,
				"isset" => false,
			));
		}

		return back();
	}

	public function getCalculationSummary(Request $request, $projectid)
	{
		$project = Project::find($projectid);
		return view('calc.calc_particles.summary', ['project' => $project]);
	}

	public function getCalculationEndresult(Request $request, $projectid)
	{
		$project = Project::find($projectid);
		return view('calc.calc_particles.endresult', ['project' => $project]);
	}

	public function getEstimate(Request $request, $projectid)
	{
		$project = Project::find($projectid);
		if ($project) {
			if ($project->project_close)
				return response()->view('calc.estimate_closed');
			$invoice_end = Invoice::where('offer_id','=', Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first()->id)->where('isclose','=',true)->first();
			if ($invoice_end && $invoice_end->invoice_close)
				return response()->view('calc.estimate_closed');
		}
		return response()->view('calc.estimate');
	}

	public function getEstimateSummary(Request $request, $projectid)
	{
		$project = Project::find($projectid);
		return view('calc.estim_particles.summary', ['project' => $project]);
	}

	public function getEstimateEndresult(Request $request, $projectid)
	{
		$project = Project::find($projectid);
		return view('calc.estim_particles.endresult', ['project' => $project]);
	}

	public function getLess(Request $request, $projectid)
	{
		$project = Project::find($projectid);
		if ($project) {
			if ($project->project_close)
				return response()->view('calc.less_closed');
			$invoice_end = Invoice::where('offer_id','=', Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first()->id)->where('isclose','=',true)->first();
			if ($invoice_end && $invoice_end->invoice_close)
				return response()->view('calc.less_closed');
		}
		return response()->view('calc.less');
	}

	public function getLessSummary(Request $request, $projectid)
	{
		$project = Project::find($projectid);
		return view('calc.less_particles.summary', ['project' => $project]);
	}

	public function getLessEndresult(Request $request, $projectid)
	{
		$project = Project::find($projectid);
		return view('calc.less_particles.endresult', ['project' => $project]);
	}

	public function getMore(Request $request, $projectid)
	{
		$project = Project::find($projectid);
		$type = ProjectType::find($project->type_id);
		if ($project) {
			if ($project->project_close)
				return response()->view('calc.more_closed');
			if ($type->type_name != 'regie') {
				$invoice_end = Invoice::where('offer_id','=', Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first()->id)->where('isclose','=',true)->first();
				if ($invoice_end && $invoice_end->invoice_close)
					return response()->view('calc.more_closed');
			}
		}
		return response()->view('calc.more');
	}

	public function getMoreSummary(Request $request, $projectid)
	{
		$project = Project::find($projectid);
		return view('calc.more_particles.summary', ['project' => $project]);
	}

	public function getMoreEndresult(Request $request, $projectid)
	{
		$project = Project::find($projectid);
		return view('calc.more_particles.endresult', ['project' => $project]);
	}

	public function getInvoice(Request $request, $project, $invoice_id)
	{
		$proj = Project::find($project);
		$project_total = ResultEndresult::totalProject($proj);
		$this_inv = Invoice::find($invoice_id);
		$invoices = Invoice::where('offer_id',$this_inv->offer_id)->where('isclose',false)->get();

		foreach ($invoices as $inv) {
			$project_total -= $inv->amount;
		}

		$invoice = Invoice::where('offer_id',$this_inv->offer_id)->where('isclose',true)->first();
		$invoice->amount = $project_total;
		$invoice->rest_21 = InvoiceTerm::partTax1($proj, $invoice) * $project_total;
		$invoice->rest_6 = InvoiceTerm::partTax2($proj, $invoice) * $project_total;
		$invoice->rest_0 = InvoiceTerm::partTax3($proj, $invoice) * $project_total;
		$invoice->save();

		return view('calc.invoice');
	}

	public function getTermInvoice(Request $request)
	{
		return response()->view('calc.invoice_term');
	}

	public function getOfferAll(Request $request)
	{
		return response()->view('calc.offer_all');
	}

	public function getOffer(Request $request)
	{
		return response()->view('calc.offer');
	}

	public function getOfferPDF(Request $request)
	{
		$pdf = PDF::loadView('calc.offer_pdf');
		$pdf->setOption('footer-html','http://localhost/c4586v34674v4&vwasrt/footer_pdf?uid='.Auth::id());
		return $pdf->stream();
	}

	public function getInvoiceAll(Request $request)
	{
		return response()->view('calc.invoice_all');
	}

	public function doNewChapter(Request $request, $project_id)
	{
		$this->validate($request, [
			'chapter' => array('required','max:50'),
		]);

		$project = Project::find($project_id);
		if (!$project || !$project->isOwner()) {
			return back()->withInput($request->all());
		}

		$chapter = new Chapter;
		$chapter->chapter_name = $request->get('chapter');
		$chapter->priority = 0;
		$chapter->project_id = $project->id;

		$chapter->save();

		return back()->with('success', 'Nieuw onderdeel aangemaakt');
	}

	public function doNewCalculationActivity(Request $request, $chapter_id)
	{
		$this->validate($request, [
			'activity' => array('required','max:100'),
		]);

		$chapter = Chapter::find($chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return back()->withInput($request->all());
		}

		$part = Part::where('part_name','=','contracting')->first();
		$part_type = PartType::where('type_name','=','calculation')->first();
		$project = Project::find($chapter->project_id);

		if ($project->tax_reverse)
			$tax = Tax::where('tax_rate','=',0)->first();
		else
			$tax = Tax::where('tax_rate','=',21)->first();

		$activity = new Activity;
		$activity->activity_name = $request->get('activity');
		$activity->priority = 0;
		$activity->chapter_id = $chapter->id;
		$activity->part_id = $part->id;
		$activity->part_type_id = $part_type->id;
		$activity->tax_labor_id = $tax->id;
		$activity->tax_material_id = $tax->id;
		$activity->tax_equipment_id = $tax->id;

		$activity->save();

		return back()->with('success', 'Werkzaamheid aangemaakt');
	}

	public function doRenameCalculationActivity(Request $request)
	{
		$this->validate($request, [
			'activity_name' => array('required','max:100'),
			'activity' => array('required','max:100'),
		]);

		$activity = Activity::find($request->input('activity'));
		if (!$activity)
			return back();
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return back();
		}

		$part = Part::where('part_name','=','contracting')->first();
		$part_type = PartType::where('type_name','=','calculation')->first();
		$project = Project::find($chapter->project_id);

		if ($project->tax_reverse)
			$tax = Tax::where('tax_rate','=',0)->first();
		else
			$tax = Tax::where('tax_rate','=',21)->first();

		$activity->activity_name = $request->get('activity_name');

		$activity->save();

		return back()->with('success', 'Werkzaamheid aangepast');
	}

	public function doNewEstimateActivity(Request $request, $chapter_id)
	{
		$this->validate($request, [
			'activity' => array('required','max:100'),
		]);

		$chapter = Chapter::find($chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return back()->withInput($request->all());
		}

		$part = Part::where('part_name','=','contracting')->first();
		$part_type = PartType::where('type_name','=','estimate')->first();
		$project = Project::find($chapter->project_id);
		if ($project->tax_reverse)
			$tax = Tax::where('tax_rate','=',0)->first();
		else
			$tax = Tax::where('tax_rate','=',21)->first();

		$activity = new Activity;
		$activity->activity_name = $request->get('activity');
		$activity->priority = 0;
		$activity->chapter_id = $chapter->id;
		$activity->part_id = $part->id;
		$activity->part_type_id = $part_type->id;
		$activity->tax_labor_id = $tax->id;
		$activity->tax_material_id = $tax->id;
		$activity->tax_equipment_id = $tax->id;

		$activity->save();

		return back()->with('success', 'Nieuwe stelpostwerzaamheid aangemaakt');
	}

	public function doUpdateTax(Request $request)
	{
		$this->validate($request, [
			'value' => array('required','integer'),
			'type' => array('required'),
			'activity' => array('required','integer')
		]);

		$activity = Activity::find($request->input('activity'));
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$type = $request->input('type');
		if ($type == 'calc-labor') {
			$activity->tax_labor_id = $request->input('value');
		} else if ($type == 'calc-material') {
			$activity->tax_material_id = $request->input('value');
		} else if ($type == 'calc-equipment') {
			$activity->tax_equipment_id = $request->input('value');
		}
		$activity->save();

		return response()->json(['success' => 1]);
	}

	public function doUpdateEstimateTax(Request $request)
	{
		$this->validate($request, [
			'value' => array('required','integer'),
			'type' => array('required'),
			'activity' => array('required','integer')
		]);

		$activity = Activity::find($request->input('activity'));
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$type = $request->get('type');
		if ($type == 'calc-labor')
			$activity->tax_labor_id = $request->get('value');
		if ($type == 'calc-material')
			$activity->tax_material_id = $request->get('value');
		if ($type == 'calc-equipment')
			$activity->tax_equipment_id = $request->get('value');
		$activity->save();

		return response()->json(['success' => 1]);
	}

	public function doUpdatePart(Request $request)
	{
		$this->validate($request, [
			'value' => array('required','integer','min:0'),
			'activity' => array('required','integer','min:0')
		]);

		$activity = Activity::find($request->input('activity'));
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$activity->part_id = $request->get('value');
		$activity->save();

		return response()->json(['success' => 1]);
	}


	public function doUpdateNote(Request $request)
	{
		$this->validate($request, [
			'note' => array('required'),
			'activity' => array('required','integer')
		]);

		$activity = Activity::find($request->input('activity'));
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$activity->note = $request->get('note');

		$activity->save();

		return response()->json(['success' => 1]);
	}

	public function doUpdateUseTimesheet(Request $request)
	{
		$this->validate($request, [
			'state' => array('required'),
			'activity' => array('required','integer')
		]);

		$activity = Activity::find($request->input('activity'));
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$activity->use_timesheet = $request->get('state');

		$activity->save();

		return response()->json(['success' => 1]);
	}

	public function doDeleteActivity(Request $request)
	{
		$this->validate($request, [
			'activity' => array('required','integer','min:0')
		]);

		$activity = Activity::find($request->input('activity'));
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$activity->delete();

		return response()->json(['success' => 1]);
	}

	public function doDeleteChapter(Request $request)
	{
		$this->validate($request, [
			'chapter' => array('required','integer','min:0')
		]);

		$chapter = Chapter::find($request->input('chapter'));
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$chapter->delete();

		return response()->json(['success' => 1]);
	}

	public function doNewCalculationFavorite(Request $request)
	{
		$this->validate($request, [
			'activity' => array('required','integer','min:0')
		]);

		$activity = Activity::find($request->input('activity'));
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}
		$project = Project::find($chapter->project_id);

		$fav_activity = new FavoriteActivity;
		$fav_activity->activity_name = $activity->activity_name;
		$fav_activity->user_id = Auth::id();

		if ($project->tax_reverse) {
			$tax = Tax::where('tax_rate',21)->first();
			$fav_activity->tax_labor_id = $tax->id;
			$fav_activity->tax_material_id = $tax->id;
			$fav_activity->tax_equipment_id = $tax->id;
		} else {
			$fav_activity->tax_labor_id = $activity->tax_labor_id;
			$fav_activity->tax_material_id = $activity->tax_material_id;
			$fav_activity->tax_equipment_id = $activity->tax_equipment_id;
		}

		$fav_activity->save();

		foreach (CalculationLabor::where('activity_id', $activity->id)->get() as $orig_calc_labor) {
			$calc_labor = new FavoriteLabor;
			$calc_labor->rate = $orig_calc_labor->rate;
			$calc_labor->amount = $orig_calc_labor->amount;
			$calc_labor->activity_id = $fav_activity->id;

			$calc_labor->save();
		}
		
		foreach (CalculationMaterial::where('activity_id', $activity->id)->get() as $orig_calc_material) {
			$calc_material = new FavoriteMaterial;
			$calc_material->material_name = $orig_calc_material->material_name;
			$calc_material->unit = $orig_calc_material->unit;
			$calc_material->rate = $orig_calc_material->rate;
			$calc_material->amount = $orig_calc_material->amount;
			$calc_material->activity_id = $fav_activity->id;

			$calc_material->save();
		}

		foreach (CalculationEquipment::where('activity_id', $activity->id)->get() as $orig_calc_equipment) {
			$calc_equipment = new FavoriteEquipment;
			$calc_equipment->equipment_name = $orig_calc_equipment->equipment_name;
			$calc_equipment->unit = $orig_calc_equipment->unit;
			$calc_equipment->rate = $orig_calc_equipment->rate;
			$calc_equipment->amount = $orig_calc_equipment->amount;
			$calc_equipment->activity_id = $fav_activity->id;

			$calc_equipment->save();
		}

		return response()->json(['success' => 1]);
	}

	public function doNewEstimateFavorite(Request $request)
	{
		$this->validate($request, [
			'activity' => array('required','integer','min:0')
		]);

		$activity = Activity::find($request->input('activity'));
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}
		$project = Project::find($chapter->project_id);

		$fav_activity = new FavoriteActivity;
		$fav_activity->activity_name = $activity->activity_name;
		$fav_activity->user_id = Auth::id();

		if ($project->tax_reverse) {
			$tax = Tax::where('tax_rate',21)->first();
			$fav_activity->tax_labor_id = $tax->id;
			$fav_activity->tax_material_id = $tax->id;
			$fav_activity->tax_equipment_id = $tax->id;
		} else {
			$fav_activity->tax_labor_id = $activity->tax_labor_id;
			$fav_activity->tax_material_id = $activity->tax_material_id;
			$fav_activity->tax_equipment_id = $activity->tax_equipment_id;
		}

		$fav_activity->save();

		foreach (EstimateLabor::where('activity_id', $activity->id)->get() as $orig_calc_labor) {
			$calc_labor = new FavoriteLabor;
			$calc_labor->rate = $orig_calc_labor->rate;
			$calc_labor->amount = $orig_calc_labor->amount;
			$calc_labor->activity_id = $fav_activity->id;

			$calc_labor->save();
		}
		
		foreach (EstimateMaterial::where('activity_id', $activity->id)->get() as $orig_calc_material) {
			$calc_material = new FavoriteMaterial;
			$calc_material->material_name = $orig_calc_material->material_name;
			$calc_material->unit = $orig_calc_material->unit;
			$calc_material->rate = $orig_calc_material->rate;
			$calc_material->amount = $orig_calc_material->amount;
			$calc_material->activity_id = $fav_activity->id;

			$calc_material->save();
		}

		foreach (EstimateEquipment::where('activity_id', $activity->id)->get() as $orig_calc_equipment) {
			$calc_equipment = new FavoriteEquipment;
			$calc_equipment->equipment_name = $orig_calc_equipment->equipment_name;
			$calc_equipment->unit = $orig_calc_equipment->unit;
			$calc_equipment->rate = $orig_calc_equipment->rate;
			$calc_equipment->amount = $orig_calc_equipment->amount;
			$calc_equipment->activity_id = $fav_activity->id;

			$calc_equipment->save();
		}

		return response()->json(['success' => 1]);
	}

	public function doNewCalculationMaterial(Request $request)
	{
		$this->validate($request, [
			'name' => array('required','max:100'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0')
		]);

		$activity = Activity::find($request->input('activity'));
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$material = CalculationMaterial::create(array(
			"material_name" => $request->get('name'),
			"unit" => $request->get('unit'),
			"rate" => str_replace(',', '.', str_replace('.', '' , $request->get('rate'))),
			"amount" => str_replace(',', '.', str_replace('.', '' , $request->get('amount'))),
			"activity_id" => $activity->id,
		));

		return response()->json(['success' => 1, 'id' => $material->id]);
	}

	public function doNewCalculationEquipment(Request $request)
	{
		$this->validate($request, [
			'name' => array('required','max:100'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0')
		]);

		$activity = Activity::find($request->input('activity'));
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$equipment = CalculationEquipment::create(array(
			"equipment_name" => $request->get('name'),
			"unit" => $request->get('unit'),
			"rate" => str_replace(',', '.', str_replace('.', '' , $request->get('rate'))),
			"amount" => str_replace(',', '.', str_replace('.', '' , $request->get('amount'))),
			"activity_id" => $activity->id,
		));

		return response()->json(['success' => 1, 'id' => $equipment->id]);
	}

	public function doNewCalculationLabor(Request $request)
	{
		$this->validate($request, [
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0')
		]);

		$activity = Activity::find($request->input('activity'));
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$rate = $request->get('rate');
		if (empty($rate)) {
			$_activity = Activity::find($request->input('activity'));
			$_chapter = Chapter::find($_activity->chapter_id);
			$_project = Project::find($_chapter->project_id);
			$rate = $_project->hour_rate;
		} else {
			$rate = str_replace(',', '.', str_replace('.', '' , $rate));
		}
		$labor = CalculationLabor::create(array(
			"rate" => $rate,
			"amount" => str_replace(',', '.', str_replace('.', '' , $request->get('amount'))),
			"activity_id" => $activity->id,
		));

		return response()->json(['success' => 1, 'id' => $labor->id]);
	}

	public function doDeleteCalculationLabor(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
		]);

		$rec = CalculationLabor::find($request->input('id'));
		if (!$rec)
			return response()->json(['success' => 0]);
		$activity = Activity::find($rec->activity_id);
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$rec->delete();

		return response()->json(['success' => 1]);
	}

	public function doDeleteCalculationMaterial(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
		]);

		$rec = CalculationMaterial::find($request->input('id'));
		if (!$rec)
			return response()->json(['success' => 0]);
		$activity = Activity::find($rec->activity_id);
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$rec->delete();

		return response()->json(['success' => 1]);
	}

	public function doDeleteCalculationEquipment(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
		]);

		$rec = CalculationEquipment::find($request->input('id'));
		if (!$rec)
			return response()->json(['success' => 0]);
		$activity = Activity::find($rec->activity_id);
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$rec->delete();

		return response()->json(['success' => 1]);
	}

	public function doUpdateCalculationMaterial(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'name' => array('max:100'),
			'unit' => array('max:10'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		]);

		$material = CalculationMaterial::find($request->input('id'));
		if (!$material)
			return response()->json(['success' => 0]);
		$activity = Activity::find($material->activity_id);
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$material->material_name = $request->get('name');
		$material->unit = $request->get('unit');
		$material->rate = str_replace(',', '.', str_replace('.', '' , $request->get('rate')));
		$material->amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));

		$material->save();

		return response()->json(['success' => 1]);
	}

	public function doUpdateCalculationEquipment(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'name' => array('max:100'),
			'unit' => array('max:10'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		]);

		$equipment = CalculationEquipment::find($request->input('id'));
		if (!$equipment)
			return response()->json(['success' => 0]);
		$activity = Activity::find($equipment->activity_id);
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$equipment->equipment_name = $request->get('name');
		$equipment->unit = $request->get('unit');
		$equipment->rate = str_replace(',', '.', str_replace('.', '' , $request->get('rate')));
		$equipment->amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));

		$equipment->save();

		return response()->json(['success' => 1]);
	}

	public function doUpdateCalculationLabor(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		]);

		$labor = CalculationLabor::find($request->input('id'));
		if (!$labor)
			return response()->json(['success' => 0]);
		$activity = Activity::find($labor->activity_id);
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$rate = $request->get('rate');
		if (empty($rate)) {
			$_labor = CalculationLabor::find($request->input('id'));
			$_activity = Activity::find($_labor->activity_id);
			$_chapter = Chapter::find($_activity->chapter_id);
			$_project = Project::find($_chapter->project_id);
			$rate = $_project->hour_rate;
		} else {
			$rate = str_replace(',', '.', str_replace('.', '' , $rate));
		}

		$labor->rate = $rate;
		$labor->amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));

		$labor->save();

		return response()->json(['success' => 1]);
	}

	public function doNewEstimateMaterial(Request $request)
	{
		$this->validate($request, [
			'name' => array('required','max:100'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0')
		]);

		$activity = Activity::find($request->input('activity'));
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$material = EstimateMaterial::create(array(
			"material_name" => $request->get('name'),
			"unit" => $request->get('unit'),
			"rate" => str_replace(',', '.', str_replace('.', '' , $request->get('rate'))),
			"amount" => str_replace(',', '.', str_replace('.', '' , $request->get('amount'))),
			"activity_id" => $activity->id,
			"original" => true,
			"isset" => false
		));

		return response()->json(['success' => 1, 'id' => $material->id]);
	}

	public function doNewEstimateEquipment(Request $request)
	{
		$this->validate($request, [
			'name' => array('required','max:100'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0')
		]);

		$activity = Activity::find($request->input('activity'));
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$equipment = EstimateEquipment::create(array(
			"equipment_name" => $request->get('name'),
			"unit" => $request->get('unit'),
			"rate" => str_replace(',', '.', str_replace('.', '' , $request->get('rate'))),
			"amount" => str_replace(',', '.', str_replace('.', '' , $request->get('amount'))),
			"activity_id" => $activity->id,
			"original" => true,
			"isset" => false
		));

		return response()->json(['success' => 1, 'id' => $equipment->id]);
	}

	public function doNewEstimateLabor(Request $request)
	{
		$this->validate($request, [
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0')
		]);

		$activity = Activity::find($request->input('activity'));
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$rate = $request->get('rate');
		if (empty($rate)) {
			$_activity = Activity::find($request->input('activity'));
			$_chapter = Chapter::find($_activity->chapter_id);
			$_project = Project::find($_chapter->project_id);
			$rate = $_project->hour_rate;
		} else {
			$rate = str_replace(',', '.', str_replace('.', '' , $rate));
		}
		$labor = EstimateLabor::create(array(
			"rate" => $rate,
			"amount" => str_replace(',', '.', str_replace('.', '' , $request->get('amount'))),
			"activity_id" => $activity->id,
			"original" => true,
			"isset" => false
		));

		return response()->json(['success' => 1, 'id' => $labor->id]);
	}

	public function doDeleteEstimateLabor(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
		]);

		$rec = EstimateLabor::find($request->input('id'));
		if (!$rec)
			return response()->json(['success' => 0]);
		$activity = Activity::find($rec->activity_id);
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$rec->delete();

		return response()->json(['success' => 1]);
	}

	public function doDeleteEstimateMaterial(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
		]);

		$rec = EstimateMaterial::find($request->input('id'));
		if (!$rec)
			return response()->json(['success' => 0]);
		$activity = Activity::find($rec->activity_id);
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$rec->delete();

		return response()->json(['success' => 1]);
	}

	public function doDeleteEstimateEquipment(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
		]);

		$rec = EstimateEquipment::find($request->input('id'));
		if (!$rec)
			return response()->json(['success' => 0]);
		$activity = Activity::find($rec->activity_id);
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$rec->delete();

		return response()->json(['success' => 1]);
	}

	public function doUpdateEstimateMaterial(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'name' => array('max:100'),
			'unit' => array('max:10'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		]);

		$material = EstimateMaterial::find($request->input('id'));
		if (!$material)
			return response()->json(['success' => 0]);
		$activity = Activity::find($material->activity_id);
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$material->material_name = $request->get('name');
		$material->unit = $request->get('unit');
		$material->rate = str_replace(',', '.', str_replace('.', '' , $request->get('rate')));
		$material->amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));

		$material->save();

		return response()->json(['success' => 1]);
	}

	public function doUpdateEstimateEquipment(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'name' => array('max:100'),
			'unit' => array('max:10'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		]);

		$equipment = EstimateEquipment::find($request->input('id'));
		if (!$equipment)
			return response()->json(['success' => 0]);
		$activity = Activity::find($equipment->activity_id);
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$equipment->equipment_name = $request->get('name');
		$equipment->unit = $request->get('unit');
		$equipment->rate = str_replace(',', '.', str_replace('.', '' , $request->get('rate')));
		$equipment->amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));

		$equipment->save();

		return response()->json(['success' => 1]);
	}

	public function doUpdateEstimateLabor(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		]);

		$labor = EstimateLabor::find($request->input('id'));
		if (!$labor)
			return response()->json(['success' => 0]);
		$activity = Activity::find($labor->activity_id);
		if (!$activity)
			return response()->json(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return response()->json(['success' => 0]);
		}

		$rate = $request->get('rate');
		if (empty($rate)) {
			$_labor = EstimateLabor::find($request->input('id'));
			$_activity = Activity::find($_labor->activity_id);
			$_chapter = Chapter::find($_activity->chapter_id);
			$_project = Project::find($_chapter->project_id);
			$rate = $_project->hour_rate;
		} else {
			$rate = str_replace(',', '.', str_replace('.', '' , $rate));
		}

		$labor->rate = $rate;
		$labor->amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));

		$labor->save();

		return response()->json(['success' => 1]);
	}
}
