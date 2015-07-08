<?php

class CostController extends BaseController {

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

	public function getTimesheet()
	{
		return View::make('cost.timesheet');
	}

	public function getPurchase()
	{
		return View::make('cost.purchase');
	}

	public function doNewTimesheet()
	{
		$rules = array(
			'date' => array('required','regex:/^20[0-9][0-9]-[0-9]{2}-[0-9]{2}$/'),
			'type' => array('required','integer'),
			'hour' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			$timesheet = Timesheet::create(array(
				'register_date' => Input::get('date'),
				'register_hour' => str_replace(',', '.', str_replace('.', '' , Input::get('hour'))),
				'activity_id' => Input::get('activity'),
				'note' => Input::get('note'),
				'timesheet_kind_id' => Input::get('type')
			));

			$_activity = Activity::find(Input::get('activity'));
			$_chapter = Chapter::find($_activity->chapter_id);
			$_project = Project::find($_chapter->project_id);

			$type = 'Aanneming';
			if (TimesheetKind::find(Input::get('type'))->kind_name == 'meerwerk')
			{
				$type = 'Meerwerk';
				$labor = MoreLabor::create(array(
					"rate" => $_project->hour_rate_more,
					"amount" => str_replace(',', '.', str_replace('.', '' , Input::get('hour'))),
					"activity_id" => Input::get('activity'),
					"hour_id" => $timesheet->id
				));
			}

			if (TimesheetKind::find(Input::get('type'))->kind_name == 'stelpost')
			{
				$type = 'Stelpost';
				$labor = EstimateLabor::create(array(
					"set_rate" => $_project->hour_rate,
					"set_amount" => str_replace(',', '.', str_replace('.', '' , Input::get('hour'))),
					"activity_id" => Input::get('activity'),
					"original" => false,
					"isset" => true,
					"hour_id" => $timesheet->id
				));
			}

			return json_encode(['success' => 1, 'type' => $type, 'activity' => Activity::find($timesheet->activity_id)->activity_name, 'hour' => number_format($timesheet->register_hour, 2,",","."), 'project' => $_project->project_name, 'id' => $timesheet->id]);
		}
	}

	public function doDeleteTimesheet()
	{
		$rules = array(
			'id' => array('required','integer')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			Timesheet::destroy(Input::get('id'));

			return json_encode(['success' => 1]);
		}
	}

	public function doNewPurchase()
	{
		$rules = array(
			'date' => array('required','regex:/^20[0-9][0-9]-[0-9]{2}-[0-9]{2}$/'),
			'type' => array('required','integer'),
			'hour' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'project' => array('required','integer'),
			'relation' => array('required','integer')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {
			$purchase = Purchase::create(array(
				'register_date' => Input::get('date'),
				'amount' => str_replace(',', '.', str_replace('.', '' , Input::get('hour'))),
				'relation_id' => Input::get('relation'),
				'note' => Input::get('note'),
				'kind_id' => Input::get('type'),
				'project_id' => Input::get('project')
			));

			return json_encode(['success' => 1, 'type' => PurchaseKind::find(Input::get('type'))->kind_name, 'amount' => number_format($purchase->amount, 2,",","."), 'id' => $purchase->id]);
		}
	}

	public function doDeletePurchase()
	{
		$rules = array(
			'id' => array('required','integer')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			Purchase::destroy(Input::get('id'));

			return json_encode(['success' => 1]);
		}
	}

}
