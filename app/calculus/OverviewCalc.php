<?php

/*
 * Uittrekstaat
 */
class OverviewCalc {

/*Calculation labor*/
	public static function laborActivity($activity) {
		$row = NULL;
		if (PartType::find($activity->part_type_id)->type_name=='estimate') {
			$row = EstimateLabor::where('activity_id', '=', $activity->id)->first();
		} else {
			$row = CalculationLabor::where('activity_id', '=', $activity->id)->first();
		}

		return $row['rate'] * $row['amount'];
	}

/*Calculation Material*/
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

/*Calculation Equipment*/
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

/*Calculation Activity totaal*/
	public static function activityTotalProfit($activity, $profit_mat, $profit_equip) {

		return OverviewCalc::laborActivity($activity) + OverviewCalc::materialActivityProfit($activity, $profit_mat) + OverviewCalc::equipmentActivityProfit($activity, $profit_equip);
	}

/*Calculation Activity totaal*/
	public static function laborTotal($activity) {

		if (PartType::find($activity->part_type_id)->type_name=='estimate') {
			return EstimateLabor::where('activity_id', '=', $activity->id)->first()['amount'];
		} else {
			return CalculationLabor::where('activity_id', '=', $activity->id)->first()['amount'];
		}
	}

	public static function estimateCheck($activity) {
		if (PartType::find($activity->part_type_id)->type_name=='estimate') {
			return 'fa fa-check';
		}
	}

	public static function laborSuperTotalAmount($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
			{
				$total += OverviewCalc::laborTotal($activity);
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
				$total += OverviewCalc::laborActivity($activity);
			}
		}
		return $total;
	}

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
				$total += OverviewCalc::materialActivityProfit($activity, $profit);
			}
		}
		return $total;
	}

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
				$total += OverviewCalc::equipmentActivityProfit($activity, $profit);
			}
		}
		return $total;
	}

	public static function superTotal($project) {
		return OverviewCalc::laborSuperTotal($project) + OverviewCalc::materialSuperTotal($project) + OverviewCalc::equipmentSuperTotal($project);
	}

}


