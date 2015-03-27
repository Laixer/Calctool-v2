<?php

/*
 * Uittrekstaat
 */
class OverviewCalc {

/*Calculation labor*/
	public static function calcLaborActivity($activity) {
		$row = CalculationLabor::where('activity_id', '=', $activity)->first();

		return $row['rate'] * $row['amount'];
	}

/*Calculation Material*/
	public static function calcMaterialActivityProfit($activity, $profit) {
		$total = 0;

		$rows = CalculationMaterial::where('activity_id', '=', $activity)->get();
		foreach ($rows as $row)
		{
			$total += $row->rate * $row->amount;
		}

		return (1+($profit/100))*$total;
	}

/*Calculation Equipment*/
	public static function calcEquipmentActivityProfit($activity, $profit) {
		$total = 0;

		$rows = CalculationEquipment::where('activity_id', '=', $activity)->get();
		foreach ($rows as $row)
		{
			$total += $row->rate * $row->amount;
		}

		return (1+($profit/100))*$total;
	}

/*Calculation Activity totaal*/
	public static function calcActivityTotalProfit($activity, $profit_mat, $profit_equip) {

		return OverviewCalc::calcLaborActivity($activity) + OverviewCalc::calcMaterialActivityProfit($activity, $profit_mat) + OverviewCalc::calcEquipmentActivityProfit($activity, $profit_equip);
	}

/*Calculation Activity totaal*/
	public static function calcLaborTotal($activity) {

		return CalculationLabor::where('activity_id', '=', $activity)->first()['amount'];
	}

	public static function calcEstimateCheck($activity) {
		if (PartType::find($activity->part_type_id)->type_name=='estimate') {
			return 'fa fa-check';
		}
	}

	public static function calcLaborSuperTotalAmount($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
			{
				$total += OverviewCalc::calcLaborTotal($activity->id);
			}
		}

		return $total;
	}

	public static function calcLaborSuperTotal($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
			{
				$total += OverviewCalc::calcLaborActivity($activity->id);
			}
		}
		return $total;
	}

	public static function calcMaterialSuperTotal($project) {
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
				$total += OverviewCalc::calcMaterialActivityProfit($activity->id, $profit);
			}
		}
		return $total;
	}

	public static function calcEquipmentSuperTotal($project) {
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
				$total += OverviewCalc::calcEquipmentActivityProfit($activity->id, $profit);
			}
		}
		return $total;
	}

	public static function calcSuperTotal($project) {
		return OverviewCalc::calcLaborSuperTotal($project) + OverviewCalc::calcMaterialSuperTotal($project) + OverviewCalc::calcEquipmentSuperTotal($project);
	}

}


