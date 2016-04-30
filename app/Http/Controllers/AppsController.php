<?php

namespace Calctool\Http\Controllers;

use Illuminate\Http\Request;

use \Calctool\Models\Relation;
use \Calctool\Models\RelationKind;
use \Calctool\Models\Contact;
use \Calctool\Models\Province;

use \Auth;
use \Cookie;

class AppsController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */

	public function getAppsDashboard()
	{
		return view('base.apps');

	}

}
