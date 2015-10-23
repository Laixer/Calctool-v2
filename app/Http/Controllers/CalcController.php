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

class CalcController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function getCalculation(Request $request)
	{
		$project = Project::find($request->input('project_id'));
		if ($project) {
			if ($project->project_close)
				return response()->view('calc.calculation_closed');
			$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
			if ($offer_last && $offer_last->offer_finish)
				return response()->view('calc.calculation_closed');
		}
		return response()->view('calc.calculation');
	}

	public function getEstimate(Request $request)
	{
		$project = Project::find(Route::Input('project_id'));
		if ($project) {
			if ($project->project_close)
				return response()->view('calc.estimate_closed');
			$invoice_end = Invoice::where('offer_id','=', Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first()->id)->where('isclose','=',true)->first();
			if ($invoice_end->invoice_close)
				return response()->view('calc.estimate_closed');
		}
		return response()->view('calc.estimate');
	}

	public function getLess(Request $request)
	{
		$project = Project::find(Route::Input('project_id'));
		if ($project) {
			if ($project->project_close)
				return response()->view('calc.less_closed');
			$invoice_end = Invoice::where('offer_id','=', Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first()->id)->where('isclose','=',true)->first();
			if ($invoice_end->invoice_close)
				return response()->view('calc.less_closed');
		}
		return response()->view('calc.less');
	}

	public function getMore(Request $request)
	{
		$project = Project::find(Route::Input('project_id'));
		if ($project) {
			if ($project->project_close)
				return response()->view('calc.more_closed');
			$invoice_end = Invoice::where('offer_id','=', Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first()->id)->where('isclose','=',true)->first();
			if ($invoice_end->invoice_close)
				return response()->view('calc.more_closed');
		}
		return response()->view('calc.more');
	}

	public function getInvoice(Request $request)
	{
		return response()->view('calc.invoice');
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

	public function getOfferDownloadPDF(Request $request)
	{
		$pdf = PDF::loadView('calc.offer_pdf');
		$pdf->setOption('footer-html','http://localhost/c4586v34674v4&vwasrt/footer_pdf?uid='.Auth::id());
		return $pdf->download(Input::get('file'));
	}

	public function getInvoiceAll(Request $request)
	{
		return response()->view('calc.invoice_all');
	}

	public function getInvoicePDF(Request $request)
	{
		$pdf = PDF::loadView('calc.invoice_pdf');
		$pdf->setOption('footer-html','http://localhost/c4586v34674v4&vwasrt/footer_pdf?uid='.Auth::id());
		return $pdf->stream();
	}

	public function getInvoiceDownloadPDF(Request $request)
	{
		$pdf = PDF::loadView('calc.invoice_pdf');
		$pdf->setOption('footer-html','http://localhost/c4586v34674v4&vwasrt/footer_pdf?uid='.Auth::id());
		return $pdf->download(Input::get('file'));
	}

	public function getTermInvoicePDF(Request $request)
	{
		$pdf = PDF::loadView('calc.invoice_term_pdf');
		return $pdf->stream();
	}

	public function getTermInvoiceDownloadPDF(Request $request)
	{
		$pdf = PDF::loadView('calc.invoice_term_pdf');
		return $pdf->download(Input::get('file'));
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

			return back()->with('success', 1);
	}

	public function doNewCalculationActivity(Request $request, $chapter_id)
	{
		$this->validate($request, [
			'activity' => array('required','max:50'),
		]);

			$chapter = Chapter::find($chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return Redirect::back()->withInput(Input::all());
			}

			$part = Part::where('part_name','=','contracting')->first();
			$part_type = PartType::where('type_name','=','calculation')->first();
			$project = Project::find($chapter->project_id);
			if (ProjectType::find($project->type_id)->type_name == 'BTW verlegd')
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

			return back()->with('success', 1);
	}

	public function doNewEstimateActivity(Request $request)
	{
		$this->validate($request, [
			'activity' => array('required','max:50'),
		]);

			$chapter = Chapter::find(Route::Input('chapter_id'));
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return Redirect::back()->withInput(Input::all());
			}

			$part = Part::where('part_name','=','contracting')->first();
			$part_type = PartType::where('type_name','=','estimate')->first();
			$project = Project::find($chapter->project_id);
			if (ProjectType::find($project->type_id)->type_name == 'BTW verlegd')
				$tax = Tax::where('tax_rate','=',0)->first();
			else
				$tax = Tax::where('tax_rate','=',21)->first();

			$activity = new Activity;
			$activity->activity_name = Input::get('activity');
			$activity->priority = 0;
			$activity->chapter_id = $chapter->id;
			$activity->part_id = $part->id;
			$activity->part_type_id = $part_type->id;
			$activity->tax_labor_id = $tax->id;
			$activity->tax_material_id = $tax->id;
			$activity->tax_equipment_id = $tax->id;

			$activity->save();

			return back()->with('success', 1);
	}

	public function doUpdateTax(Request $request)
	{
		$this->validate($request, [
			'value' => array('required','integer'),
			'type' => array('required'),
			'activity' => array('required','integer')
		]);

			$activity = Activity::find(Input::get('activity'));
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$type = Input::get('type');
			if ($type == 'calc-labor') {
				$activity->tax_labor_id = Input::get('value');
			} else if ($type == 'calc-material') {
				$activity->tax_material_id = Input::get('value');
			} else if ($type == 'calc-equipment') {
				$activity->tax_equipment_id = Input::get('value');
			}
			$activity->save();

			return json_encode(['success' => 1]);
	}

	public function doUpdateEstimateTax(Request $request)
	{
		$this->validate($request, [
			'value' => array('required','integer'),
			'type' => array('required'),
			'activity' => array('required','integer')
		]);

			$activity = Activity::find(Input::get('activity'));
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$type = Input::get('type');
			if ($type == 'calc-labor')
				$activity->tax_labor_id = Input::get('value');
			if ($type == 'calc-material')
				$activity->tax_material_id = Input::get('value');
			if ($type == 'calc-equipment')
				$activity->tax_equipment_id = Input::get('value');
			$activity->save();

			return json_encode(['success' => 1]);
	}

	public function doUpdatePart(Request $request)
	{
		$this->validate($request, [
			'value' => array('required','integer','min:0'),
			'activity' => array('required','integer','min:0')
		]);

			$activity = Activity::find(Input::get('activity'));
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$activity->part_id = Input::get('value');
			$activity->save();

			return json_encode(['success' => 1]);
	}


	public function doUpdateNote(Request $request)
	{
		$this->validate($request, [
			'note' => array('required'),
			'activity' => array('required','integer')
		]);

			$activity = Activity::find(Input::get('activity'));
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$activity->note = Input::get('note');

			$activity->save();

			return json_encode(['success' => 1]);
	}

	public function doDeleteActivity(Request $request)
	{
		$this->validate($request, [
			'activity' => array('required','integer','min:0')
		]);

			$activity = Activity::find(Input::get('activity'));
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$activity->delete();

			return json_encode(['success' => 1]);
	}

	public function doDeleteChapter(Request $request)
	{
		$this->validate($request, [
			'chapter' => array('required','integer','min:0')
		]);

			$chapter = Chapter::find(Input::get('chapter'));
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$chapter->delete();

			return json_encode(['success' => 1]);
	}

	public function doNewCalculationMaterial(Request $request)
	{
		$this->validate($request, [
			'name' => array('required','max:50'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0')
		]);

			$activity = Activity::find(Input::get('activity'));
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$material = CalculationMaterial::create(array(
				"material_name" => Input::get('name'),
				"unit" => Input::get('unit'),
				"rate" => str_replace(',', '.', str_replace('.', '' , Input::get('rate'))),
				"amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => $activity->id,
			));

			return json_encode(['success' => 1, 'id' => $material->id]);
	}

	public function doNewCalculationEquipment(Request $request)
	{
		$this->validate($request, [
			'name' => array('required','max:50'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0')
		]);

			$activity = Activity::find(Input::get('activity'));
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$equipment = CalculationEquipment::create(array(
				"equipment_name" => Input::get('name'),
				"unit" => Input::get('unit'),
				"rate" => str_replace(',', '.', str_replace('.', '' , Input::get('rate'))),
				"amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => $activity->id,
			));

			return json_encode(['success' => 1, 'id' => $equipment->id]);
	}

	public function doNewCalculationLabor(Request $request)
	{
		$this->validate($request, [
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0')
		]);

			$activity = Activity::find(Input::get('activity'));
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$rate = Input::get('rate');
			if (empty($rate)) {
				$_activity = Activity::find(Input::get('activity'));
				$_chapter = Chapter::find($_activity->chapter_id);
				$_project = Project::find($_chapter->project_id);
				$rate = $_project->hour_rate;
			} else {
				$rate = str_replace(',', '.', str_replace('.', '' , $rate));
			}
			$labor = CalculationLabor::create(array(
				"rate" => $rate,
				"amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => $activity->id,
			));

			return json_encode(['success' => 1, 'id' => $labor->id]);
	}

	public function doDeleteCalculationLabor(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
		]);

			$rec = CalculationLabor::find(Input::get('id'));
			if (!$rec)
				return json_encode(['success' => 0]);
			$activity = Activity::find($rec->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$rec->delete();

			return json_encode(['success' => 1]);
	}

	public function doDeleteCalculationMaterial(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
		]);

			$rec = CalculationMaterial::find(Input::get('id'));
			if (!$rec)
				return json_encode(['success' => 0]);
			$activity = Activity::find($rec->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$rec->delete();

			return json_encode(['success' => 1]);
	}

	public function doDeleteCalculationEquipment(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
		]);

			$rec = CalculationEquipment::find(Input::get('id'));
			if (!$rec)
				return json_encode(['success' => 0]);
			$activity = Activity::find($rec->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$rec->delete();

			return json_encode(['success' => 1]);
	}

	public function doUpdateCalculationMaterial(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'name' => array('max:50'),
			'unit' => array('max:10'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		]);

			$material = CalculationMaterial::find(Input::get('id'));
			if (!$material)
				return json_encode(['success' => 0]);
			$activity = Activity::find($material->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$material->material_name = Input::get('name');
			$material->unit = Input::get('unit');
			$material->rate = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$material->amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			$material->save();

			return json_encode(['success' => 1]);
	}

	public function doUpdateCalculationEquipment(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'name' => array('max:50'),
			'unit' => array('max:10'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		]);

			$equipment = CalculationEquipment::find(Input::get('id'));
			if (!$equipment)
				return json_encode(['success' => 0]);
			$activity = Activity::find($equipment->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$equipment->equipment_name = Input::get('name');
			$equipment->unit = Input::get('unit');
			$equipment->rate = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$equipment->amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			$equipment->save();

			return json_encode(['success' => 1]);
	}

	public function doUpdateCalculationLabor(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		]);

			$labor = CalculationLabor::find(Input::get('id'));
			if (!$labor)
				return json_encode(['success' => 0]);
			$activity = Activity::find($labor->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$rate = Input::get('rate');
			if (empty($rate)) {
				$_labor = CalculationLabor::find(Input::get('id'));
				$_activity = Activity::find($_labor->activity_id);
				$_chapter = Chapter::find($_activity->chapter_id);
				$_project = Project::find($_chapter->project_id);
				$rate = $_project->hour_rate;
			} else {
				$rate = str_replace(',', '.', str_replace('.', '' , $rate));
			}

			$labor->rate = $rate;
			$labor->amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			$labor->save();

			return json_encode(['success' => 1]);
	}

	public function doNewEstimateMaterial(Request $request)
	{
		$this->validate($request, [
			'name' => array('required','max:50'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0')
		]);

			$activity = Activity::find(Input::get('activity'));
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$material = EstimateMaterial::create(array(
				"material_name" => Input::get('name'),
				"unit" => Input::get('unit'),
				"rate" => str_replace(',', '.', str_replace('.', '' , Input::get('rate'))),
				"amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => $activity->id,
				"original" => true,
				"isset" => false
			));

			return json_encode(['success' => 1, 'id' => $material->id]);
	}

	public function doNewEstimateEquipment(Request $request)
	{
		$this->validate($request, [
			'name' => array('required','max:50'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0')
		]);

			$activity = Activity::find(Input::get('activity'));
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$equipment = EstimateEquipment::create(array(
				"equipment_name" => Input::get('name'),
				"unit" => Input::get('unit'),
				"rate" => str_replace(',', '.', str_replace('.', '' , Input::get('rate'))),
				"amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => $activity->id,
				"original" => true,
				"isset" => false
			));

			return json_encode(['success' => 1, 'id' => $equipment->id]);
	}

	public function doNewEstimateLabor(Request $request)
	{
		$this->validate($request, [
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0')
		]);

			$activity = Activity::find(Input::get('activity'));
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$rate = Input::get('rate');
			if (empty($rate)) {
				$_activity = Activity::find(Input::get('activity'));
				$_chapter = Chapter::find($_activity->chapter_id);
				$_project = Project::find($_chapter->project_id);
				$rate = $_project->hour_rate;
			} else {
				$rate = str_replace(',', '.', str_replace('.', '' , $rate));
			}
			$labor = EstimateLabor::create(array(
				"rate" => $rate,
				"amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => $activity->id,
				"original" => true,
				"isset" => false
			));

			return json_encode(['success' => 1, 'id' => $labor->id]);
	}

	public function doDeleteEstimateLabor(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
		]);

			$rec = EstimateLabor::find(Input::get('id'));
			if (!$rec)
				return json_encode(['success' => 0]);
			$activity = Activity::find($rec->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$rec->delete();

			return json_encode(['success' => 1]);
	}

	public function doDeleteEstimateMaterial(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
		]);

			$rec = EstimateMaterial::find(Input::get('id'));
			if (!$rec)
				return json_encode(['success' => 0]);
			$activity = Activity::find($rec->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$rec->delete();

			return json_encode(['success' => 1]);
	}

	public function doDeleteEstimateEquipment(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
		]);

			$rec = EstimateEquipment::find(Input::get('id'));
			if (!$rec)
				return json_encode(['success' => 0]);
			$activity = Activity::find($rec->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$rec->delete();

			return json_encode(['success' => 1]);
	}

	public function doUpdateEstimateMaterial(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'name' => array('max:50'),
			'unit' => array('max:10'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		]);

			$material = EstimateMaterial::find(Input::get('id'));
			if (!$material)
				return json_encode(['success' => 0]);
			$activity = Activity::find($material->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$material->material_name = Input::get('name');
			$material->unit = Input::get('unit');
			$material->rate = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$material->amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			$material->save();

			return json_encode(['success' => 1]);
	}

	public function doUpdateEstimateEquipment(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'name' => array('max:50'),
			'unit' => array('max:10'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		]);

			$equipment = EstimateEquipment::find(Input::get('id'));
			if (!$equipment)
				return json_encode(['success' => 0]);
			$activity = Activity::find($equipment->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$equipment->equipment_name = Input::get('name');
			$equipment->unit = Input::get('unit');
			$equipment->rate = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$equipment->amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			$equipment->save();

			return json_encode(['success' => 1]);
	}

	public function doUpdateEstimateLabor(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		]);

			$labor = EstimateLabor::find(Input::get('id'));
			if (!$labor)
				return json_encode(['success' => 0]);
			$activity = Activity::find($labor->activity_id);
			if (!$activity)
				return json_encode(['success' => 0]);
			$chapter = Chapter::find($activity->chapter_id);
			if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
				return json_encode(['success' => 0]);
			}

			$rate = Input::get('rate');
			if (empty($rate)) {
				$_labor = EstimateLabor::find(Input::get('id'));
				$_activity = Activity::find($_labor->activity_id);
				$_chapter = Chapter::find($_activity->chapter_id);
				$_project = Project::find($_chapter->project_id);
				$rate = $_project->hour_rate;
			} else {
				$rate = str_replace(',', '.', str_replace('.', '' , $rate));
			}

			$labor->rate = $rate;
			$labor->amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			$labor->save();

			return json_encode(['success' => 1]);
	}
}
