<?php

namespace Calctool\Calculus;

use \Calctool\Models\Activity;
use \Calctool\Models\PartType;
use \Calctool\Models\CalculationLabor;
use \Calctool\Models\CalculationMaterial;
use \Calctool\Models\CalculationEquipment;


/*
 * Eindresultaat
 */
class CalculationEndresult {

	public static function conCalcLaborActivityTax1($project) {
		$total = 0;
		$part_id = \Calctool\Models\Part::where('part_name','=','contracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','21')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$total += EstimateLabor::where('activity_id','=',$activity->id)->sum('amount');
				} else {
					$total += CalculationLabor::where('activity_id','=',$activity->id)->sum('amount');
				}
			}
		}

		return $total;
	}

	public static function conCalcLaborActivityTax2($project) {
		$total = 0;
		$part_id = \Calctool\Models\Part::where('part_name','=','contracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','6')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$total += EstimateLabor::where('activity_id','=',$activity->id)->sum('amount');
				} else {
					$total += CalculationLabor::where('activity_id','=',$activity->id)->sum('amount');
				}
			}
		}

		return $total;
	}

	public static function conCalcLaborActivityTax3($project) {
		$total = 0;
		$part_id = \Calctool\Models\Part::where('part_name','=','contracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','0')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$total += EstimateLabor::where('activity_id','=',$activity->id)->sum('amount');
				} else {
					$total += CalculationLabor::where('activity_id','=',$activity->id)->sum('amount');
				}
			}
		}

		return $total;
	}

	public static function conCalcLaborActivityTax1Amount($project) {
		$total = 0;
		$part_id = \Calctool\Models\Part::where('part_name','=','contracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','21')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (\Calctool\Models\Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateLabor::where('activity_id','=',$activity->id)->get();
					} else {
						$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
					}
					foreach ($rows as $row)
					{
						$total += $project->hour_rate * $row->amount;
					}
				}
			}

		return $total;
	}

	public static function conCalcLaborActivityTax2Amount($project) {
		$total = 0;
		$part_id = \Calctool\Models\Part::where('part_name','=','contracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','6')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateLabor::where('activity_id','=',$activity->id)->get();
					} else {
						$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
					}
					foreach ($rows as $row)
					{
						$total += $project->hour_rate * $row->amount;
					}
				}
			}

		return $total;
	}

	public static function conCalcLaborActivityTax3Amount($project) {
		$total = 0;
		$part_id = \Calctool\Models\Part::where('part_name','=','contracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','0')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateLabor::where('activity_id','=',$activity->id)->get();
					} else {
						$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
					}
					foreach ($rows as $row)
					{
						$total += $project->hour_rate * $row->amount;
					}
				}
			}

		return $total;
	}

	public static function conCalcLaborActivityTax1AmountTax($project) {
		return (CalculationEndresult::conCalcLaborActivityTax1Amount($project)/100)*21;
	}

	public static function conCalcLaborActivityTax2AmountTax($project) {
		return (CalculationEndresult::conCalcLaborActivityTax2Amount($project)/100)*6;
	}

	public static function conCalcMaterialActivityTax1Amount($project) {
		$total = 0;
		$part_id = \Calctool\Models\Part::where('part_name','=','contracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','21')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateMaterial::where('activity_id','=',$activity->id)->get();
					} else {
						$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
					}
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
		$part_id = \Calctool\Models\Part::where('part_name','=','contracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','6')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateMaterial::where('activity_id','=',$activity->id)->get();
					} else {
						$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
					}
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
		$part_id = \Calctool\Models\Part::where('part_name','=','contracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','0')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateMaterial::where('activity_id','=',$activity->id)->get();
					} else {
						$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
					}
						foreach ($rows as $row)
					{
						$total += $row->rate * $row->amount;
					}
				}
			}

			return $total + ($total * ($project->profit_calc_contr_mat)/100);
		}

	public static function conCalcMaterialActivityTax1AmountTax($project) {
		return (CalculationEndresult::conCalcMaterialActivityTax1Amount($project)/100)*21;
	}

	public static function conCalcMaterialActivityTax2AmountTax($project) {
		return (CalculationEndresult::conCalcMaterialActivityTax2Amount($project)/100)*6;
	}

	public static function conCalcEquipmentActivityTax1Amount($project) {
		$total = 0;
		$part_id = \Calctool\Models\Part::where('part_name','=','contracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','21')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateEquipment::where('activity_id','=',$activity->id)->get();
					} else {
						$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
					}
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
		$part_id = \Calctool\Models\Part::where('part_name','=','contracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','6')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateEquipment::where('activity_id','=',$activity->id)->get();
					} else {
						$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
					}
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
		$part_id = \Calctool\Models\Part::where('part_name','=','contracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','0')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateEquipment::where('activity_id','=',$activity->id)->get();
					} else {
						$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
					}
						foreach ($rows as $row)
					{
						$total += $row->rate * $row->amount;
					}
				}
			}

			return $total + ($total * ($project->profit_calc_contr_equip)/100);
		}

	public static function conCalcEquipmentActivityTax1AmountTax($project) {
		return (CalculationEndresult::conCalcEquipmentActivityTax1Amount($project)/100)*21;
	}

	public static function conCalcEquipmentActivityTax2AmountTax($project) {
		return (CalculationEndresult::conCalcEquipmentActivityTax2Amount($project)/100)*6;
	}

	public static function subconCalcLaborActivityTax1($project) {
		$total = 0;
		$part_id = \Calctool\Models\Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','21')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$total += EstimateLabor::where('activity_id','=',$activity->id)->sum('amount');
				} else {
					$total += CalculationLabor::where('activity_id','=',$activity->id)->sum('amount');
				}
			}
		}

		return $total;
	}

	public static function subconCalcLaborActivityTax2($project) {
		$total = 0;
		$part_id = \Calctool\Models\Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','6')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$total += EstimateLabor::where('activity_id','=',$activity->id)->sum('amount');
				} else {
					$total += CalculationLabor::where('activity_id','=',$activity->id)->sum('amount');
				}
			}
		}

		return $total;
	}

	public static function subconCalcLaborActivityTax3($project) {
		$total = 0;
		$part_id = \Calctool\Models\Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','0')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$total += EstimateLabor::where('activity_id','=',$activity->id)->sum('amount');
				} else {
					$total += CalculationLabor::where('activity_id','=',$activity->id)->sum('amount');
				}
			}
		}

		return $total;
	}

	public static function subconCalcLaborActivityTax1Amount($project) {
		$total = 0;
		$part_id = \Calctool\Models\Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','21')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateLabor::where('activity_id','=',$activity->id)->get();
					} else {
						$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
					}
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
		$part_id = \Calctool\Models\Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','6')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateLabor::where('activity_id','=',$activity->id)->get();
					} else {
						$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
					}
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
		$part_id = \Calctool\Models\Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','0')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateLabor::where('activity_id','=',$activity->id)->get();
					} else {
						$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
					}
					foreach ($rows as $row)
					{
						$total += $row->rate * $row->amount;
					}
				}
			}

		return $total;
	}

	public static function subconCalcLaborActivityTax1AmountTax($project) {
		return (CalculationEndresult::subconCalcLaborActivityTax1Amount($project)/100)*21;
	}

	public static function subconCalcLaborActivityTax2AmountTax($project) {
		return (CalculationEndresult::subconCalcLaborActivityTax2Amount($project)/100)*6;
	}

	public static function subconCalcMaterialActivityTax1Amount($project) {
		$total = 0;
		$part_id = \Calctool\Models\Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','21')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateMaterial::where('activity_id','=',$activity->id)->get();
					} else {
						$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
					}
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
		$part_id = \Calctool\Models\Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','6')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateMaterial::where('activity_id','=',$activity->id)->get();
					} else {
						$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
					}
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
		$part_id = \Calctool\Models\Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','0')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateMaterial::where('activity_id','=',$activity->id)->get();
					} else {
						$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
					}
						foreach ($rows as $row)
					{
						$total += $row->rate * $row->amount;
					}
				}
			}

			return $total + ($total * ($project->profit_calc_subcontr_mat)/100);
		}

	public static function subconCalcMaterialActivityTax1AmountTax($project) {
		return (CalculationEndresult::subconCalcMaterialActivityTax1Amount($project)/100)*21;
	}

	public static function subconCalcMaterialActivityTax2AmountTax($project) {
		return (CalculationEndresult::subconCalcMaterialActivityTax2Amount($project)/100)*6;
	}

	public static function subconCalcEquipmentActivityTax1Amount($project) {
		$total = 0;
		$part_id = \Calctool\Models\Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','21')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateEquipment::where('activity_id','=',$activity->id)->get();
					} else {
						$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
					}
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
		$part_id = \Calctool\Models\Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','6')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateEquipment::where('activity_id','=',$activity->id)->get();
					} else {
						$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
					}
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
		$part_id = \Calctool\Models\Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = \Calctool\Models\Tax::where('tax_rate','=','0')->first()->id;

		foreach (\Calctool\Models\Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateEquipment::where('activity_id','=',$activity->id)->get();
					} else {
						$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
					}
						foreach ($rows as $row)
					{
						$total += $row->rate * $row->amount;
					}
				}
			}

			return $total + ($total * ($project->profit_calc_subcontr_equip)/100);
		}

	public static function subconCalcEquipmentActivityTax1AmountTax($project) {
		return (CalculationEndresult::subconCalcEquipmentActivityTax1Amount($project)/100)*21;
	}

	public static function subconCalcEquipmentActivityTax2AmountTax($project) {
		return (CalculationEndresult::subconCalcEquipmentActivityTax2Amount($project)/100)*6;
	}

	public static function totalContracting($project) {
		$total = 0;

		$total += CalculationEndresult::conCalcLaborActivityTax1Amount($project);
		$total += CalculationEndresult::conCalcLaborActivityTax2Amount($project);
		$total += CalculationEndresult::conCalcLaborActivityTax3Amount($project);

		$total += CalculationEndresult::conCalcMaterialActivityTax1Amount($project);
		$total += CalculationEndresult::conCalcMaterialActivityTax2Amount($project);
		$total += CalculationEndresult::conCalcMaterialActivityTax3Amount($project);

		$total += CalculationEndresult::conCalcEquipmentActivityTax1Amount($project);
		$total += CalculationEndresult::conCalcEquipmentActivityTax2Amount($project);
		$total += CalculationEndresult::conCalcEquipmentActivityTax3Amount($project);

		return $total;
	}

	public static function totalContractingTax($project) {
		$total = 0;

		$total += CalculationEndresult::conCalcLaborActivityTax1AmountTax($project);
		$total += CalculationEndresult::conCalcLaborActivityTax2AmountTax($project);

		$total += CalculationEndresult::conCalcMaterialActivityTax1AmountTax($project);
		$total += CalculationEndresult::conCalcMaterialActivityTax2AmountTax($project);

		$total += CalculationEndresult::conCalcEquipmentActivityTax1AmountTax($project);
		$total += CalculationEndresult::conCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalSubcontracting($project) {
		$total = 0;

		$total += CalculationEndresult::subconCalcLaborActivityTax1Amount($project);
		$total += CalculationEndresult::subconCalcLaborActivityTax2Amount($project);
		$total += CalculationEndresult::subconCalcLaborActivityTax3Amount($project);

		$total += CalculationEndresult::subconCalcMaterialActivityTax1Amount($project);
		$total += CalculationEndresult::subconCalcMaterialActivityTax2Amount($project);
		$total += CalculationEndresult::subconCalcMaterialActivityTax3Amount($project);

		$total += CalculationEndresult::subconCalcEquipmentActivityTax1Amount($project);
		$total += CalculationEndresult::subconCalcEquipmentActivityTax2Amount($project);
		$total += CalculationEndresult::subconCalcEquipmentActivityTax3Amount($project);

		return $total;
	}

	public static function totalSubcontractingTax($project) {
		$total = 0;

		$total += CalculationEndresult::subconCalcLaborActivityTax1AmountTax($project);
		$total += CalculationEndresult::subconCalcLaborActivityTax2AmountTax($project);

		$total += CalculationEndresult::subconCalcMaterialActivityTax1AmountTax($project);
		$total += CalculationEndresult::subconCalcMaterialActivityTax2AmountTax($project);

		$total += CalculationEndresult::subconCalcEquipmentActivityTax1AmountTax($project);
		$total += CalculationEndresult::subconCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalProject($project) {
		return CalculationEndresult::totalContracting($project) + CalculationEndresult::totalSubcontracting($project);
	}

	public static function totalContractingTax1($project) {
		$total = 0;

		$total += CalculationEndresult::conCalcLaborActivityTax1AmountTax($project);
		$total += CalculationEndresult::conCalcMaterialActivityTax1AmountTax($project);
		$total += CalculationEndresult::conCalcEquipmentActivityTax1AmountTax($project);

		return $total;
	}

	public static function totalContractingTax2($project) {
		$total = 0;

		$total += CalculationEndresult::conCalcLaborActivityTax2AmountTax($project);
		$total += CalculationEndresult::conCalcMaterialActivityTax2AmountTax($project);
		$total += CalculationEndresult::conCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalSubcontractingTax1($project) {
		$total = 0;

		$total += CalculationEndresult::subconCalcLaborActivityTax1AmountTax($project);
		$total += CalculationEndresult::subconCalcMaterialActivityTax1AmountTax($project);
		$total += CalculationEndresult::subconCalcEquipmentActivityTax1AmountTax($project);

		return $total;
	}

	public static function totalSubcontractingTax2($project) {
		$total = 0;

		$total += CalculationEndresult::subconCalcLaborActivityTax2AmountTax($project);
		$total += CalculationEndresult::subconCalcMaterialActivityTax2AmountTax($project);
		$total += CalculationEndresult::subconCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalProjectTax($project) {
		return CalculationEndresult::totalContractingTax($project) + CalculationEndresult::totalSubcontractingTax($project);
	}

	public static function superTotalProject($project) {
		return CalculationEndresult::totalProject($project) + CalculationEndresult::totalProjectTax($project);
	}
}
