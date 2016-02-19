<?php

namespace Calctool\Http\Controllers;

use Auth;
use \Calctool\Models\Project;

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
		return response()->json(['success' => 1, 'description' => 'API server ready', 'version' => 1]);
	}

	public function getProjects()
	{
		$projects = Project::where('user_id','=', Auth::user()->id)->orderBy('created_at', 'desc')->get();
		return response()->json($projects);
	}

}
