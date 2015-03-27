<?php

/*
 * Eindresultaat
 */
class Endresult {

	public static function conCalcLaborActivityTax1($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$total += CalculationLabor::where('activity_id','=',$activity->id)->sum('amount');
			}
		}

		return $total;
	}

	public static function conCalcLaborActivityTax2($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','6')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$total += CalculationLabor::where('activity_id','=',$activity->id)->sum('amount');
			}
		}

		return $total;
	}

	public static function conCalcLaborActivityTax3($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','0')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$total += CalculationLabor::where('activity_id','=',$activity->id)->sum('amount');
			}
		}

		return $total;
	}

	public static function conCalcLaborActivityTax1Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
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

	public static function conCalcLaborActivityTax2Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','6')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
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

	public static function conCalcLaborActivityTax3Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','0')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
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

	public static function conCalcLaborActivityTax1AmountTax($project) {
		return (Endresult::conCalcLaborActivityTax1Amount($project)/100)*21;
	}

	public static function conCalcLaborActivityTax2AmountTax($project) {
		return (Endresult::conCalcLaborActivityTax2Amount($project)/100)*6;
	}

	public static function conCalcMaterialActivityTax1Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_calc_contr_mat)/100);
	}

	public static function conCalcMaterialActivityTax2Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','6')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_calc_contr_mat)/100);
	}

	public static function conCalcMaterialActivityTax3Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','0')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_calc_contr_mat)/100);
	}

	public static function conCalcMaterialActivityTax1AmountTax($project) {
		return (Endresult::conCalcMaterialActivityTax1Amount($project)/100)*21;
	}

	public static function conCalcMaterialActivityTax2AmountTax($project) {
		return (Endresult::conCalcMaterialActivityTax2Amount($project)/100)*6;
	}

	public static function conCalcEquipmentActivityTax1Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_calc_contr_equip)/100);
	}

	public static function conCalcEquipmentActivityTax2Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','6')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_calc_contr_equip)/100);
	}

	public static function conCalcEquipmentActivityTax3Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','0')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_calc_contr_equip)/100);
	}

	public static function conCalcEquipmentActivityTax1AmountTax($project) {
		return (Endresult::conCalcEquipmentActivityTax1Amount($project)/100)*21;
	}

	public static function conCalcEquipmentActivityTax2AmountTax($project) {
		return (Endresult::conCalcEquipmentActivityTax2Amount($project)/100)*6;
	}

	public static function subconCalcLaborActivityTax1($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$total += CalculationLabor::where('activity_id','=',$activity->id)->sum('amount');
			}
		}

		return $total;
	}

	public static function subconCalcLaborActivityTax2($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','6')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$total += CalculationLabor::where('activity_id','=',$activity->id)->sum('amount');
			}
		}

		return $total;
	}

	public static function subconCalcLaborActivityTax3($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','0')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$total += CalculationLabor::where('activity_id','=',$activity->id)->sum('amount');
			}
		}

		return $total;
	}

	public static function subconCalcLaborActivityTax1Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
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

	public static function subconCalcLaborActivityTax2Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','6')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
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

	public static function subconCalcLaborActivityTax3Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','0')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
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

	public static function subconCalcLaborActivityTax1AmountTax($project) {
		return (Endresult::subconCalcLaborActivityTax1Amount($project)/100)*21;
	}

	public static function subconCalcLaborActivityTax2AmountTax($project) {
		return (Endresult::subconCalcLaborActivityTax2Amount($project)/100)*6;
	}

	public static function subconCalcMaterialActivityTax1Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;



		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)


		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_calc_subcontr_mat)/100);
	}

	public static function subconCalcMaterialActivityTax2Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','6')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_calc_subcontr_mat)/100);
	}

	public static function subconCalcMaterialActivityTax3Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','0')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_calc_subcontr_mat)/100);
	}

	public static function subconCalcMaterialActivityTax1AmountTax($project) {
		return (Endresult::subconCalcMaterialActivityTax1Amount($project)/100)*21;
	}

	public static function subconCalcMaterialActivityTax2AmountTax($project) {
		return (Endresult::subconCalcMaterialActivityTax2Amount($project)/100)*6;
	}

	public static function subconCalcEquipmentActivityTax1Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_calc_subcontr_equip)/100);
	}

	public static function subconCalcEquipmentActivityTax2Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','6')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_calc_subcontr_equip)/100);
	}

	public static function subconCalcEquipmentActivityTax3Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','0')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_calc_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_calc_subcontr_equip)/100);
	}

	public static function subconCalcEquipmentActivityTax1AmountTax($project) {
		return (Endresult::subconCalcEquipmentActivityTax1Amount($project)/100)*21;
	}

	public static function subconCalcEquipmentActivityTax2AmountTax($project) {
		return (Endresult::subconCalcEquipmentActivityTax2Amount($project)/100)*6;
	}

	public static function totalContracting($project) {
		$total = 0;

		$total += Endresult::conCalcLaborActivityTax1Amount($project);
		$total += Endresult::conCalcLaborActivityTax2Amount($project);
		$total += Endresult::conCalcLaborActivityTax3Amount($project);

		$total += Endresult::conCalcMaterialActivityTax1Amount($project);
		$total += Endresult::conCalcMaterialActivityTax2Amount($project);
		$total += Endresult::conCalcMaterialActivityTax3Amount($project);

		$total += Endresult::conCalcEquipmentActivityTax1Amount($project);
		$total += Endresult::conCalcEquipmentActivityTax2Amount($project);
		$total += Endresult::conCalcEquipmentActivityTax3Amount($project);

		return $total;
	}

	public static function totalContractingTax($project) {
		$total = 0;

		$total += Endresult::conCalcLaborActivityTax1AmountTax($project);
		$total += Endresult::conCalcLaborActivityTax2AmountTax($project);

		$total += Endresult::conCalcMaterialActivityTax1AmountTax($project);
		$total += Endresult::conCalcMaterialActivityTax2AmountTax($project);

		$total += Endresult::conCalcEquipmentActivityTax1AmountTax($project);
		$total += Endresult::conCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalSubcontracting($project) {
		$total = 0;

		$total += Endresult::subconCalcLaborActivityTax1Amount($project);
		$total += Endresult::subconCalcLaborActivityTax2Amount($project);
		$total += Endresult::subconCalcLaborActivityTax3Amount($project);

		$total += Endresult::subconCalcMaterialActivityTax1Amount($project);
		$total += Endresult::subconCalcMaterialActivityTax2Amount($project);
		$total += Endresult::subconCalcMaterialActivityTax3Amount($project);

		$total += Endresult::subconCalcEquipmentActivityTax1Amount($project);
		$total += Endresult::subconCalcEquipmentActivityTax2Amount($project);
		$total += Endresult::subconCalcEquipmentActivityTax3Amount($project);

		return $total;
	}

	public static function totalSubcontractingTax($project) {
		$total = 0;

		$total += Endresult::subconCalcLaborActivityTax1AmountTax($project);
		$total += Endresult::subconCalcLaborActivityTax2AmountTax($project);

		$total += Endresult::subconCalcMaterialActivityTax1AmountTax($project);
		$total += Endresult::subconCalcMaterialActivityTax2AmountTax($project);

		$total += Endresult::subconCalcEquipmentActivityTax1AmountTax($project);
		$total += Endresult::subconCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalProject($project) {
		return Endresult::totalContracting($project) + Endresult::totalSubcontracting($project);
	}

	public static function totalContractingTax1($project) {
		$total = 0;

		$total += Endresult::conCalcLaborActivityTax1AmountTax($project);
		$total += Endresult::conCalcMaterialActivityTax1AmountTax($project);
		$total += Endresult::conCalcEquipmentActivityTax1AmountTax($project);

		return $total;
	}

	public static function totalContractingTax2($project) {
		$total = 0;

		$total += Endresult::conCalcLaborActivityTax2AmountTax($project);
		$total += Endresult::conCalcMaterialActivityTax2AmountTax($project);
		$total += Endresult::conCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalSubcontractingTax1($project) {
		$total = 0;

		$total += Endresult::subconCalcLaborActivityTax1AmountTax($project);
		$total += Endresult::subconCalcMaterialActivityTax1AmountTax($project);
		$total += Endresult::subconCalcEquipmentActivityTax1AmountTax($project);

		return $total;
	}

	public static function totalSubcontractingTax2($project) {
		$total = 0;

		$total += Endresult::subconCalcLaborActivityTax2AmountTax($project);
		$total += Endresult::subconCalcMaterialActivityTax2AmountTax($project);
		$total += Endresult::subconCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalProjectTax($project) {
		return Endresult::totalContractingTax($project) + Endresult::totalSubcontractingTax($project);
	}

	public static function superTotalProject($project) {
		return Endresult::totalProject($project) + Endresult::totalProjectTax($project);
	}
}
