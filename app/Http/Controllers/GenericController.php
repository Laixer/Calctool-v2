<?php

namespace Calctool\Http\Controllers;

use Illuminate\Http\Request;

class GenericController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */

	public function getAbout(Request $request)
	{
		return view('generic.about');
	}

	public function getFaq(Request $request)
	{
		return view('generic.faq');
	}

	public function getTerms(Request $request)
	{
		return view('generic.terms');
	}

	public function getPrivacy(Request $request)
	{
		return view('generic.privacy');
	}

	public function getSupport(Request $request)
	{
		return view('generic.support');
	}
}
