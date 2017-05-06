<?php

namespace BynqIO\Dynq\Calculus;

use BynqIO\Dynq\Models\Part;
use BynqIO\Dynq\Models\Tax;
use BynqIO\Dynq\Models\Chapter;
use BynqIO\Dynq\Models\Activity;
use BynqIO\Dynq\Models\PartType;
use BynqIO\Dynq\Models\EstimateLabor;
use BynqIO\Dynq\Models\CalculationMaterial;
use BynqIO\Dynq\Models\EstimateMaterial;
use BynqIO\Dynq\Models\CalculationEquipment;
use BynqIO\Dynq\Models\EstimateEquipment;
use BynqIO\Dynq\Models\CalculationLabor;

/*
 * Eindresultaat stelposten stellen
 */
class SetEstimateCalculationEndresult {

	public static function conCalcLaborActivityTax1($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$cnt = EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->count('id');

					if ($cnt) {
						$total += EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->sum('set_amount');
					} else {
						$amount_set = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','true')->sum('set_amount');
						$amount = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','false')->sum('amount');
						$total += $amount_set + $amount;
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
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$cnt = EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->count('id');

					if ($cnt) {
						$total += EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->sum('set_amount');
					} else {
						$amount_set = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','true')->sum('set_amount');
						$amount = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','false')->sum('amount');
						$total += $amount_set + $amount;
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
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$cnt = EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->count('id');

					if ($cnt) {
						$total += EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->sum('set_amount');
					} else {
						$amount_set = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','true')->sum('set_amount');
						$amount = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','false')->sum('amount');
						$total += $amount_set + $amount;
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
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$cnt = EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->count('id');

					if ($cnt) {
						$total += EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->sum('set_amount');
					} else {
						$amount_set = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','true')->sum('set_amount');
						$amount = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','false')->sum('amount');
						$total += $amount_set + $amount;
					}

				}
				$total += CalculationLabor::where('activity_id','=',$activity->id)->sum('amount');
			}
		}

		return $total * $project->hour_rate;
	}

	public static function conCalcLaborActivityTax2Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','6')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$cnt = EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->count('id');

