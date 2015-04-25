<?php

/*
 * Uittrekstaat
 */
class CalculationOverview {

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

	public static function laborTotal($activity) {

		if (PartType::find($activity->part_type_id)->type_name=='estimate') {
			return EstimateLabor::where('activity_id', '=', $activity->id)->first()['amount'];
		} else {
			return CalculationLabor::where('activity_id', '=', $activity->id)->first()['amount'];
		}
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

/*Calculation activity*/
	public static function activityTotalProfit($activity, $profit_mat, $profit_equip) {
		$total = 0;

		$total += CalculationOverview::laborActivity($activity);
		$total += CalculationOverview::materialActivityProfit($activity, $profit_mat);
		$total += CalculationOverview::equipmentActivityProfit($activity, $profit_equip);

		return $total;
	}

	public static function estimateCheck($activity) {
		if (PartType::find($activity->part_type_id)->type_name=='estimate') {
			return 'fa fa-check';
		}
	}
/*Calculation Overview Super Total*/
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
				$total += CalculationOverview::laborActivity($activity);
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
				$total += CalculationOverview::materialActivityProfit($activity, $profit);
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
				$total += CalculationOverview::equipmentActivityProfit($activity, $profit);
			}
		}
		return $total;
	}

	public static function superTotal($project) {
		return CalculationOverview::laborSuperTotal($project) + CalculationOverview::materialSuperTotal($project) + CalculationOverview::equipmentSuperTotal($project);
	}





/*
Geprusts door Don, nog veranderen voor een goede variant"
public static function conMaterialSuperTotal($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;

			foreach (Part::find($activity->part_id)->type_name=='contracting') {
					$total = CalculationMaterial::where('activity_id','=',$activity->id)->get();
					}

			return $total ;
	}
*/

}














































