<?php

namespace Calctool\Http\Controllers;

use Illuminate\Http\Request;

use \Calctool\Models\Project;
use \Calctool\Models\Chapter;
use \Calctool\Models\Activity;
use \Calctool\Models\Detail;
use \Calctool\Models\PartType;
use \Calctool\Models\Part;
use \Calctool\Models\ProjectType;
use \Calctool\Models\Tax;
use \Calctool\Models\MoreEquipment;
use \Calctool\Models\MoreLabor;
use \Calctool\Models\MoreMaterial;

use \Auth;

class MoreController extends Controller {

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

	public function updateMoreStatus($id)
	{
		$proj = Project::find($id);
		if (!$proj->start_more)
			$proj->start_more = date('Y-m-d');
		$proj->update_more = date('Y-m-d');
		$proj->save();
	}

	public function doNewChapter(Request $request, $project_id)
	{
		$this->validate($request, [
			'chapter' => array('required','max:50'),
		]);

		$project = Project::find($project_id);
		if (!$project || !$project->isOwner()) {
			return back()->withInput($request->all());
		}

		$chapter = new Chapter;
		$chapter->chapter_name = $request->get('chapter');
		$chapter->priority = 0;
		$chapter->project_id = $project->id;
		$chapter->more = true;

		$chapter->save();

		return back()->with('success', 1);
	}

	public function doNewActivity(Request $request, $chapter_id)
	{
		$this->validate($request, [
			'activity' => array('required','max:50'),
			'project' => array('required','integer'),
		]);

		$chapter = Chapter::find($chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return back()->withInput($request->all());
		}

		$part = Part::where('part_name','=','contracting')->first();
		$part_type = PartType::where('type_name','=','calculation')->first();
		$detail = Detail::where('detail_name','=','more')->first();
		$project = Project::find($chapter->project_id);
		if (ProjectType::find($project->type_id)->type_name == 'BTW verlegd')
			$tax = Tax::where('tax_rate','=',0)->first();
		else
			$tax = Tax::where('tax_rate','=',21)->first();

		$activity = new Activity;
		$activity->activity_name = $request->get('activity');
		$activity->priority = 0;
		$activity->chapter_id = $chapter->id;
		$activity->part_id = $part->id;
		$activity->part_type_id = $part_type->id;
		$activity->detail_id = $detail->id;
		$activity->tax_labor_id = $tax->id;
		$activity->tax_material_id = $tax->id;
		$activity->tax_equipment_id = $tax->id;

		$activity->save();

		$this->updateMoreStatus($request->get('project'));

		return back()->with('success', 1);
	}

	public function doDeleteChapter(Request $request)
	{
		$this->validate($request, [
			'chapter' => array('required','integer','min:0')
		]);

		$chapter = Chapter::find($request->input('chapter'));
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		if (!$chapter->more)
			return json_encode(['success' => 0]);

		$chapter->delete();

		return json_encode(['success' => 1]);
	}

