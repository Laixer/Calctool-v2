<?php

/*
 * Uittrekstaat
 */
class LessOverview {

/*--Less Overview - total per activitys--*/
/*labor activity total*/

/*NOG DOEN>>*/

/*Less labor*/

	public static function laborActivity($activity) {
		$row = NULL;
		if (PartType::find($activity->part_type_id)->type_name=='calculation') {
			$row = CalculationLabor::where('activity_id', '=', $activity->id)->where('isless','=','true')->first();
		}

		return ($row['rate'] * $row['less_amount']) - ($row['rate'] * $row['amount']);
	}

	public static function laborTotal($activity) {
		if (PartType::find($activity->part_type_id)->type_name=='calculation') {
			$row = CalculationLabor::where('activity_id', '=', $activity->id)->where('isless','=','true')->first();
			return $row['less_amount'] - $row['amount'];
		}
	}

	public static function LessMaterialTotal($activity) {
		$total = 0;

		$rows = CalculationMaterial::where('activity_id', '=', $activity)->where('isless','=','true')->get();
		foreach ($rows as $row)
		{
			$total = LessRegister::lessLaborTotal($row->less_rate, $row->less_amount);
			$less_total = LessRegister::lessLaborTotal($row->rate, $row->amount);
			$total += $total - $less_total;
		}

		return $total;
	}

/*<<NOG DOEN*/

/*Less Material*/
/* Ik krijg geen "less" voor de functienaam, ja ik heb het ook doorgevoerd op de blade*/
	public static function materialActivityProfit($activity, $profit) {
		$supertotal = 0;

		$row = NULL;
		$rows = CalculationMaterial::where('activity_id', '=', $activity->id)->where('isless','=','true')->get();

		foreach ($rows as $row)
		{
			$total = (LessRegister::lessLaborTotal($row->less_rate, $row->less_amount) * (1+($profit/100)));
			$less_total = (LessRegister::lessLaborTotal($row->rate, $row->amount) * (1+($profit/100)));
			$supertotal += $total - $less_total;
		}
		return $supertotal;
	}

/*Less Equipment*/
/* Ik krijg geen "less" voor de functienaam, ja ik heb het ook doorgevoerd op de blade*/
	public static function equipmentActivityProfit($activity, $profit) {
		$supertotal = 0;

		$row = NULL;
		$rows = CalculationEquipment::where('activity_id', '=', $activity->id)->where('isless','=','true')->get();

		foreach ($rows as $row)
		{
			$total = (LessRegister::lessLaborTotal($row->less_rate, $row->less_amount) * (1+($profit/100)));
			$less_total = (LessRegister::lessLaborTotal($row->rate, $row->amount) * (1+($profit/100)));
			$supertotal += $total - $less_total;
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

/*--Less Overview - total contracting--*/
/*Material for Contracting & Subcontracting*/

	public static function contrMaterialTotal($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
			{
				$total += LessOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat);
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
				$total += LessOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat);
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
				$total += LessOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip);
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
				$total += LessOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip);
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
				$total += LessOverview::laborTotal($activity);
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
				$total += LessOverview::laborTotal($activity);
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
				$total += LessOverview::laborActivity($activity);
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
				$total += LessOverview::laborActivity($activity);
			}
		}
		return $total;
	}

	public static function contrTotal($project) {
		return LessOverview::contrLaborTotal($project) + LessOverview::contrMaterialTotal($project) + LessOverview::contrEquipmentTotal($project);
	}

	public static function subcontrTotal($project) {
		return LessOverview::subcontrLaborTotal($project) + LessOverview::subcontrMaterialTotal($project) + LessOverview::subcontrEquipmentTotal($project);
	}


/*--Less Overview -  SuperTotals (projecttotals)--*/
/*Labor amount & labor total SuperTotal*/

	public static function laborSuperTotalAmount($project) {
		$total = 0;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
			{
				$total += LessOverview::laborTotal($activity);
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
				$total += LessOverview::laborActivity($activity);
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
				$total += LessOverview::materialActivityProfit($activity, $profit);
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
				$total += LessOverview::equipmentActivityProfit($activity, $profit);
			}
		}
		return $total;
	}

/*Project SuperTotal*/
	public static function superTotal($project) {
		return LessOverview::laborSuperTotal($project) + LessOverview::materialSuperTotal($project) + LessOverview::equipmentSuperTotal($project);
	}
/*<<NOG DOEN*/
}


