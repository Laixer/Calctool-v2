<?php

namespace BynqIO\CalculatieTool\Calculus;

use BynqIO\CalculatieTool\Models\Chapter;
use BynqIO\CalculatieTool\Models\Activity;
use BynqIO\CalculatieTool\Models\Part;
use BynqIO\CalculatieTool\Models\PartType;
use BynqIO\CalculatieTool\Models\CalculationLabor;
use BynqIO\CalculatieTool\Models\CalculationMaterial;
use BynqIO\CalculatieTool\Models\CalculationEquipment;
use BynqIO\CalculatieTool\Models\EstimateLabor;
use BynqIO\CalculatieTool\Models\EstimateMaterial;
use BynqIO\CalculatieTool\Models\EstimateEquipment;

/*
 * Uittrekstaat
 */
class CalculationOverview {

/*--Calculation Overview - total per activitys--*/
/*labor activity total*/
	public static function laborActivity($rate, $activity) {
		$row = NULL;
		if (PartType::find($activity->part_type_id)->type_name=='estimate') {
			$row = EstimateLabor::where('activity_id', '=', $activity->id)->first();
		} else {
			$row = CalculationLabor::where('activity_id', '=', $activity->id)->first();
		}
		if (Part::find($activity->part_id)->part_name=='subcontracting') {
			$rate = $row['rate'];
		}
		return $rate * $row['amount'];
	}

	public static function laborTotal($activity) {

		if (PartType::find($activity->part_type_id)->type_name=='estimate') {
			return EstimateLabor::where('activity_id', '=', $activity->id)->first()['amount'];
		} else {
			return CalculationLabor::where('activity_id', '=', $activity->id)->first()['amount'];
		}
	}

/*Material activity total*/
	public static function materialActivityProfit($activity, $profit) {
		$total = 0;

		$row = NULL;
		if (PartType::find($activity->part_type_id)->type_name=='estimate') {
			$rows = EstimateMaterial::where('activity_id', '=', $activity->id)->get();
		} else {
			$rows = CalculationMaterial::where('activity_id', '=', $activity->id)->get();
		}
		foreach ($rows as $row)
		{
			$total += $row->rate * $row->amount;
		}

		return (1+($profit/100))*$total;
	}

/*Equipment activity total*/
	public static function equipmentActivityProfit($activity, $profit) {
		$total = 0;

		$row = NULL;
		if (PartType::find($activity->part_type_id)->type_name=='estimate') {
			$rows = EstimateEquipment::where('activity_id', '=', $activity->id)->get();
		} else {
			$rows = CalculationEquipment::where('activity_id', '=', $activity->id)->get();
		}
		foreach ($rows as $row)
		{
			$total += $row->rate * $row->amount;
		}

		return (1+($profit/100))*$total;
	}

/*Activity total*/
	public static function activityTotalProfit($rate, $activity, $profit_mat, $profit_equip) {
		$total = 0;

		$total += CalculationOverview::laborActivity($rate, $activity);
		$total += CalculationOverview::materialActivityProfit($activity, $profit_mat);
		$total += CalculationOverview::equipmentActivityProfit($activity, $profit_equip);

		return $total;
	}

/*Check if activity*/
	public static function estimateCheck($activity) {
		if (PartType::find($activity->part_type_id)->type_name=='estimate') {
			return 'fa fa-check';
		}
	}

/*--Calculation Overview - total contracting--*/
/*Material for Contracting & Subcontracting*/

	public static function contrMaterialTotal($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
			{
				$total += CalculationOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat);
			}
		}
		return $total;
	}

	public static function subcontrMaterialTotal($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
			{
				$total += CalculationOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat);
			}
		}
		return $total;
	}

/*Equipment for Contracting & Subcontracting*/

	public static function contrEquipmentTotal($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
			{
				$total += CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip);
			}
		}
		return $total;
	}

	public static function subcontrEquipmentTotal($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
			{
				$total += CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip);
			}
		}
		return $total;
	}

/*Labor amount & labor total for Contracting & Subcontracting*/

	public static function contrLaborTotalAmount($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
			{
				$total += CalculationOverview::laborTotal($activity);
			}
		}

		return $total;
	}

	public static function subcontrLaborTotalAmount($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
			{
				$total += CalculationOverview::laborTotal($activity);
			}
		}

		return $total;
	}

	public static function contrLaborTotal($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
			{
				$total += CalculationOverview::laborActivity($project->hour_rate, $activity);
			}
		}
		return $total;
	}

	public static function subcontrLaborTotal($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
			{
				$total += CalculationOverview::laborActivity($project->hour_rate, $activity);
			}
		}
		return $total;
	}

	public static function contrTotal($project) {
		return CalculationOverview::contrLaborTotal($project) + CalculationOverview::contrMaterialTotal($project) + CalculationOverview::contrEquipmentTotal($project);
	}

	public static function subcontrTotal($project) {
		return CalculationOverview::subcontrLaborTotal($project) + CalculationOverview::subcontrMaterialTotal($project) + CalculationOverview::subcontrEquipmentTotal($project);
	}


/*--Calculation Overview -  SuperTotals (projecttotals)--*/
/*Labor amount & labor total SuperTotal*/

	public static function laborSuperTotalAmount($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
			{
				$total += CalculationOverview::laborTotal($activity);
			}
		}

		return $total;
	}

	public static function laborSuperTotal($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
			{
				$total += CalculationOverview::laborActivity($project->hour_rate, $activity);
			}
		}
		return $total;
	}

/*Masterial SuperTotal*/
	public static function materialSuperTotal($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
			{
				if (Part::find($activity->part_id)->part_name=='contracting') {
					$profit = $project->profit_calc_contr_mat;
				} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
					$profit = $project->profit_calc_subcontr_mat;
				}
				$total += CalculationOverview::materialActivityProfit($activity, $profit);
			}
		}
		return $total;
	}

/*Equipment SuperTotal*/
	public static function equipmentSuperTotal($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
			{
				if (Part::find($activity->part_id)->part_name=='contracting') {
					$profit = $project->profit_calc_contr_equip;
				} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
					$profit = $project->profit_calc_subcontr_equip;
				}
				$total += CalculationOverview::equipmentActivityProfit($activity, $profit);
			}
		}
		return $total;
	}

/*Project SuperTotal*/
	public static function superTotal($project) {
		return CalculationOverview::laborSuperTotal($project) + CalculationOverview::materialSuperTotal($project) + CalculationOverview::equipmentSuperTotal($project);
	}


}


