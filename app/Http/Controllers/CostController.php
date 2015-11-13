<?php

namespace Calctool\Http\Controllers;

use Illuminate\Http\Request;

use \Calctool\Models\Project;
use \Calctool\Models\Chapter;
use \Calctool\Models\Activity;
use \Calctool\Models\Detail;
use \Calctool\Models\Part;
use \Calctool\Models\PartType;
use \Calctool\Models\Purchase;
use \Calctool\Models\PurchaseKind;
use \Calctool\Models\Relation;
use \Calctool\Models\Timesheet;
use \Calctool\Models\TimesheetKind;
use \Calctool\Models\EstimateLabor;

class CostController extends Controller {

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

	public function getTimesheet()
	{
		return view('cost.timesheet');
	}

	public function getPurchase()
	{
		return view('cost.purchase');
	}

	public function doNewTimesheet(Request $request)
	{
		$this->validate($request, [
			'date' => array('required','regex:/^20[0-9][0-9]-[0-9]{2}-[0-9]{2}$/'),
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

		return json_encode(['success' => 1, 'type' => $type, 'activity' => Activity::find($timesheet->activity_id)->activity_name, 'hour' => number_format($timesheet->register_hour, 2,",","."), 'date' => date('d-m-Y', strtotime($request->get('date'))), 'project' => $_project->project_name, 'id' => $timesheet->id]);
	}

	public function doDeleteTimesheet(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer')
		]);

		$timesheet = Timesheet::find($request->get('id'));
		if (!$timesheet)
			return json_encode(['success' => 0]);
		$activity = Activity::find($timesheet->activity_id);
		if (!$activity)
			return json_encode(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$timesheet->delete();

		return json_encode(['success' => 1]);
	}

	public function doNewPurchase(Request $request)
	{
		$this->validate($request, [
			'date' => array('required','regex:/^20[0-9][0-9]-[0-9]{2}-[0-9]{2}$/'),
			'type' => array('required','integer'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'project' => array('required','integer'),
			'relation' => array('required','integer')
		]);

		$project = Project::find($request->get('project'));
		if (!$project || !$project->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$purchase = Purchase::create(array(
			'register_date' => $request->get('date'),
			'amount' => str_replace(',', '.', str_replace('.', '' , $request->get('amount'))),
			'relation_id' => $request->get('relation'),
			'note' => $request->get('note'),
			'kind_id' => $request->get('type'),
			'project_id' => $project->id
		));

		return json_encode(['success' => 1,'relation' => Relation::find($request->get('relation'))->company_name, 'type' => ucfirst(PurchaseKind::find($request->get('type'))->kind_name), 'date' => date('d-m-Y', strtotime($request->get('date'))), 'amount' => '&euro; '.number_format($purchase->amount, 2,",","."), 'id' => $purchase->id]);
	}

	public function doDeletePurchase(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer')
		]);

		$purchase = Purchase::find($request->get('id'));
		if (!$purchase || !Project::find($purchase->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$purchase->delete();

		return json_encode(['success' => 1]);
	}

	public function getActivityByType(Request $request, $projectid, $typeid)
	{

		$project = Project::find($projectid);
		if (!$project || !$project->isOwner()) {
			return json_encode(['success' => 0]);
		}

		switch ($typeid) {
			case 1:
				$rs = [];
				foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
				foreach (Activity::select(['id','activity_name'])->whereNull('detail_id')->where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->get() as $activity) {
					$activity['chapter'] = $chapter->chapter_name;
					array_push($rs, $activity);
				}
				return $rs;
				break;
			case 2:
				$rs = [];
				foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
				foreach (Activity::select(['id','activity_name'])->whereNull('detail_id')->where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','estimate')->first()->id)->get() as $activity) {
					$activity['chapter'] = $chapter->chapter_name;
					array_push($rs, $activity);
				}
				return $rs;
				break;
			case 3:
				$rs = [];
				foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
				foreach (Activity::select(['id','activity_name'])->where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity) {
					$activity['chapter'] = $chapter->chapter_name;
					array_push($rs, $activity);
				}
				return $rs;
				break;
		}

		return json_encode(['success' => 1]);
	}
}
