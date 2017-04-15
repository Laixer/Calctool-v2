<?php

namespace BynqIO\CalculatieTool\Calculus;

use \BynqIO\CalculatieTool\Models\Part;
use \BynqIO\CalculatieTool\Models\Tax;
use \BynqIO\CalculatieTool\Models\Chapter;
use \BynqIO\CalculatieTool\Models\Activity;
use \BynqIO\CalculatieTool\Models\CalculationLabor;
use \BynqIO\CalculatieTool\Models\CalculationMaterial;
use \BynqIO\CalculatieTool\Models\CalculationEquipment;

/*
 * Eindresultaat
 */
class LessEndresult {

	public static function conCalcLaborActivityTax1($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless) {
						$total += $row->less_amount - $row->amount;
					}
				}
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless) {
						$total += $row->less_amount - $row->amount;
					}
				}
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless) {
						$total += $row->less_amount - $row->amount;
					}
				}
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless) {
						$total += $project->hour_rate * ($row->less_amount - $row->amount);
					}
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless) {
						$total += $project->hour_rate * ($row->less_amount - $row->amount);
					}
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless) {
						$total += $project->hour_rate * ($row->less_amount - $row->amount);
					}
				}
			}
		}

		return $total;
	}

	public static function conCalcLaborActivityTax1AmountTax($project) {
		return (LessEndresult::conCalcLaborActivityTax1Amount($project)/100)*21;
	}

	public static function conCalcLaborActivityTax2AmountTax($project) {
		return (LessEndresult::conCalcLaborActivityTax2Amount($project)/100)*6;
	}

	public static function conCalcMaterialActivityTax1Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless)
						$total += ($row->less_rate * $row->less_amount) - ($row->rate * $row->amount);
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless)
						$total += ($row->less_rate * $row->less_amount) - ($row->rate * $row->amount);
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless)
						$total += ($row->less_rate * $row->less_amount) - ($row->rate * $row->amount);
				}
			}
		}

		return $total + ($total * ($project->profit_calc_contr_mat)/100);
	}

	public static function conCalcMaterialActivityTax1AmountTax($project) {
		return (LessEndresult::conCalcMaterialActivityTax1Amount($project)/100)*21;
	}

	public static function conCalcMaterialActivityTax2AmountTax($project) {
		return (LessEndresult::conCalcMaterialActivityTax2Amount($project)/100)*6;
	}

	public static function conCalcEquipmentActivityTax1Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless)
						$total += ($row->less_rate * $row->less_amount) - ($row->rate * $row->amount);
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless)
						$total += ($row->less_rate * $row->less_amount) - ($row->rate * $row->amount);
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless)
						$total += ($row->less_rate * $row->less_amount) - ($row->rate * $row->amount);
				}
			}
		}

		return $total + ($total * ($project->profit_calc_contr_equip)/100);
	}

	public static function conCalcEquipmentActivityTax1AmountTax($project) {
		return (LessEndresult::conCalcEquipmentActivityTax1Amount($project)/100)*21;
	}

	public static function conCalcEquipmentActivityTax2AmountTax($project) {
		return (LessEndresult::conCalcEquipmentActivityTax2Amount($project)/100)*6;
	}

	public static function subconCalcLaborActivityTax1($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless) {
						$total += $row->less_amount - $row->amount;
					}
				}
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless) {
						$total += $row->less_amount - $row->amount;
					}
				}
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless) {
						$total += $row->less_amount - $row->amount;
					}
				}
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless) {
						$total += $row->rate * ($row->less_amount - $row->amount);
					}
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless) {
						$total += $row->rate * ($row->less_amount - $row->amount);
					}
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless) {
						$total += $row->rate * ($row->less_amount - $row->amount);
					}
				}
			}
		}

		return $total;
	}

	public static function subconCalcLaborActivityTax1AmountTax($project) {
		return (LessEndresult::subconCalcLaborActivityTax1Amount($project)/100)*21;
	}

	public static function subconCalcLaborActivityTax2AmountTax($project) {
		return (LessEndresult::subconCalcLaborActivityTax2Amount($project)/100)*6;
	}

	public static function subconCalcMaterialActivityTax1Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless)
						$total += ($row->less_rate * $row->less_amount) - ($row->rate * $row->amount);
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless)
						$total += ($row->less_rate * $row->less_amount) - ($row->rate * $row->amount);
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless)
						$total += ($row->less_rate * $row->less_amount) - ($row->rate * $row->amount);
				}
			}
		}

		return $total + ($total * ($project->profit_calc_subcontr_mat)/100);
	}

	public static function subconCalcMaterialActivityTax1AmountTax($project) {
		return (LessEndresult::subconCalcMaterialActivityTax1Amount($project)/100)*21;
	}

	public static function subconCalcMaterialActivityTax2AmountTax($project) {
		return (LessEndresult::subconCalcMaterialActivityTax2Amount($project)/100)*6;
	}

	public static function subconCalcEquipmentActivityTax1Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless)
						$total += ($row->less_rate * $row->less_amount) - ($row->rate * $row->amount);
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless)
						$total += ($row->less_rate * $row->less_amount) - ($row->rate * $row->amount);
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row->isless)
						$total += ($row->less_rate * $row->less_amount) - ($row->rate * $row->amount);
				}
			}
		}

		return $total + ($total * ($project->profit_calc_subcontr_equip)/100);
	}

	public static function subconCalcEquipmentActivityTax1AmountTax($project) {
		return (LessEndresult::subconCalcEquipmentActivityTax1Amount($project)/100)*21;
	}

	public static function subconCalcEquipmentActivityTax2AmountTax($project) {
		return (LessEndresult::subconCalcEquipmentActivityTax2Amount($project)/100)*6;
	}

	public static function totalContracting($project) {
		$total = 0;

		$total += LessEndresult::conCalcLaborActivityTax1Amount($project);
		$total += LessEndresult::conCalcLaborActivityTax2Amount($project);
		$total += LessEndresult::conCalcLaborActivityTax3Amount($project);

		$total += LessEndresult::conCalcMaterialActivityTax1Amount($project);
		$total += LessEndresult::conCalcMaterialActivityTax2Amount($project);
		$total += LessEndresult::conCalcMaterialActivityTax3Amount($project);

		$total += LessEndresult::conCalcEquipmentActivityTax1Amount($project);
		$total += LessEndresult::conCalcEquipmentActivityTax2Amount($project);
		$total += LessEndresult::conCalcEquipmentActivityTax3Amount($project);

		return $total;
	}

	public static function totalContractingTax($project) {
		$total = 0;

		$total += LessEndresult::conCalcLaborActivityTax1AmountTax($project);
		$total += LessEndresult::conCalcLaborActivityTax2AmountTax($project);

		$total += LessEndresult::conCalcMaterialActivityTax1AmountTax($project);
		$total += LessEndresult::conCalcMaterialActivityTax2AmountTax($project);

		$total += LessEndresult::conCalcEquipmentActivityTax1AmountTax($project);
		$total += LessEndresult::conCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalSubcontracting($project) {
		$total = 0;

		$total += LessEndresult::subconCalcLaborActivityTax1Amount($project);
		$total += LessEndresult::subconCalcLaborActivityTax2Amount($project);
		$total += LessEndresult::subconCalcLaborActivityTax3Amount($project);

		$total += LessEndresult::subconCalcMaterialActivityTax1Amount($project);
		$total += LessEndresult::subconCalcMaterialActivityTax2Amount($project);
		$total += LessEndresult::subconCalcMaterialActivityTax3Amount($project);

		$total += LessEndresult::subconCalcEquipmentActivityTax1Amount($project);
		$total += LessEndresult::subconCalcEquipmentActivityTax2Amount($project);
		$total += LessEndresult::subconCalcEquipmentActivityTax3Amount($project);

		return $total;
	}

	public static function totalSubcontractingTax($project) {
		$total = 0;

		$total += LessEndresult::subconCalcLaborActivityTax1AmountTax($project);
		$total += LessEndresult::subconCalcLaborActivityTax2AmountTax($project);

		$total += LessEndresult::subconCalcMaterialActivityTax1AmountTax($project);
		$total += LessEndresult::subconCalcMaterialActivityTax2AmountTax($project);

		$total += LessEndresult::subconCalcEquipmentActivityTax1AmountTax($project);
		$total += LessEndresult::subconCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalProject($project) {
		return LessEndresult::totalContracting($project) + LessEndresult::totalSubcontracting($project);
	}

	public static function totalContractingTax1($project) {
		$total = 0;

		$total += LessEndresult::conCalcLaborActivityTax1AmountTax($project);
		$total += LessEndresult::conCalcMaterialActivityTax1AmountTax($project);
		$total += LessEndresult::conCalcEquipmentActivityTax1AmountTax($project);

		return $total;
	}

	public static function totalContractingTax2($project) {
		$total = 0;

		$total += LessEndresult::conCalcLaborActivityTax2AmountTax($project);
		$total += LessEndresult::conCalcMaterialActivityTax2AmountTax($project);
		$total += LessEndresult::conCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalSubcontractingTax1($project) {
		$total = 0;

		$total += LessEndresult::subconCalcLaborActivityTax1AmountTax($project);
		$total += LessEndresult::subconCalcMaterialActivityTax1AmountTax($project);
		$total += LessEndresult::subconCalcEquipmentActivityTax1AmountTax($project);

		return $total;
	}

	public static function totalSubcontractingTax2($project) {
		$total = 0;

		$total += LessEndresult::subconCalcLaborActivityTax2AmountTax($project);
		$total += LessEndresult::subconCalcMaterialActivityTax2AmountTax($project);
		$total += LessEndresult::subconCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalProjectTax($project) {
		return LessEndresult::totalContractingTax($project) + LessEndresult::totalSubcontractingTax($project);
	}

	public static function superTotalProject($project) {
		return LessEndresult::totalProject($project) + LessEndresult::totalProjectTax($project);
	}
}
