<?php

namespace Calctool\Http\Controllers;

use Auth;
use \Calctool\Models\Project;
use \Calctool\Models\Chapter;
use \Calctool\Models\Activity;
use \Calctool\Models\Timesheet;
use \Calctool\Models\TimesheetKind;
use Illuminate\Http\Request;

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

	public function getTimesheet()
	{
		$projects = Project::where('user_id','=',Auth::user()->id)->select('id')->get();
		$chapters = Chapter::whereIn('project_id', $projects->toArray())->select('id')->get();
		$activities = Activity::whereIn('chapter_id', $chapters->toArray())->select('id')->get();
		$timesheets = Timesheet::whereIn('activity_id', $activities->toArray())->get();


		foreach ($timesheets as $sheet) {
			$sheet['activity_name'] = Activity::find($sheet->activity_id)->activity_name;
			$sheet['project_name'] = Project::find(Chapter::find(Activity::find($sheet->activity_id)->chapter_id)->project_id)->project_name;
			$sheet['register_hour'] = number_format($sheet['register_hour'], 2, ",",".");
			$sheet['timesheet_kind'] = ucfirst(TimesheetKind::find($sheet['timesheet_kind_id'])->kind_name);
			$sheet['register_date'] = date('d-m-Y', strtotime($sheet['register_date']));
		}

		return response()->json($timesheets->toArray());
	}

	public function doTimesheetDelete(Request $request)
	{
		$this->validate($request, [
			'id' => array('required'),
		]);

		Timesheet::destroy($request->input('id'));
	}

	public function doTimesheetNew(Request $request)
	{
		$this->validate($request, [
			'date' => array('required'),
			'type' => array('required','integer'),
			'hour' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer')
		]);

		$activity = Activity::find($request->get('activity'));
		if (!$activity)
			return json_encode(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$timesheet = Timesheet::create(array(
			'register_date' => $request->get('date'),
			'register_hour' => str_replace(',', '.', str_replace('.', '' , $request->get('hour'))),
			'activity_id' => $activity->id,
			'note' => $request->get('note'),
			'timesheet_kind_id' => $request->get('type')
		));

		$_activity = Activity::find($request->get('activity'));
		$_chapter = Chapter::find($_activity->chapter_id);
		$_project = Project::find($_chapter->project_id);

		$type = 'Aanneming';
		if (TimesheetKind::find($request->get('type'))->kind_name == 'meerwerk')
		{
			$type = 'Meerwerk';
			$labor = MoreLabor::create(array(
				"rate" => $_project->hour_rate_more,
				"amount" => str_replace(',', '.', str_replace('.', '' , $request->get('hour'))),
				"activity_id" => $activity->id,
				"hour_id" => $timesheet->id
			));
		}

		if (TimesheetKind::find($request->get('type'))->kind_name == 'stelpost')
		{
			$type = 'Stelpost';
			$labor = EstimateLabor::create(array(
				"set_rate" => $_project->hour_rate,
				"set_amount" => str_replace(',', '.', str_replace('.', '' , $request->get('hour'))),
				"activity_id" => $activity->id,
				"original" => false,
				"isset" => true,
				"hour_id" => $timesheet->id
			));
		}

		return response()->json(['success' => 1, 'note' => $timesheet->note, 'type' => $type, 'activity' => Activity::find($timesheet->activity_id)->activity_name, 'hour' => number_format($timesheet->register_hour, 2,",","."), 'date' => date('d-m-Y', strtotime($request->get('date'))), 'project' => $_project->project_name, 'id' => $timesheet->id]);
	}

}
