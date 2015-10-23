<?php

namespace Calctool\Calculus;

/*
 * Uittrekstaat
 */
class MoreOverview {

/*--More Overview - total per activitys--*/
/*labor activity total*/
	public static function laborActivity($activity) {
		$count = MoreLabor::where('activity_id','=', $activity->id)->whereNotNull('hour_id')->count('hour_id');
		if ($count) {
			$amount = MoreLabor::where('activity_id','=', $activity->id)->whereNotNull('hour_id')->sum('amount');
			$rate = MoreLabor::where('activity_id','=', $activity->id)->whereNotNull('hour_id')->first()['rate'];
		} else {
			$row = MoreLabor::where('activity_id', '=', $activity->id)->first();
			$amount = $row['amount'];
			$rate = $row['rate'];
		}

		return $rate * $amount;
	}

	public static function laborTotal($activity) {
		$count = MoreLabor::where('activity_id','=', $activity->id)->whereNotNull('hour_id')->count('hour_id');
		if ($count)
			return MoreLabor::where('activity_id','=', $activity->id)->whereNotNull('hour_id')->sum('amount');
		return MoreLabor::where('activity_id', '=', $activity->id)->first()['amount'];
	}

/*Material activity total*/
	public static function materialActivityProfit($activity, $profit) {
		$total = 0;

		$rows = MoreMaterial::where('activity_id', '=', $activity->id)->get();
		foreach ($rows as $row)
		{
			$total += $row->rate * $row->amount;
		}

		return (1+($profit/100))*$total;
	}

/*Equipment activity total*/
	public static function equipmentActivityProfit($activity, $profit) {
		$total = 0;

		$rows = MoreEquipment::where('activity_id', '=', $activity->id)->get();
		foreach ($rows as $row)
		{
			$total += $row->rate * $row->amount;
		}

		return (1+($profit/100))*$total;
	}

/*Activity total*/
	public static function activityTotalProfit($activity, $profit_mat, $profit_equip) {
		$total = 0;

		$total += MoreOverview::laborActivity($activity);
		$total += MoreOverview::materialActivityProfit($activity, $profit_mat);
		$total += MoreOverview::equipmentActivityProfit($activity, $profit_equip);

		return $total;
	}

/*--More Overview - total contracting--*/
/*Material for Contracting & Subcontracting*/

	public static function contrMaterialTotal($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
			{
				$total += MoreOverview::materialActivityProfit($activity, $project->profit_more_contr_mat);
			}
		}
		return $total;
	}

	public static function subcontrMaterialTotal($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
			{
				$total += MoreOverview::materialActivityProfit($activity, $project->profit_more_subcontr_mat);
			}
		}
		return $total;
	}

/*Equipment for Contracting & Subcontracting*/

	public static function contrEquipmentTotal($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
			{
				$total += MoreOverview::equipmentActivityProfit($activity, $project->profit_more_contr_equip);
			}
		}
		return $total;
	}

	public static function subcontrEquipmentTotal($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
			{
				$total += MoreOverview::equipmentActivityProfit($activity, $project->profit_more_subcontr_equip);
			}
		}
		return $total;
	}

/*Labor amount & labor total for Contracting & Subcontracting*/

	public static function contrLaborTotalAmount($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
			{
				$total += MoreOverview::laborTotal($activity);
			}
		}

		return $total;
	}

	public static function subcontrLaborTotalAmount($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
			{
				$total += MoreOverview::laborTotal($activity);
			}
		}

		return $total;
	}

	public static function contrLaborTotal($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
			{
				$total += MoreOverview::laborActivity($activity);
			}
		}
		return $total;
	}

	public static function subcontrLaborTotal($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
			{
				$total += MoreOverview::laborActivity($activity);
			}
		}
		return $total;
	}

	public static function contrTotal($project) {
		return MoreOverview::contrLaborTotal($project) + MoreOverview::contrMaterialTotal($project) + MoreOverview::contrEquipmentTotal($project);
	}

	public static function subcontrTotal($project) {
		return MoreOverview::subcontrLaborTotal($project) + MoreOverview::subcontrMaterialTotal($project) + MoreOverview::subcontrEquipmentTotal($project);
	}


/*--More Overview -  SuperTotals (projecttotals)--*/
/*Labor amount & labor total SuperTotal*/

	public static function laborSuperTotalAmount($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
			{
				$total += MoreOverview::laborTotal($activity);
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
				$total += MoreOverview::laborActivity($activity);
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
					$profit = $project->profit_more_contr_mat;
				} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
					$profit = $project->profit_more_subcontr_mat;
				}
				$total += MoreOverview::materialActivityProfit($activity, $profit);
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
					$profit = $project->profit_more_contr_equip;
				} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
					$profit = $project->profit_more_subcontr_equip;
				}
				$total += MoreOverview::equipmentActivityProfit($activity, $profit);
			}
		}
		return $total;
	}

/*Project SuperTotal*/
	public static function superTotal($project) {
		return MoreOverview::laborSuperTotal($project) + MoreOverview::materialSuperTotal($project) + MoreOverview::equipmentSuperTotal($project);
	}
}
