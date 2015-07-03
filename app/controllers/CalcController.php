<?php

class CalcController extends BaseController {

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

	public function getCalculation()
	{
		return View::make('calc.calculation');
	}


	public function getEstimate()
	{
		return View::make('calc.estimate');
	}

	public function getLess()
	{
		return View::make('calc.less');
	}

	public function getMore()
	{
		return View::make('calc.more');
	}

	public function getInvoice()
	{
		return View::make('calc.invoice');
	}

	public function getTermInvoice()
	{
		return View::make('calc.invoice_term');
	}

	public function getOffer()
	{
		return View::make('calc.offer');
	}

	public function getOfferPDF()
	{
		return View::make('calc.offer_pdf');
	}

	public function getInvoiceAll()
	{
		return View::make('calc.invoice_all');
	}

	public function getInvoicepdf()
	{
		return View::make('calc.invoicepdf');
	}

	public function doNewChapter()
	{
		$rules = array(
			'chapter' => array('required','max:50'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {

			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$chapter = new Chapter;
			$chapter->chapter_name = Input::get('chapter');
			$chapter->priority = 0;
			$chapter->project_id = Route::Input('project_id');

			$chapter->save();

			return Redirect::back()->with('success', 1);
		}

	}

	public function doNewCalculationActivity()
	{
		$rules = array(
			'activity' => array('required','max:50'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {

			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$part = Part::where('part_name','=','contracting')->first();
			$part_type = PartType::where('type_name','=','calculation')->first();
			$tax = Tax::where('tax_rate','=',21)->first();

			$activity = new Activity;
			$activity->activity_name = Input::get('activity');
			$activity->priority = 0;
			$activity->chapter_id = Route::Input('chapter_id');
			$activity->part_id = $part->id;
			$activity->part_type_id = $part_type->id;
			$activity->tax_calc_labor_id = $tax->id;
			$activity->tax_calc_material_id = $tax->id;
			$activity->tax_calc_equipment_id = $tax->id;
			$activity->tax_more_labor_id = $tax->id;
			$activity->tax_more_material_id = $tax->id;
			$activity->tax_more_equipment_id = $tax->id;
			$activity->tax_estimate_labor_id = $tax->id;
			$activity->tax_estimate_material_id = $tax->id;
			$activity->tax_estimate_equipment_id = $tax->id;

			$activity->save();

			return Redirect::back()->with('success', 1);

		}
	}

	public function doNewEstimateActivity()
	{
		$rules = array(
			'activity' => array('required','max:50'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {

			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$part = Part::where('part_name','=','contracting')->first();
			$part_type = PartType::where('type_name','=','estimate')->first();
			$tax = Tax::where('tax_rate','=',21)->first();

			$activity = new Activity;
			$activity->activity_name = Input::get('activity');
			$activity->priority = 0;
			$activity->chapter_id = Route::Input('chapter_id');
			$activity->part_id = $part->id;
			$activity->part_type_id = $part_type->id;
			$activity->tax_calc_labor_id = $tax->id;
			$activity->tax_calc_material_id = $tax->id;
			$activity->tax_calc_equipment_id = $tax->id;
			$activity->tax_more_labor_id = $tax->id;
			$activity->tax_more_material_id = $tax->id;
			$activity->tax_more_equipment_id = $tax->id;
			$activity->tax_estimate_labor_id = $tax->id;
			$activity->tax_estimate_material_id = $tax->id;
			$activity->tax_estimate_equipment_id = $tax->id;

			$activity->save();

			return Redirect::back()->with('success', 1);

		}
	}

	public function doUpdateTax()
	{
		$rules = array(
			'value' => array('required','integer'),
			'type' => array('required'),
			'activity' => array('required','integer')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			$type = Input::get('type');
			$activity = Activity::find(Input::get('activity'));
			if ($type == 'calc-labor')
				$activity->tax_calc_labor_id = Input::get('value');
			if ($type == 'calc-material')
				$activity->tax_calc_material_id = Input::get('value');
			if ($type == 'calc-equipment')
				$activity->tax_calc_equipment_id = Input::get('value');
			$activity->save();

			return json_encode(['success' => 1]);
		}
	}

	public function doUpdatePart()
	{
		$rules = array(
			'value' => array('required','integer','min:0'),
			'activity' => array('required','integer','min:0')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$activity = Activity::find(Input::get('activity'));
			$activity->part_id = Input::get('value');
			$activity->save();

			return json_encode(['success' => 1]);
		}
	}

	public function doDeleteActivity()
	{
		$rules = array(
			'activity' => array('required','integer','min:0')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			Activity::destroy(Input::get('activity'));

			return json_encode(['success' => 1]);
		}
	}

	public function doDeleteChapter()
	{
		$rules = array(
			'chapter' => array('required','integer','min:0')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			Chapter::destroy(Input::get('chapter'));

			return json_encode(['success' => 1]);
		}
	}

	public function doNewCalculationMaterial()
	{
		$rules = array(
			'name' => array('required','max:50'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			$material = CalculationMaterial::create(array(
				"material_name" => Input::get('name'),
				"unit" => Input::get('unit'),
				"rate" => str_replace(',', '.', str_replace('.', '' , Input::get('rate'))),
				"amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => Input::get('activity'),
			));

			return json_encode(['success' => 1, 'id' => $material->id]);
		}
	}

	public function doNewCalculationEquipment()
	{
		$rules = array(
			'name' => array('required','max:50'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			$equipment = CalculationEquipment::create(array(
				"equipment_name" => Input::get('name'),
				"unit" => Input::get('unit'),
				"rate" => str_replace(',', '.', str_replace('.', '' , Input::get('rate'))),
				"amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => Input::get('activity'),
			));

			return json_encode(['success' => 1, 'id' => $equipment->id]);
		}
	}

	public function doNewCalculationLabor()
	{
		$rules = array(
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
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
				"activity_id" => Input::get('activity'),
			));

			return json_encode(['success' => 1, 'id' => $labor->id]);
		}
	}

	public function doDeleteCalculationMaterial()
	{
		$rules = array(
			'id' => array('required','integer','min:0'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			CalculationMaterial::destroy(Input::get('id'));

			return json_encode(['success' => 1]);
		}
	}

	public function doDeleteCalculationEquipment()
	{
		$rules = array(
			'id' => array('required','integer','min:0'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			CalculationEquipment::destroy(Input::get('id'));

			return json_encode(['success' => 1]);
		}
	}

	public function doUpdateCalculationMaterial()
	{
		$rules = array(
			'id' => array('integer','min:0'),
			'name' => array('max:50'),
			'unit' => array('max:10'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$material = CalculationMaterial::find(Input::get('id'));
			$material->material_name = Input::get('name');
			$material->unit = Input::get('unit');
			$material->rate = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$material->amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			$material->save();

			return json_encode(['success' => 1]);
		}
	}

	public function doUpdateCalculationEquipment()
	{
		$rules = array(
			'id' => array('integer','min:0'),
			'name' => array('max:50'),
			'unit' => array('max:10'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$equipment = CalculationEquipment::find(Input::get('id'));
			$equipment->equipment_name = Input::get('name');
			$equipment->unit = Input::get('unit');
			$equipment->rate = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$equipment->amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			$equipment->save();

			return json_encode(['success' => 1]);
		}
	}

	public function doUpdateCalculationLabor()
	{
		$rules = array(
			'id' => array('integer','min:0'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
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
			$labor = CalculationLabor::find(Input::get('id'));
			$labor->rate = $rate;
			$labor->amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			$labor->save();

			return json_encode(['success' => 1]);
		}
	}

	public function doNewEstimateMaterial()
	{
		$rules = array(
			'name' => array('required','max:50'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			$material = EstimateMaterial::create(array(
				"material_name" => Input::get('name'),
				"unit" => Input::get('unit'),
				"rate" => str_replace(',', '.', str_replace('.', '' , Input::get('rate'))),
				"amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => Input::get('activity'),
				"original" => true,
				"isset" => false
			));

			return json_encode(['success' => 1, 'id' => $material->id]);
		}
	}

	public function doNewEstimateEquipment()
	{
		$rules = array(
			'name' => array('required','max:50'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			$equipment = EstimateEquipment::create(array(
				"equipment_name" => Input::get('name'),
				"unit" => Input::get('unit'),
				"rate" => str_replace(',', '.', str_replace('.', '' , Input::get('rate'))),
				"amount" => str_replace(',', '.', str_replace('.', '' , Input::get('amount'))),
				"activity_id" => Input::get('activity'),
				"original" => true,
				"isset" => false
			));

			return json_encode(['success' => 1, 'id' => $equipment->id]);
		}
	}

	public function doNewEstimateLabor()
	{
		$rules = array(
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
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
				"activity_id" => Input::get('activity'),
				"original" => true,
				"isset" => false
			));

			return json_encode(['success' => 1, 'id' => $labor->id]);
		}
	}

	public function doDeleteEstimateMaterial()
	{
		$rules = array(
			'id' => array('required','integer','min:0'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			EstimateMaterial::destroy(Input::get('id'));

			return json_encode(['success' => 1]);
		}
	}

	public function doDeleteEstimateEquipment()
	{
		$rules = array(
			'id' => array('required','integer','min:0'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			EstimateEquipment::destroy(Input::get('id'));

			return json_encode(['success' => 1]);
		}
	}

	public function doUpdateEstimateMaterial()
	{
		$rules = array(
			'id' => array('integer','min:0'),
			'name' => array('max:50'),
			'unit' => array('max:10'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$material = EstimateMaterial::find(Input::get('id'));
			$material->material_name = Input::get('name');
			$material->unit = Input::get('unit');
			$material->rate = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$material->amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			$material->save();

			return json_encode(['success' => 1]);
		}
	}

	public function doUpdateEstimateEquipment()
	{
		$rules = array(
			'id' => array('integer','min:0'),
			'name' => array('max:50'),
			'unit' => array('max:10'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$equipment = EstimateEquipment::find(Input::get('id'));
			$equipment->equipment_name = Input::get('name');
			$equipment->unit = Input::get('unit');
			$equipment->rate = str_replace(',', '.', str_replace('.', '' , Input::get('rate')));
			$equipment->amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			$equipment->save();

			return json_encode(['success' => 1]);
		}
	}

	public function doUpdateEstimateLabor()
	{
		$rules = array(
			'id' => array('integer','min:0'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
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
			$labor = EstimateLabor::find(Input::get('id'));
			$labor->rate = $rate;
			$labor->amount = str_replace(',', '.', str_replace('.', '' , Input::get('amount')));

			$labor->save();

			return json_encode(['success' => 1]);
		}
	}
}
