<?php

namespace BynqIO\CalculatieTool\Http\Controllers\Calculation;

use Illuminate\Http\Request;

use \BynqIO\CalculatieTool\Models\Part;
use \BynqIO\CalculatieTool\Models\Project;
use \BynqIO\CalculatieTool\Models\Chapter;
use \BynqIO\CalculatieTool\Models\Activity;
use \BynqIO\CalculatieTool\Models\CalculationEquipment;
use \BynqIO\CalculatieTool\Models\CalculationLabor;
use \BynqIO\CalculatieTool\Models\CalculationMaterial;
use \BynqIO\CalculatieTool\Calculus\LessRegister;
use BynqIO\CalculatieTool\Http\Controllers\Controller;

class LessController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    */

    public function updateLessStatus($id)
    {
        $proj = Project::find($id);
        if (!$proj || !$proj->isOwner())
            return;
        if (!$proj->start_less)
            $proj->start_less = date('Y-m-d');
        $proj->update_less = date('Y-m-d');
        $proj->save();
    }

    public function doUpdateMaterial(Request $request)
    {
        $this->validate($request, [
            'id' => array('integer','min:0'),
            'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
            'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
            'project' => array('required','integer'),
        ]);

        $material = CalculationMaterial::find($request->get('id'));
        if (!$material)
            return response()->json(['success' => 0]);
        $activity = Activity::find($material->activity_id);
        if (!$activity)
            return response()->json(['success' => 0]);
        $chapter = Chapter::find($activity->chapter_id);
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return response()->json(['success' => 0]);
        }

        $rate = str_replace(',', '.', str_replace('.', '' , $request->get('rate')));
        $amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));

        if ($rate > $material->rate)
            return response()->json(['success' => 0, 'message' => 'rate too large', 'rate' => $material->rate, 'amount' => $material->amount]);

        $material->less_rate = $rate;
        if ($amount > $material->amount)
            return response()->json(['success' => 0, 'message' => 'amount too large', 'rate' => $material->rate, 'amount' => $material->amount]);

        $material->less_amount = $amount;
        $material->isless = True;

        $material->save();

        $this->updateLessStatus($request->get('project'));

        $project = Project::find($chapter->project_id);
        if (Part::find($activity->part_id)->part_name=='contracting') {
            $profit = $project->profit_calc_contr_mat;
        } else if (Part::find($activity->part_id)->part_name=='subcontracting') {
            $profit = $project->profit_calc_subcontr_mat;
        }

        if ($material->isless) {
            $total = ($material->rate * $material->amount) * ((100+$profit)/100);
            $less_total = ($material->less_rate * $material->less_amount) * ((100+$profit)/100);
            if($less_total-$total <0)
                $total_less = "<font color=red>&euro; ".number_format($less_total-$total, 2, ",",".")."</font>";
            else
                $total_less = '&euro; '.number_format($less_total-$total, 2, ",",".");
        } else {
            $total_less = '&euro; 0,00';
        }

        return response()->json(['success' => 1, 'less_rate' => number_format($material->less_rate, 2,",","."), 'less_amount' => number_format($material->less_amount, 2,",","."), 'less_total' => $total_less]);
    }

    public function doUpdateEquipment(Request $request)
    {
        $this->validate($request, [
            'id' => array('integer','min:0'),
            'rate' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
            'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
            'project' => array('required','integer'),
        ]);

        $equipment = CalculationEquipment::find($request->get('id'));
        if (!$equipment)
            return response()->json(['success' => 0]);
        $activity = Activity::find($equipment->activity_id);
        if (!$activity)
            return response()->json(['success' => 0]);
        $chapter = Chapter::find($activity->chapter_id);
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return response()->json(['success' => 0]);
        }

        $rate = str_replace(',', '.', str_replace('.', '' , $request->get('rate')));
        $amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));

        if ($rate > $equipment->rate)
            return response()->json(['success' => 0, 'message' => 'rate too large', 'rate' => $equipment->rate, 'amount' => $equipment->amount]);

        $equipment->less_rate = $rate;
        if ($amount > $equipment->amount)
            return response()->json(['success' => 0, 'message' => 'amount too large', 'rate' => $equipment->rate, 'amount' => $equipment->amount]);

        $equipment->less_amount = $amount;
        $equipment->isless = True;

        $equipment->save();

        $this->updateLessStatus($request->get('project'));

        $project = Project::find($chapter->project_id);
        if (Part::find($activity->part_id)->part_name=='contracting') {
            $profit = $project->profit_calc_contr_equip;
        } else if (Part::find($activity->part_id)->part_name=='subcontracting') {
            $profit = $project->profit_calc_subcontr_equip;
        }

        if ($equipment->isless) {
            $total = ($equipment->rate * $equipment->amount) * ((100+$profit)/100);
            $less_total = ($equipment->less_rate * $equipment->less_amount) * ((100+$profit)/100);
            if($less_total-$total <0)
                $total_less = "<font color=red>&euro; ".number_format($less_total-$total, 2, ",",".")."</font>";
            else
                $total_less = '&euro; '.number_format($less_total-$total, 2, ",",".");
        } else {
            $total_less = '&euro; 0,00';
        }

        return response()->json(['success' => 1, 'less_rate' => number_format($equipment->less_rate, 2,",","."), 'less_amount' => number_format($equipment->less_amount, 2,",","."), 'less_total' => $total_less]);
    }

    public function doUpdateLabor(Request $request)
    {
        $this->validate($request, [
            'id' => array('integer','min:0'),
            'amount' => array('regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
            'project' => array('required','integer'),
        ]);

        $labor = CalculationLabor::find($request->get('id'));
        if (!$labor)
            return response()->json(['success' => 0]);
        $activity = Activity::find($labor->activity_id);
        if (!$activity)
            return response()->json(['success' => 0]);
        $chapter = Chapter::find($activity->chapter_id);
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return response()->json(['success' => 0]);
        }

        $amount = str_replace(',', '.', str_replace('.', '' , $request->get('amount')));
        if ($amount > $labor->amount)
            return response()->json(['success' => 0, 'message' => 'amount too large', 'amount' => $labor->amount]);

        $labor->less_amount = $amount;
        $labor->isless = True;

        $labor->save();

        $this->updateLessStatus($request->get('project'));

        $total_less = LessRegister::lessLaborDeltaTotal($labor, $activity, Project::find($request->get('project')));
        if($total_less <0) {
            $total_less = "<font color=red>&euro; ".number_format($total_less, 2, ",",".")."</font>";
        } else {
            $total_less = '&euro; '.number_format($total_less, 2, ",",".");
        }

        return response()->json(['success' => 1, 'less_amount' => number_format($labor->less_amount, 2,",","."), 'less_total' => $total_less]);
    }

    public function doResetMaterial(Request $request)
    {
        $this->validate($request, [
            'id' => array('integer','min:0'),
            'project' => array('required','integer'),
        ]);

        $material = CalculationMaterial::find($request->get('id'));
        if (!$material)
            return response()->json(['success' => 0]);
        $activity = Activity::find($material->activity_id);
        if (!$activity)
            return response()->json(['success' => 0]);
        $chapter = Chapter::find($activity->chapter_id);
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return response()->json(['success' => 0]);
        }

        $material->less_rate = NULL;
        $material->less_amount = NULL;
        $material->isless = False;

        $material->save();

        $this->updateLessStatus($request->get('project'));

        return response()->json(['success' => 1, 'rate' => number_format($material->rate, 2,",","."), 'amount' => number_format($material->amount, 2,",",".")]);
    }

    public function doResetEquipment(Request $request)
    {
        $this->validate($request, [
            'id' => array('integer','min:0'),
            'project' => array('required','integer'),
        ]);

        $equipment = CalculationEquipment::find($request->get('id'));
        if (!$equipment)
            return response()->json(['success' => 0]);
        $activity = Activity::find($equipment->activity_id);
        if (!$activity)
            return response()->json(['success' => 0]);
        $chapter = Chapter::find($activity->chapter_id);
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return response()->json(['success' => 0]);
        }

        $equipment->less_rate = NULL;
        $equipment->less_amount = NULL;
        $equipment->isless = False;

        $equipment->save();

        $this->updateLessStatus($request->get('project'));

        return response()->json(['success' => 1, 'rate' => number_format($equipment->rate, 2,",","."), 'amount' => number_format($equipment->amount, 2,",",".")]);
    }

    public function doResetLabor(Request $request)
    {
        $this->validate($request, [
            'id' => array('integer','min:0'),
            'project' => array('required','integer'),
        ]);

        $labor = CalculationLabor::find($request->get('id'));
        if (!$labor)
            return response()->json(['success' => 0]);
        $activity = Activity::find($labor->activity_id);
        if (!$activity)
            return response()->json(['success' => 0]);
        $chapter = Chapter::find($activity->chapter_id);
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return response()->json(['success' => 0]);
        }

        $labor->less_amount = NULL;
        $labor->isless = False;

        $labor->save();

        $this->updateLessStatus($request->get('project'));

        return response()->json(['success' => 1, 'amount' => number_format($labor->amount, 2,",",".")]);

    }

}
