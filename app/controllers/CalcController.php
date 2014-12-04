<?php

class CalcController extends BaseController {

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

	public function getCalculation()
	{
		return View::make('calc.calculation');
	}


	public function getEstimate()
	{
		return View::make('calc.estimate');
	}

	public function getLess()
	{
		return View::make('calc.less');
	}

	public function getMore()
	{
		return View::make('calc.more');
	}

	public function doNewChapter()
	{
		$rules = array(
			'chapter' => 'required|max:50',
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$chapter = new Chapter;
			$chapter->chapter_name = Input::get('chapter');
			$chapter->priority = 0;
			$chapter->project_id = Route::Input('project_id');

			$chapter->save();

			return Redirect::back()->with('success', 1);
		}

	}

	public function doNewActivity()
	{
		$rules = array(
			'activity' => 'required|max:50',
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$part = Part::where('part_name','=','contracting')->first();
			$part_type = PartType::where('type_name','=','calculation')->first();

			$activity = new Activity;
			$activity->activity_name = Input::get('activity');
			$activity->priority = 0;
			$activity->chapter_id = Route::Input('chapter_id');
			$activity->part_id = $part->id;
			$activity->part_type_id = $part_type->id;

			$activity->save();

			return Redirect::back()->with('success', 1);

		}
	}
}
