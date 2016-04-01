<?php

namespace Calctool\Http\Controllers;

use Auth;
use \Calctool\Models\Project;
use \Calctool\Models\Chapter;
use \Calctool\Models\Activity;
use \Calctool\Models\Timesheet;
use \Calctool\Models\TimesheetKind;
use \Calctool\Models\Purchase;
use \Calctool\Models\PurchaseKind;
use \Calctool\Models\Wholesale;
use \Calctool\Models\Relation;
use \Calctool\Models\RelationKind;
use \Calctool\Models\Contact;
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

		foreach ($projects as $project) {
			$relation = Relation::find($project->client_id);
			$project['relation'] = RelationKind::find($relation->kind_id)->kind_name == 'zakelijk' ? ucwords($relation->company_name) : (Contact::where('relation_id','=',$relation->id)->first()['firstname'].' '.Contact::where('relation_id','=',$relation->id)->first()['lastname']);
			$project['type'] = $project->type->type_name;
		}

		return response()->json($projects);
	}

	public function getTimesheet()
	{
		$projects = Project::where('user_id','=',Auth::id())->select('id')->get();
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

	public function getPurchase()
	{
		$projects = Project::where('user_id','=',Auth::id())->select('id')->get();
		$purchases = Purchase::whereIn('project_id', $projects->toArray())->get();

		foreach ($purchases as $purchase) {
			$purchase['relation'] = $purchase->relation_id ? Relation::find($purchase->relation_id)->company_name : Wholesale::find($purchase->wholesale_id)->company_name;
			$purchase['project_name'] = Project::find($purchase->project_id)->project_name;
			$purchase['amount'] = number_format($purchase['amount'], 2, ",",".");
			$purchase['purchase_kind'] = ucfirst(PurchaseKind::find($purchase['kind_id'])->kind_name);
			$purchase['register_date'] = date('d-m-Y', strtotime($purchase['register_date']));
		}

		return response()->json($purchases->toArray());
	}

	public function doPurchaseDelete(Request $request)
	{
		$this->validate($request, [
			'id' => array('required'),
		]);

		Purchase::destroy($request->input('id'));
	}

	public function doPurchaseNew(Request $request)
	{
		$this->validate($request, [
			'date' => array('required'),
			'type' => array('required','integer'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'project' => array('required','integer'),
			'relation' => array('required')
		]);

		$project = Project::find($request->get('project'));
		if (!$project || !$project->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$relation_id = null;
		$wholesale_id = null;
		$arr = explode('-', $request->get('relation'));
		if ($arr[0] == 'rel')
			$relation_id = $arr[1];
		else if ($arr[0] == 'whl')
			$wholesale_id = $arr[1];

		$purchase = new Purchase;
		$purchase->register_date = $request->get('date');
		$purchase->amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));
		if ($relation_id)
			$purchase->relation_id = $relation_id;
		else if ($wholesale_id)
			$purchase->wholesale_id = $wholesale_id;
		$purchase->note = $request->get('note');
		$purchase->kind_id = $request->get('type');
		$purchase->project_id = $project->id;

		$purchase->save();

		if ($relation_id)
			$relname = Relation::find($relation_id)->company_name;
		else if ($wholesale_id)
			$relname = Wholesale::find($wholesale_id)->company_name;

		return response()->json(['success' => 1, 'note' => $purchase->note, 'relation' => $relname, 'type' => ucfirst(PurchaseKind::find($request->get('type'))->kind_name), 'date' => date('d-m-Y', strtotime($request->get('date'))), 'amount' => number_format($purchase->amount, 2,",","."), 'project' => $project->project_name, 'id' => $purchase->id]);
	}

}
