<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the Dynq project.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  Dynq
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
 */

namespace BynqIO\Dynq\Http\Controllers;

use Illuminate\Http\Request;

use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\Models\Chapter;
use BynqIO\Dynq\Models\Activity;
use BynqIO\Dynq\Models\Detail;
use BynqIO\Dynq\Models\Part;
use BynqIO\Dynq\Models\PartType;
use BynqIO\Dynq\Models\Purchase;
use BynqIO\Dynq\Models\PurchaseKind;
use BynqIO\Dynq\Models\Relation;
use BynqIO\Dynq\Models\Timesheet;
use BynqIO\Dynq\Models\TimesheetKind;
use BynqIO\Dynq\Models\EstimateLabor;
use BynqIO\Dynq\Models\Wholesale;
use BynqIO\Dynq\Models\MoreLabor;
use BynqIO\Dynq\Models\ProjectType;

class CostController extends Controller
{
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
            //'date' => array('required','regex:/^20[0-9][0-9]-[0-9]{2}-[0-9]{2}$/'),
            'date' => array('required'),
            'type' => array('required','integer'),
            'hour' => array('required','regex:/^[0-9]{1,3}([.,][0-9]{1,2})?$/'),
            'activity' => array('required','integer')
        ]);

        $activity = Activity::find($request->get('activity'));
        if (!$activity)
            return response()->json(['success' => 0]);
        $chapter = Chapter::find($activity->chapter_id);
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return response()->json(['success' => 0]);
        }

        $_activity = Activity::find($request->get('activity'));
        $_chapter = Chapter::find($_activity->chapter_id);
        $_project = Project::find($_chapter->project_id);

        $timesheet = Timesheet::create(array(
            'register_date' => date('Y-m-d', strtotime($request->get('date'))),
            'register_hour' => str_replace(',', '.', str_replace('.', '' , $request->get('hour'))),
            'activity_id' => $activity->id,
            'note' => $request->get('note'),
            'timesheet_kind_id' => 2,
        ));


        $type = 'Aanneming';
        if (TimesheetKind::find($type_id)->kind_name == 'meerwerk')
        {
            $type = 'Meerwerk';
            $labor = MoreLabor::create(array(
                "rate" => 0,
                "amount" => str_replace(',', '.', str_replace('.', '' , $request->get('hour'))),
                "activity_id" => $activity->id,
                "hour_id" => $timesheet->id
            ));
        }

        if (TimesheetKind::find($type_id)->kind_name == 'stelpost')
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

        return response()->json(['success' => 1, 'type' => $type, 'activity' => Activity::find($timesheet->activity_id)->activity_name, 'hour' => number_format($timesheet->register_hour, 2,",","."), 'date' => date('d-m-Y', strtotime($request->get('date'))), 'project' => $_project->project_name, 'id' => $timesheet->id]);
    }

    public function doDeleteTimesheet(Request $request)
    {
        $this->validate($request, [
            'id' => array('required','integer')
        ]);

        $timesheet = Timesheet::find($request->get('id'));
        if (!$timesheet)
            return response()->json(['success' => 0]);
        $activity = Activity::find($timesheet->activity_id);
        if (!$activity)
            return response()->json(['success' => 0]);
        $chapter = Chapter::find($activity->chapter_id);
        if (!$chapter || !Project::find($chapter->project_id)->isOwner()) {
            return response()->json(['success' => 0]);
        }

        $timesheet->delete();

        return response()->json(['success' => 1]);
    }

    public function doNewPurchase(Request $request)
    {
        $this->validate($request, [
            'date' => array('required','regex:/^20[0-9][0-9]-[0-9]{2}-[0-9]{2}$/'),
            'type' => array('required','integer'),
            'amount' => array('required','regex:/^([0-9]+.?)?[0-9]+[.,]?[0-9]*$/'),
            'project' => array('required','integer'),
            'relation' => array('required')
        ]);

        $project = Project::find($request->get('project'));
        if (!$project || !$project->isOwner()) {
            return response()->json(['success' => 0]);
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

        return response()->json(['success' => 1,'relation' => $relname, 'type' => ucfirst(PurchaseKind::find($request->get('type'))->kind_name), 'date' => date('d-m-Y', strtotime($request->get('date'))), 'amount' => '&euro; '.number_format($purchase->amount, 2,",","."), 'id' => $purchase->id]);
    }

    public function doDeletePurchase(Request $request)
    {
        $this->validate($request, [
            'id' => array('required','integer')
        ]);

        $purchase = Purchase::find($request->get('id'));
        if (!$purchase || !Project::find($purchase->project_id)->isOwner()) {
            return response()->json(['success' => 0]);
        }

        $purchase->delete();

        return response()->json(['success' => 1]);
    }

    public function getActivityByType(Request $request, $projectid, $typeid)
    {

        $project = Project::find($projectid);
        if (!$project || !$project->isOwner()) {
            return response()->json(['success' => 0]);
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

        return response()->json(['success' => 1]);
    }
}
