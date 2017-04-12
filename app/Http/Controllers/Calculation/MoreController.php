<?php

namespace CalculatieTool\Http\Controllers\Calculation;

use Illuminate\Http\Request;

use \CalculatieTool\Models\Project;
use \CalculatieTool\Models\Chapter;
use \CalculatieTool\Models\Activity;
use \CalculatieTool\Models\FavoriteActivity;
use \CalculatieTool\Models\Detail;
use \CalculatieTool\Models\PartType;
use \CalculatieTool\Models\Part;
use \CalculatieTool\Models\ProjectType;
use \CalculatieTool\Models\Tax;
use \CalculatieTool\Models\MoreEquipment;
use \CalculatieTool\Models\MoreLabor;
use \CalculatieTool\Models\MoreMaterial;
use \CalculatieTool\Models\FavoriteLabor;
use \CalculatieTool\Models\FavoriteMaterial;
use \CalculatieTool\Models\FavoriteEquipment;
use CalculatieTool\Http\Controllers\Controller;

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

    public function getMoreWithFavorite(Request $request, $projectid, $chapterid, $favid)
    {
        $chapter = Chapter::find($chapterid);
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return back();
        }

        $favact = FavoriteActivity::find($favid);
        if (!$favact || !$favact->isOwner()) {
            return back();
        }

        $part = Part::where('part_name','=','contracting')->first();
        $part_type = PartType::where('type_name','=','calculation')->first();
        $detail = Detail::where('detail_name','=','more')->first();
        $project = Project::find($chapter->project_id);

        $last_activity = Activity::where('chapter_id', $chapter->id)->where('part_type_id',$part_type->id)->where('detail_id',$detail->id)->orderBy('priority','desc')->first();

        $activity = new Activity;
        $activity->activity_name = $favact->activity_name;
        $activity->priority = $last_activity ? $last_activity->priority + 1 : 0;
        $activity->note = $favact->note;
        $activity->chapter_id = $chapter->id;
        $activity->part_id = $part->id;
        $activity->part_type_id = $part_type->id;
        $activity->detail_id = $detail->id;

        if ($project->tax_reverse) {
            $tax_id = Tax::where('tax_rate','0')->first()['id'];
            $activity->tax_labor_id = $tax_id;
            $activity->tax_material_id = $tax_id;
            $activity->tax_equipment_id = $tax_id;
        } else {
            $activity->tax_labor_id = $favact->tax_labor_id;
            $activity->tax_material_id = $favact->tax_material_id;
            $activity->tax_equipment_id = $favact->tax_equipment_id;
        }
        $activity->save();

        $this->updateMoreStatus($projectid);

        foreach (FavoriteLabor::where('activity_id', $favact->id)->get() as $fav_calc_labor) {
            MoreLabor::create(array(
                "rate" => 0,
                "amount" => $fav_calc_labor->amount,
                "activity_id" => $activity->id,
            ));
        }

        foreach (FavoriteMaterial::where('activity_id', $favact->id)->get() as $fav_calc_material) {
            MoreMaterial::create(array(
                "material_name" => $fav_calc_material->material_name,
                "unit" => $fav_calc_material->unit,
                "rate" => $fav_calc_material->rate,
                "amount" => $fav_calc_material->amount,
                "activity_id" => $activity->id,
            ));
        }

        if ($project->use_equipment) {
            foreach (FavoriteEquipment::where('activity_id', $favact->id)->get() as $fav_calc_equipment) {
                MoreEquipment::create(array(
                    "equipment_name" => $fav_calc_equipment->equipment_name,
                    "unit" => $fav_calc_equipment->unit,
                    "rate" => $fav_calc_equipment->rate,
                    "amount" => $fav_calc_equipment->amount,
                    "activity_id" => $activity->id,
                ));
            }
        }

        return back();
    }

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

        $last_chaper = Chapter::where('project_id', $project->id)->orderBy('priority','desc')->first();

        $chapter = new Chapter;
        $chapter->chapter_name = $request->get('chapter');
        $chapter->priority = $last_chaper ? $last_chaper->priority + 1 : 0;
        $chapter->project_id = $project->id;
        $chapter->more = true;

        $chapter->save();

        return back()->with('success', 'Nieuw onderdeel aangemaakt');
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
        if ($project->tax_reverse)
            $tax = Tax::where('tax_rate','=',0)->first();
        else
            $tax = Tax::where('tax_rate','=',21)->first();

        $last_activity = Activity::where('chapter_id', $chapter->id)->where('part_type_id',$part_type->id)->where('detail_id',$detail->id)->orderBy('priority','desc')->first();

        $activity = new Activity;
        $activity->activity_name = $request->get('activity');
        $activity->priority = $last_activity ? $last_activity->priority + 1 : 0;
        $activity->chapter_id = $chapter->id;
        $activity->part_id = $part->id;
        $activity->part_type_id = $part_type->id;
        $activity->detail_id = $detail->id;
        $activity->tax_labor_id = $tax->id;
        $activity->tax_material_id = $tax->id;
        $activity->tax_equipment_id = $tax->id;

        $activity->save();

        $this->updateMoreStatus($request->get('project'));

        return back()->with('success', 'Nieuwe werkzaamheid aangemaakt');
    }

    public function doMoveActivity(Request $request)
    {
        $this->validate($request, [
            'activity' => array('required','integer','min:0'),
            'direction' => array('required')
        ]);

        $activity = Activity::find($request->input('activity'));
        if (!$activity)
            return response()->json(['success' => 0]);
        $chapter = Chapter::find($activity->chapter_id);
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return response()->json(['success' => 0]);
        }

        if ($request->input('direction') == 'up') {
            $switch_activity = Activity::where('chapter_id', $chapter->id)->where('priority','<',$activity->priority)->where('detail_id', $activity->detail_id)->orderBy('priority','desc')->first();
            if ($switch_activity) {
                $old_priority = $activity->priority;
                $activity->priority = $switch_activity->priority;
                $switch_activity->priority = $old_priority;

                $switch_activity->save();
            }
        } else if ($request->input('direction') == 'down') {
            $switch_activity = Activity::where('chapter_id', $chapter->id)->where('priority','>',$activity->priority)->where('detail_id', $activity->detail_id)->orderBy('priority')->first();
            if ($switch_activity) {
                $old_priority = $activity->priority;
                $activity->priority = $switch_activity->priority;
                $switch_activity->priority = $old_priority;

                $switch_activity->save();
            }
        }

        $activity->save();

        return response()->json(['success' => 1]);
    }

    public function doDeleteChapter(Request $request)
    {
        $this->validate($request, [
            'chapter' => array('required','integer','min:0')
        ]);

        $chapter = Chapter::find($request->input('chapter'));
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return response()->json(['success' => 0]);
        }

        if (!$chapter->more)
            return response()->json(['success' => 0]);

        $chapter->delete();

        return response()->json(['success' => 1]);
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
            return response()->json(['success' => 0]);
        $chapter = Chapter::find($activity->chapter_id);
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return response()->json(['success' => 0]);
        }

        $material = MoreMaterial::create(array(
            "material_name" => $request->get('name'),
            "unit" => $request->get('unit'),
            "rate" => str_replace(',', '.', str_replace('.', '' , $request->get('rate'))),
            "amount" => str_replace(',', '.', str_replace('.', '' , $request->get('amount'))),
            "activity_id" => $activity->id,
        ));

        $this->updateMoreStatus($request->get('project'));

        return response()->json(['success' => 1, 'id' => $material->id]);
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
            return response()->json(['success' => 0]);
        $chapter = Chapter::find($activity->chapter_id);
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return response()->json(['success' => 0]);
        }

        $equipment = MoreEquipment::create(array(
            "equipment_name" => $request->get('name'),
            "unit" => $request->get('unit'),
            "rate" => str_replace(',', '.', str_replace('.', '' , $request->get('rate'))),
            "amount" => str_replace(',', '.', str_replace('.', '' , $request->get('amount'))),
            "activity_id" => $activity->id,
        ));

        $this->updateMoreStatus($request->get('project'));

        return response()->json(['success' => 1, 'id' => $equipment->id]);
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
            return response()->json(['success' => 0]);
        $chapter = Chapter::find($activity->chapter_id);
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return response()->json(['success' => 0]);
        }

        $rate = $request->get('rate');
        if (empty($rate)) {
            $_activity = Activity::find($request->get('activity'));
            $_chapter = Chapter::find($_activity->chapter_id);
            $_project = Project::find($_chapter->project_id);
            $rate = $_project->hour_rate_more;
            if (!$rate)
                $rate = 0;
        } else {
            $rate = str_replace(',', '.', str_replace('.', '' , $rate));
        }
        $labor = MoreLabor::create(array(
            "rate" => $rate,
            "amount" => str_replace(',', '.', str_replace('.', '' , $request->get('amount'))),
            "activity_id" => $activity->id,
        ));

        $this->updateMoreStatus($request->get('project'));

        return response()->json(['success' => 1, 'id' => $labor->id]);
    }

    public function doDeleteMaterial(Request $request)
    {
        $this->validate($request, [
            'id' => array('required','integer','min:0'),
            'project' => array('required','integer'),
        ]);

        $rec = MoreMaterial::find($request->get('id'));
        if (!$rec)
            return response()->json(['success' => 0]);
        $activity = Activity::find($rec->activity_id);
        if (!$activity)
            return response()->json(['success' => 0]);
        $chapter = Chapter::find($activity->chapter_id);
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return response()->json(['success' => 0]);
        }

        $rec->delete();

        $this->updateMoreStatus($request->get('project'));

        return response()->json(['success' => 1]);
    }

    public function doDeleteEquipment(Request $request)
    {
        $this->validate($request, [
            'id' => array('required','integer','min:0'),
            'project' => array('required','integer'),
        ]);

        $rec = MoreEquipment::find($request->get('id'));
        if (!$rec)
            return response()->json(['success' => 0]);
        $activity = Activity::find($rec->activity_id);
        if (!$activity)
            return response()->json(['success' => 0]);
        $chapter = Chapter::find($activity->chapter_id);
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return response()->json(['success' => 0]);
        }

        $rec->delete();

        $this->updateMoreStatus($request->get('project'));

        return response()->json(['success' => 1]);
    }

    public function doDeleteLabor(Request $request)
    {
        $this->validate($request, [
            'id' => array('required','integer','min:0'),
            'project' => array('required','integer'),
        ]);

        $rec = MoreLabor::find($request->get('id'));
        if (!$rec)
            return response()->json(['success' => 0]);
        $activity = Activity::find($rec->activity_id);
        if (!$activity)
            return response()->json(['success' => 0]);
        $chapter = Chapter::find($activity->chapter_id);
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return response()->json(['success' => 0]);
        }

        $rec->delete();

        $this->updateMoreStatus($request->get('project'));

        return response()->json(['success' => 1]);
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
            return response()->json(['success' => 0]);
        $activity = Activity::find($material->activity_id);
        if (!$activity)
            return response()->json(['success' => 0]);
        $chapter = Chapter::find($activity->chapter_id);
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return response()->json(['success' => 0]);
        }

        $material->material_name = $request->get('name');
        $material->unit = $request->get('unit');
        $material->rate = str_replace(',', '.', str_replace('.', '' , $request->get('rate')));
        $material->amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));

        $material->save();

        $this->updateMoreStatus($request->get('project'));

        return response()->json(['success' => 1]);
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
            return response()->json(['success' => 0]);
        $activity = Activity::find($equipment->activity_id);
        if (!$activity)
            return response()->json(['success' => 0]);
        $chapter = Chapter::find($activity->chapter_id);
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return response()->json(['success' => 0]);
        }

        $equipment->equipment_name = $request->get('name');
        $equipment->unit = $request->get('unit');
        $equipment->rate = str_replace(',', '.', str_replace('.', '' , $request->get('rate')));
        $equipment->amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));

        $equipment->save();

        $this->updateMoreStatus($request->get('project'));

        return response()->json(['success' => 1]);
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
            return response()->json(['success' => 0]);
        $activity = Activity::find($labor->activity_id);
        if (!$activity)
            return response()->json(['success' => 0]);
        $chapter = Chapter::find($activity->chapter_id);
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return response()->json(['success' => 0]);
        }

        $rate = $request->get('rate');
        if (empty($rate)) {
            $_labor = MoreLabor::find($request->get('id'));
            $_activity = Activity::find($_labor->activity_id);
            $_chapter = Chapter::find($_activity->chapter_id);
            $_project = Project::find($_chapter->project_id);
            $rate = $_project->hour_rate_more;
        } else {
            $rate = str_replace(',', '.', str_replace('.', '' , $rate));
        }

        $labor->rate = $rate;
        $labor->amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));

        $labor->save();

        $this->updateMoreStatus($request->get('project'));

        return response()->json(['success' => 1]);
    }
}
