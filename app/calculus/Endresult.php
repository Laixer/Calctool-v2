<?php

/*
 * Eindresultaat
 */
class Endresult {

	public static function calcLaborActivityTax1($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$part_type_id = PartType::where('type_name','=','calculation')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('part_type_id','=',$part_type_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$total += CalculationLabor::where('activity_id','=',$activity->id)->sum('amount');
			}
		}

		return $total;
	}

	public static function calcLaborActivityTax2($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$part_type_id = PartType::where('type_name','=','calculation')->first()->id;
		$tax_id = Tax::where('tax_rate','=','6')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('part_type_id','=',$part_type_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$total += CalculationLabor::where('activity_id','=',$activity->id)->sum('amount');
			}
		}

		return $total;
	}

	public static function calcLaborActivityTax3($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$part_type_id = PartType::where('type_name','=','calculation')->first()->id;
		$tax_id = Tax::where('tax_rate','=','0')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('part_type_id','=',$part_type_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$total += CalculationLabor::where('activity_id','=',$activity->id)->sum('amount');
			}
		}

		return $total;
	}

	public static function calcLaborActivityTax1Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$part_type_id = PartType::where('type_name','=','calculation')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('part_type_id','=',$part_type_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total;
	}

	public static function calcLaborActivityTax2Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$part_type_id = PartType::where('type_name','=','calculation')->first()->id;
		$tax_id = Tax::where('tax_rate','=','6')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('part_type_id','=',$part_type_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total;
	}

	public static function calcLaborActivityTax3Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$part_type_id = PartType::where('type_name','=','calculation')->first()->id;
		$tax_id = Tax::where('tax_rate','=','0')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('part_type_id','=',$part_type_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total;
	}

	public static function calcLaborActivityTax1AmountTax($project) {
		return (Endresult::calcLaborActivityTax1Amount($project)/100)*21;
	}

	public static function calcLaborActivityTax2AmountTax($project) {
		return (Endresult::calcLaborActivityTax2Amount($project)/100)*6;
	}

	public static function calcMaterialActivityTax1Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$part_type_id = PartType::where('type_name','=','calculation')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('part_type_id','=',$part_type_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total;
	}

	public static function calcMaterialActivityTax2Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$part_type_id = PartType::where('type_name','=','calculation')->first()->id;
		$tax_id = Tax::where('tax_rate','=','6')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('part_type_id','=',$part_type_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total;
	}

	public static function calcMaterialActivityTax3Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$part_type_id = PartType::where('type_name','=','calculation')->first()->id;
		$tax_id = Tax::where('tax_rate','=','0')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('part_type_id','=',$part_type_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total;
	}

	public static function calcMaterialActivityTax1AmountTax($project) {
		return (Endresult::calcMaterialActivityTax1Amount($project)/100)*21;
	}

	public static function calcMaterialActivityTax2AmountTax($project) {
		return (Endresult::calcMaterialActivityTax2Amount($project)/100)*6;
	}

	public static function calcEquipmentActivityTax1Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$part_type_id = PartType::where('type_name','=','calculation')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('part_type_id','=',$part_type_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total;
	}

	public static function calcEquipmentActivityTax2Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$part_type_id = PartType::where('type_name','=','calculation')->first()->id;
		$tax_id = Tax::where('tax_rate','=','6')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('part_type_id','=',$part_type_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total;
	}

	public static function calcEquipmentActivityTax3Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$part_type_id = PartType::where('type_name','=','calculation')->first()->id;
		$tax_id = Tax::where('tax_rate','=','0')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('part_type_id','=',$part_type_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total;
	}

	public static function calcEquipmentActivityTax1AmountTax($project) {
		return (Endresult::calcEquipmentActivityTax1Amount($project)/100)*21;
	}

	public static function calcEquipmentActivityTax2AmountTax($project) {
		return (Endresult::calcEquipmentActivityTax2Amount($project)/100)*6;
	}

}
