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
				'note' => Input::get('note')
			));

			switch (Input::get('type')) {
				case 1:
					$type = 'Aanneming';
					break;

				case 2:
					$type = 'Meerwerk';
					break;

				case 3:
					$type = 'Stelpost';
					break;
			}

			return json_encode(['success' => 1, 'type' => $type, 'activity' => Activity::find($timesheet->activity_id)->activity_name, 'hour' => number_format($timesheet->register_hour, 2,",","."), 'id' => $timesheet->id]);
		}
	}
}
