<?php

namespace BynqIO\Dynq\Calculus;

use BynqIO\Dynq\Models\EstimateLabor;
use BynqIO\Dynq\Models\PartType;
use BynqIO\Dynq\Models\EstimateMaterial;
use BynqIO\Dynq\Models\EstimateEquipment;
use BynqIO\Dynq\Models\Chapter;
use BynqIO\Dynq\Models\Activity;
use BynqIO\Dynq\Models\Part;



/*
 * Uittrekstaat
 */
class EstimateOverview {

/*--Estimate Overview - total per activitys--*/
/*labor activity total*/
	public static function laborActivity($activity) {
		$total = 0;

		//$count = EstimateLabor::where('activity_id','=', $activity->id)->where('isset','=','true')->where('original','=','false')->count('id');
		if (PartType::find($activity->part_type_id)->type_name=='estimate') {
			$rows = EstimateLabor::where('activity_id', '=', $activity->id)->get();
			foreach ($rows as $row)
			{
				//if ($count) {
				if ($activity->use_timesheet) {
					if ($row->isset && !$row->original) {
						if (!$row->set_rate)
							$total += $row->rate * $row->set_amount;
						else
							$total += $row->set_rate * $row->set_amount;
					}
				} else {
					if ($row->isset && !$row->hour_id)
						if (!$row->set_rate)
							$total += $row->rate * $row->set_amount;
						else
							$total += $row->set_rate * $row->set_amount;
					else
						$total += $row->rate * $row->amount;
				}
			}

			return $total;
		}
	}

	public static function laborTotal($activity) {
		$total = 0;

		//$count = EstimateLabor::where('activity_id','=', $activity->id)->where('isset','=','true')->where('original','=','false')->count('id');
		if (PartType::find($activity->part_type_id)->type_name=='estimate') {
			$rows = EstimateLabor::where('activity_id', '=', $activity->id)->get();
			foreach ($rows as $row)
			{
				//if ($count) {
				if ($activity->use_timesheet) {
					if ($row->isset && !$row->original) {
						$total += $row->set_amount;
					}
				} else {
					if ($row->isset && !$row->hour_id)
						$total += $row->set_amount;
					else
						$total += $row->amount;
				}
			}

			return $total;
		}
	}

/*Material activity total*/
	public static function materialActivityProfit($activity, $profit) {
		$total = 0;

		$row = NULL;
		if (PartType::find($activity->part_type_id)->type_name=='estimate') {
			$rows = EstimateMaterial::where('activity_id', '=', $activity->id)->get();

			foreach ($rows as $row)
			{
				if ($row['isset'])
					$total += $row->set_rate * $row->set_amount;
				else
					$total += $row->rate * $row->amount;
			}

			return (1+($profit/100))*$total;
		}
	}

/*Equipment activity total*/
	public static function equipmentActivityProfit($activity, $profit) {
		$total = 0;

		$row = NULL;
		if (PartType::find($activity->part_type_id)->type_name=='estimate') {
			$rows = EstimateEquipment::where('activity_id', '=', $activity->id)->get();

			foreach ($rows as $row)
			{
				if ($row['isset'])
					$total += $row->set_rate * $row->set_amount;
				else
					$total += $row->rate * $row->amount;
			}

			return (1+($profit/100))*$total;
		}
	}

/*Activity total*/
	public static function activityTotalProfit($activity, $profit_mat, $profit_equip) {
		$total = 0;

		$total += EstimateOverview::laborActivity($activity);
		$total += EstimateOverview::materialActivityProfit($activity, $profit_mat);
		$total += EstimateOverview::equipmentActivityProfit($activity, $profit_equip);

		return $total;
	}

/*--Estimate Overview - total contracting--*/
/*Material for Contracting & Subcontracting*/

	public static function contrMaterialTotal($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
			{
				$total += EstimateOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat);
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
				$total += EstimateOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat);
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
				$total += EstimateOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip);
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
				$total += EstimateOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip);
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
				$total += EstimateOverview::laborTotal($activity);
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
				$total += EstimateOverview::laborTotal($activity);
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
				$total += EstimateOverview::laborActivity($activity);
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
				$total += EstimateOverview::laborActivity($activity);
			}
		}
		return $total;
	}

	public static function contrTotal($project) {
		return EstimateOverview::contrLaborTotal($project) + EstimateOverview::contrMaterialTotal($project) + EstimateOverview::contrEquipmentTotal($project);
	}

	public static function subcontrTotal($project) {
		return EstimateOverview::subcontrLaborTotal($project) + EstimateOverview::subcontrMaterialTotal($project) + EstimateOverview::subcontrEquipmentTotal($project);
	}


/*--Estimate Overview -  SuperTotals (projecttotals)--*/
/*Labor amount & labor total SuperTotal*/

	public static function laborSuperTotalAmount($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
			{
				$total += EstimateOverview::laborTotal($activity);
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
				$total += EstimateOverview::laborActivity($activity);
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
				$total += EstimateOverview::materialActivityProfit($activity, $profit);
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
				$total += EstimateOverview::equipmentActivityProfit($activity, $profit);
			}
		}
		return $total;
	}

/*Project SuperTotal*/
	public static function superTotal($project) {
		return EstimateOverview::laborSuperTotal($project) + EstimateOverview::materialSuperTotal($project) + EstimateOverview::equipmentSuperTotal($project);
	}

}
