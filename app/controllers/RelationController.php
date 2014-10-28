<?php

class RelationController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */
	public function getNew()
	{
		return View::make('user.new_relation');
	}

	public function getAll()
	{
		return View::make('user.relation');
	}

}
