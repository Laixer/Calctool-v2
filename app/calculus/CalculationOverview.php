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

	public static function contrMaterialSuperTotal($project) {
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

	public static function subcontrMaterialSuperTotal($project) {
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

	public static function contrEquipmentSuperTotal($project) {
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

	public static function subcontrEquipmentSuperTotal($project) {
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

	public static function contrLaborSuperTotalAmount($project) {
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

	public static function subcontrLaborSuperTotalAmount($project) {
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

	public static function contrLaborSuperTotal($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
			{
				$total += CalculationOverview::laborActivity($activity);
			}
		}
		return $total;
	}

	public static function subcontrLaborSuperTotal($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
			{
				$total += CalculationOverview::laborActivity($activity);
			}
		}
		return $total;
	}

	public static function contrSuperTotal($project) {
		return CalculationOverview::contrLaborSuperTotal($project) + CalculationOverview::contrMaterialSuperTotal($project) + CalculationOverview::contrEquipmentSuperTotal($project);
	}

	public static function subcontrSuperTotal($project) {
		return CalculationOverview::subcontrLaborSuperTotal($project) + CalculationOverview::subcontrMaterialSuperTotal($project) + CalculationOverview::subcontrEquipmentSuperTotal($project);
	}

}
