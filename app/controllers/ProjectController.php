<?php

class ProjectController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */
	public function getNew()
	{
		return View::make('user.new_project');
	}

	public function getAll()
	{
		return View::make('user.project');
	}

}
