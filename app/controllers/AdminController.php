<?php

class AdminController extends BaseController {

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

	public function getDashboard()
	{
		return View::make('admin.dashboard');
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
			'title' => array('required'),
			'message' => array('required'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {

			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$alert = new SystemMessage;
			$alert->title = Input::get('title');
			$alert->content = Input::get('message');
			$alert->active = true;

			$alert->save();

			return Redirect::back()->with('success', 1);
		}

	}

	public function doDeleteAlert()
	{
		$rules = array(
			'id' => array('required'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$alert = SystemMessage::find(Input::get('id'));
			$alert->active = false;

			$alert->save();

			return json_encode(['success' => 1]);
		}

	}

}
