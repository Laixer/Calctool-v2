<?php

/*
 * Uittrekstaat
 */
class LessOverview {

/*--Calculation Overview - total per activitys--*/
/*labor activity total*/

/*NOG DOEN>>*/

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


	public static function LessMaterialTotal($activity) {
		$total = 0;

		$rows = CalculationMaterial::where('activity_id', '=', $activity)->get();
		foreach ($rows as $row)
		{
			if ($row->isless)
				$total += LessRegister::lessLaborTotal($row->less_rate, $row->less_amount);
			else
				$total += LessRegister::lessLaborTotal($row->rate, $row->amount);
		}

		return $total;
	}

/*<<NOG DOEN*/

/*Less Material*/
/* Ik krijg geen "less" voor de functienaam, ja ik heb het ook doorgevoerd op de blade*/
	public static function materialActivityProfit($activity, $profit) {
		$supertotal = 0;

		$row = NULL;
		$rows = CalculationMaterial::where('activity_id', '=', $activity->id)->get();

		foreach ($rows as $row)
		{
			if ($row->isless) {
				$total = (LessRegister::lessLaborTotal($row->less_rate, $row->less_amount) * (1+($profit/100)));
				$less_total = (LessRegister::lessLaborTotal($row->rate, $row->amount) * (1+($profit/100)));
				$supertotal += $total - $less_total;
			}

		}
		return $supertotal;
	}

/*Less Equipment*/
/* Ik krijg geen "less" voor de functienaam, ja ik heb het ook doorgevoerd op de blade*/
	public static function equipmentActivityProfit($activity, $profit) {
		$supertotal = 0;

		$row = NULL;
		$rows = CalculationEquipment::where('activity_id', '=', $activity->id)->get();

		foreach ($rows as $row)
		{
			if ($row->isless) {
				$total = (LessRegister::lessLaborTotal($row->less_rate, $row->less_amount) * (1+($profit/100)));
				$less_total = (LessRegister::lessLaborTotal($row->rate, $row->amount) * (1+($profit/100)));
				$supertotal += $total - $less_total;
			}

		}
		return $supertotal;
	}

/*Less Activity total*/
	public static function activityTotalProfit($activity, $profit_mat, $profit_equip) {
		$total = 0;

		$total += LessOverview::laborActivity($activity);
		$total += LessOverview::materialActivityProfit($activity, $profit_mat);
		$total += LessOverview::equipmentActivityProfit($activity, $profit_equip);

		return $total;
	}

/*NOG DOEN>>*/
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
				$total += CalculationOverview::laborActivity($activity);
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
				$total += CalculationOverview::laborActivity($activity);
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
				$total += CalculationOverview::laborActivity($activity);
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
/*<<NOG DOEN*/
}