	public function doNewMaterial(Request $request)
	{
		$this->validate($request, [
			'name' => array('required','max:50'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0'),
			'project' => array('required','integer'),
		]);

		$activity = Activity::find($request->get('activity'));
		if (!$activity)
			return json_encode(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$material = MoreMaterial::create(array(
			"material_name" => $request->get('name'),
			"unit" => $request->get('unit'),
			"rate" => str_replace(',', '.', str_replace('.', '' , $request->get('rate'))),
			"amount" => str_replace(',', '.', str_replace('.', '' , $request->get('amount'))),
			"activity_id" => $activity->id,
		));

		$this->updateMoreStatus($request->get('project'));

		return json_encode(['success' => 1, 'id' => $material->id]);
	}

	public function doNewEquipment(Request $request)
	{
		$this->validate($request, [
			'name' => array('required','max:50'),
			'unit' => array('required','max:10'),
			'rate' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0'),
			'project' => array('required','integer'),
		]);

		$activity = Activity::find($request->get('activity'));
		if (!$activity)
			return json_encode(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$equipment = MoreEquipment::create(array(
			"equipment_name" => $request->get('name'),
			"unit" => $request->get('unit'),
			"rate" => str_replace(',', '.', str_replace('.', '' , $request->get('rate'))),
			"amount" => str_replace(',', '.', str_replace('.', '' , $request->get('amount'))),
			"activity_id" => $activity->id,
		));

		$this->updateMoreStatus($request->get('project'));

		return json_encode(['success' => 1, 'id' => $equipment->id]);
	}

	public function doNewLabor(Request $request)
	{
		$this->validate($request, [
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'activity' => array('required','integer','min:0'),
			'project' => array('required','integer'),
		]);

		$activity = Activity::find($request->get('activity'));
		if (!$activity)
			return json_encode(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$rate = $request->get('rate');
		if (empty($rate)) {
			$_activity = Activity::find($request->get('activity'));
			$_chapter = Chapter::find($_activity->chapter_id);
			$_project = Project::find($_chapter->project_id);
			$rate = $_project->hour_rate_more;
		} else {
			$rate = str_replace(',', '.', str_replace('.', '' , $rate));
		}
		$labor = MoreLabor::create(array(
			"rate" => $rate,
			"amount" => str_replace(',', '.', str_replace('.', '' , $request->get('amount'))),
			"activity_id" => $activity->id,
		));

		$this->updateMoreStatus($request->get('project'));

		return json_encode(['success' => 1, 'id' => $labor->id]);
	}

	public function doDeleteMaterial(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
			'project' => array('required','integer'),
		]);

		$rec = MoreMaterial::find($request->get('id'));
		if (!$rec)
			return json_encode(['success' => 0]);
		$activity = Activity::find($rec->activity_id);
		if (!$activity)
			return json_encode(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$rec->delete();

		$this->updateMoreStatus($request->get('project'));

		return json_encode(['success' => 1]);
	}

	public function doDeleteEquipment(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
			'project' => array('required','integer'),
		]);

		$rec = MoreEquipment::find($request->get('id'));
		if (!$rec)
			return json_encode(['success' => 0]);
		$activity = Activity::find($rec->activity_id);
		if (!$activity)
			return json_encode(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$rec->delete();

		$this->updateMoreStatus($request->get('project'));

		return json_encode(['success' => 1]);
	}

	public function doDeleteLabor(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer','min:0'),
			'project' => array('required','integer'),
		]);

		$rec = MoreLabor::find($request->get('id'));
		if (!$rec)
			return json_encode(['success' => 0]);
		$activity = Activity::find($rec->activity_id);
		if (!$activity)
			return json_encode(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$rec->delete();

		$this->updateMoreStatus($request->get('project'));

		return json_encode(['success' => 1]);
	}

	public function doUpdateMaterial(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'name' => array('max:50'),
			'unit' => array('max:10'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'project' => array('required','integer'),
		]);

		$material = MoreMaterial::find($request->get('id'));
		if (!$material)
			return json_encode(['success' => 0]);
		$activity = Activity::find($material->activity_id);
		if (!$activity)
			return json_encode(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$material->material_name = $request->get('name');
		$material->unit = $request->get('unit');
		$material->rate = str_replace(',', '.', str_replace('.', '' , $request->get('rate')));
		$material->amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));

		$material->save();

		$this->updateMoreStatus($request->get('project'));

		return json_encode(['success' => 1]);
	}

	public function doUpdateEquipment(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'name' => array('max:50'),
			'unit' => array('max:10'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'project' => array('required','integer'),
		]);

		$equipment = MoreEquipment::find($request->get('id'));
		if (!$equipment)
			return json_encode(['success' => 0]);
		$activity = Activity::find($equipment->activity_id);
		if (!$activity)
			return json_encode(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$equipment->equipment_name = $request->get('name');
		$equipment->unit = $request->get('unit');
		$equipment->rate = str_replace(',', '.', str_replace('.', '' , $request->get('rate')));
		$equipment->amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));

		$equipment->save();

		$this->updateMoreStatus($request->get('project'));

		return json_encode(['success' => 1]);
	}

	public function doUpdateLabor(Request $request)
	{
		$this->validate($request, [
			'id' => array('integer','min:0'),
			'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
			'project' => array('required','integer'),
		]);

		$labor = MoreLabor::find($request->get('id'));
		if (!$labor)
			return json_encode(['success' => 0]);
		$activity = Activity::find($labor->activity_id);
		if (!$activity)
			return json_encode(['success' => 0]);
		$chapter = Chapter::find($activity->chapter_id);
		if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
			return json_encode(['success' => 0]);
		}

		$rate = $request->get('rate');
		if (empty($rate)) {
			$_labor = MoreLabor::find($request->get('id'));
			$_activity = Activity::find($_labor->activity_id);
			$_chapter = Chapter::find($_activity->chapter_id);
			$_project = Project::find($_chapter->project_id);
			$rate = $_project->hour_rate;
		} else {
			$rate = str_replace(',', '.', str_replace('.', '' , $rate));
		}

		$labor->rate = $rate;
		$labor->amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));

		$labor->save();

		$this->updateMoreStatus($request->get('project'));

		return json_encode(['success' => 1]);
	}
}
