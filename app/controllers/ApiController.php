<?php

class ApiController extends Controller {

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

	public function getApiRoot()
	{
		return json_encode(['success' => 1, 'description' => 'API server ready', 'version' => 1]);
	}

	public function getAlert()
	{
		return View::make('admin.alert');
	}

	public function getPHPInfo()
	{
		return View::make('admin.phpinfo');
	}

	public function doNewAlert()
	{
		$rules = array(
			'level' => array('required'),
			'message' => array('required'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {

			$messages = $validator->messages();
			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$alert = new SystemMessage;
			$alert->level = Input::get('level');
			$alert->content = Input::get('message');
			$alert->active = true;

			$alert->save();

			return json_encode(['success' => 1]);
		}

	}
}