					if ($cnt) {
						$total += EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->sum('set_amount');
					} else {
						$amount_set = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','true')->sum('set_amount');
						$amount = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','false')->sum('amount');
						$total += $amount_set + $amount;
					}

				}
				$total += CalculationLabor::where('activity_id','=',$activity->id)->sum('amount');
			}
		}

		return $total * $project->hour_rate;
	}

	public static function conCalcLaborActivityTax3Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','0')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$cnt = EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->count('id');

					if ($cnt) {
						$total += EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->sum('set_amount');
					} else {
						$amount_set = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','true')->sum('set_amount');
						$amount = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','false')->sum('amount');
						$total += $amount_set + $amount;
					}

				}
				$total += CalculationLabor::where('activity_id','=',$activity->id)->sum('amount');
			}
		}

		return $total * $project->hour_rate;
	}

	public static function conCalcMaterialActivityTax1Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				$rows = [];
				$set_rows = [];
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateMaterial::where('activity_id','=',$activity->id)->where('isset','=','false')->get();
					$set_rows = EstimateMaterial::where('activity_id','=',$activity->id)->where('isset','=','true')->get();
				}
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
				foreach ($set_rows as $row)
				{
					$total += $row->set_rate * $row->set_amount;
				}
			
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				$rows = [];
				$set_rows = [];
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateMaterial::where('activity_id','=',$activity->id)->where('isset','=','false')->get();
					$set_rows = EstimateMaterial::where('activity_id','=',$activity->id)->where('isset','=','true')->get();
				}
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
				foreach ($set_rows as $row)
				{
					$total += $row->set_rate * $row->set_amount;
				}
			
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				$rows = [];
				$set_rows = [];
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateMaterial::where('activity_id','=',$activity->id)->where('isset','=','false')->get();
					$set_rows = EstimateMaterial::where('activity_id','=',$activity->id)->where('isset','=','true')->get();
				}
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
				foreach ($set_rows as $row)
				{
					$total += $row->set_rate * $row->set_amount;
				}
			
				$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_calc_contr_mat)/100);
	}

	public static function conCalcEquipmentActivityTax1Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				$rows = [];
				$set_rows = [];
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateEquipment::where('activity_id','=',$activity->id)->where('isset','=','false')->get();
					$set_rows = EstimateEquipment::where('activity_id','=',$activity->id)->where('isset','=','true')->get();
				}
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
				foreach ($set_rows as $row)
				{
					$total += $row->set_rate * $row->set_amount;
				}
			
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				$rows = [];
				$set_rows = [];
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateEquipment::where('activity_id','=',$activity->id)->where('isset','=','false')->get();
					$set_rows = EstimateEquipment::where('activity_id','=',$activity->id)->where('isset','=','true')->get();
				}
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
				foreach ($set_rows as $row)
				{
					$total += $row->set_rate * $row->set_amount;
				}
			
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				$rows = [];
				$set_rows = [];
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateEquipment::where('activity_id','=',$activity->id)->where('isset','=','false')->get();
					$set_rows = EstimateEquipment::where('activity_id','=',$activity->id)->where('isset','=','true')->get();
				}
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
				foreach ($set_rows as $row)
				{
					$total += $row->set_rate * $row->set_amount;
				}
			
				$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}


		return $total + ($total * ($project->profit_calc_contr_equip)/100);
	}

	public static function subconCalcLaborActivityTax1($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$cnt = EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->count('id');

					if ($cnt) {
						$total += EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->sum('set_amount');
					} else {
						$amount_set = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','true')->sum('set_amount');
						$amount = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','false')->sum('amount');
						$total += $amount_set + $amount;
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
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$cnt = EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->count('id');

					if ($cnt) {
						$total += EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->sum('set_amount');
					} else {
						$amount_set = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','true')->sum('set_amount');
						$amount = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','false')->sum('amount');
						$total += $amount_set + $amount;
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
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$cnt = EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->count('id');

					if ($cnt) {
						$total += EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->sum('set_amount');
					} else {
						$amount_set = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','true')->sum('set_amount');
						$amount = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','false')->sum('amount');
						$total += $amount_set + $amount;
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
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$cnt = EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->count('id');

					if ($cnt) {
						$total += EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->sum('set_amount');
					} else {
						$amount_set = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','true')->sum('set_amount');
						$rate_set = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','true')->first()['set_rate'];
						$amount = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','false')->sum('amount');
						$rate = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','false')->first()['rate'];
						$total += ($amount_set * $rate_set) + ($amount * $rate);
					}
				}
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$cnt = EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->count('id');

					if ($cnt) {
						$total += EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->sum('set_amount');
					} else {
						$amount_set = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','true')->sum('set_amount');
						$rate_set = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','true')->first()['set_rate'];
						$amount = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','false')->sum('amount');
						$rate = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','false')->first()['rate'];
						$total += ($amount_set * $rate_set) + ($amount * $rate);
					}
				}
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$cnt = EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->count('id');

					if ($cnt) {
						$total += EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->sum('set_amount');
					} else {
						$amount_set = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','true')->sum('set_amount');
						$rate_set = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','true')->first()['set_rate'];
						$amount = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','false')->sum('amount');
						$rate = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','false')->first()['rate'];
						$total += ($amount_set * $rate_set) + ($amount * $rate);
					}
				}
				$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total;
	}

	public static function subconCalcMaterialActivityTax1Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				$rows = [];
				$set_rows = [];
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateMaterial::where('activity_id','=',$activity->id)->where('isset','=','false')->get();
					$set_rows = EstimateMaterial::where('activity_id','=',$activity->id)->where('isset','=','true')->get();
				}
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
				foreach ($set_rows as $row)
				{
					$total += $row->set_rate * $row->set_amount;
				}
			
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				$rows = [];
				$set_rows = [];
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateMaterial::where('activity_id','=',$activity->id)->where('isset','=','false')->get();
					$set_rows = EstimateMaterial::where('activity_id','=',$activity->id)->where('isset','=','true')->get();
				}
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
				foreach ($set_rows as $row)
				{
					$total += $row->set_rate * $row->set_amount;
				}
			
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				$rows = [];
				$set_rows = [];
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateMaterial::where('activity_id','=',$activity->id)->where('isset','=','false')->get();
					$set_rows = EstimateMaterial::where('activity_id','=',$activity->id)->where('isset','=','true')->get();
				}
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
				foreach ($set_rows as $row)
				{
					$total += $row->set_rate * $row->set_amount;
				}
			
				$rows = CalculationMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_calc_subcontr_mat)/100);
	}

	public static function subconCalcEquipmentActivityTax1Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				$rows = [];
				$set_rows = [];
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateEquipment::where('activity_id','=',$activity->id)->where('isset','=','false')->get();
					$set_rows = EstimateEquipment::where('activity_id','=',$activity->id)->where('isset','=','true')->get();
				}
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
				foreach ($set_rows as $row)
				{
					$total += $row->set_rate * $row->set_amount;
				}
			
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				$rows = [];
				$set_rows = [];
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateEquipment::where('activity_id','=',$activity->id)->where('isset','=','false')->get();
					$set_rows = EstimateEquipment::where('activity_id','=',$activity->id)->where('isset','=','true')->get();
				}
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
				foreach ($set_rows as $row)
				{
					$total += $row->set_rate * $row->set_amount;
				}
			
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				$rows = [];
				$set_rows = [];
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					$rows = EstimateEquipment::where('activity_id','=',$activity->id)->where('isset','=','false')->get();
					$set_rows = EstimateEquipment::where('activity_id','=',$activity->id)->where('isset','=','true')->get();
				}
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
				foreach ($set_rows as $row)
				{
					$total += $row->set_rate * $row->set_amount;
				}
			
				$rows = CalculationEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_calc_subcontr_equip)/100);
	}
	public static function totalContracting($project) {
		$total = 0;

		$total += SetEstimateCalculationEndresult::conCalcLaborActivityTax1Amount($project);
		$total += SetEstimateCalculationEndresult::conCalcLaborActivityTax2Amount($project);
		$total += SetEstimateCalculationEndresult::conCalcLaborActivityTax3Amount($project);

		$total += SetEstimateCalculationEndresult::conCalcMaterialActivityTax1Amount($project);
		$total += SetEstimateCalculationEndresult::conCalcMaterialActivityTax2Amount($project);
		$total += SetEstimateCalculationEndresult::conCalcMaterialActivityTax3Amount($project);

		$total += SetEstimateCalculationEndresult::conCalcEquipmentActivityTax1Amount($project);
		$total += SetEstimateCalculationEndresult::conCalcEquipmentActivityTax2Amount($project);
		$total += SetEstimateCalculationEndresult::conCalcEquipmentActivityTax3Amount($project);

		return $total;
	}

	public static function totalContractingTax($project) {
		$total = 0;

		$total += SetEstimateCalculationEndresult::conCalcLaborActivityTax1AmountTax($project);
		$total += SetEstimateCalculationEndresult::conCalcLaborActivityTax2AmountTax($project);

		$total += SetEstimateCalculationEndresult::conCalcMaterialActivityTax1AmountTax($project);
		$total += SetEstimateCalculationEndresult::conCalcMaterialActivityTax2AmountTax($project);

		$total += SetEstimateCalculationEndresult::conCalcEquipmentActivityTax1AmountTax($project);
		$total += SetEstimateCalculationEndresult::conCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalSubcontracting($project) {
		$total = 0;

		$total += SetEstimateCalculationEndresult::subconCalcLaborActivityTax1Amount($project);
		$total += SetEstimateCalculationEndresult::subconCalcLaborActivityTax2Amount($project);
		$total += SetEstimateCalculationEndresult::subconCalcLaborActivityTax3Amount($project);

		$total += SetEstimateCalculationEndresult::subconCalcMaterialActivityTax1Amount($project);
		$total += SetEstimateCalculationEndresult::subconCalcMaterialActivityTax2Amount($project);
		$total += SetEstimateCalculationEndresult::subconCalcMaterialActivityTax3Amount($project);

		$total += SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax1Amount($project);
		$total += SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax2Amount($project);
		$total += SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax3Amount($project);

		return $total;
	}

	public static function totalSubcontractingTax($project) {
		$total = 0;

		$total += SetEstimateCalculationEndresult::subconCalcLaborActivityTax1AmountTax($project);
		$total += SetEstimateCalculationEndresult::subconCalcLaborActivityTax2AmountTax($project);

		$total += SetEstimateCalculationEndresult::subconCalcMaterialActivityTax1AmountTax($project);
		$total += SetEstimateCalculationEndresult::subconCalcMaterialActivityTax2AmountTax($project);

		$total += SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax1AmountTax($project);
		$total += SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalProject($project) {
		return SetEstimateCalculationEndresult::totalContracting($project) + SetEstimateCalculationEndresult::totalSubcontracting($project);
	}

	public static function totalContractingTax1($project) {
		$total = 0;

		$total += SetEstimateCalculationEndresult::conCalcLaborActivityTax1AmountTax($project);
		$total += SetEstimateCalculationEndresult::conCalcMaterialActivityTax1AmountTax($project);
		$total += SetEstimateCalculationEndresult::conCalcEquipmentActivityTax1AmountTax($project);

		return $total;
	}

	public static function totalContractingTax2($project) {
		$total = 0;

		$total += SetEstimateCalculationEndresult::conCalcLaborActivityTax2AmountTax($project);
		$total += SetEstimateCalculationEndresult::conCalcMaterialActivityTax2AmountTax($project);
		$total += SetEstimateCalculationEndresult::conCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalSubcontractingTax1($project) {
		$total = 0;

		$total += SetEstimateCalculationEndresult::subconCalcLaborActivityTax1AmountTax($project);
		$total += SetEstimateCalculationEndresult::subconCalcMaterialActivityTax1AmountTax($project);
		$total += SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax1AmountTax($project);

		return $total;
	}

	public static function totalSubcontractingTax2($project) {
		$total = 0;

		$total += SetEstimateCalculationEndresult::subconCalcLaborActivityTax2AmountTax($project);
		$total += SetEstimateCalculationEndresult::subconCalcMaterialActivityTax2AmountTax($project);
		$total += SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalProjectTax($project) {
		return SetEstimateCalculationEndresult::totalContractingTax($project) + SetEstimateCalculationEndresult::totalSubcontractingTax($project);
	}

	public static function superTotalProject($project) {
		return SetEstimateCalculationEndresult::totalProject($project) + SetEstimateCalculationEndresult::totalProjectTax($project);
	}
	
}
