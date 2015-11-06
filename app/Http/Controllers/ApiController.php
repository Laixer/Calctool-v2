<?php

namespace Calctool\Http\Controllers;

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
		return view('admin.alert');
	}

	public function getPHPInfo()
	{
		return view('admin.phpinfo');
	}

	public function doNewAlert(Request $request)
	{
		$this->validate($request, [
			'level' => array('required'),
			'message' => array('required'),
		]);

		$alert = new SysMessage;
		$alert->level = Input::get('level');
		$alert->content = Input::get('message');
		$alert->active = true;

		$alert->save();

		return json_encode(['success' => 1]);
	}
}
