<?php

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */
	public function getAll()
	{
		return View::make('base.user');
	}

	public function getNew()
	{
		return View::make('base.new_user');
	}

}
