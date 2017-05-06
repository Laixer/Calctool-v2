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

/*
 * Eindresultaat stelposten stellen
 */
class SetEstimateEndresult {

	public static function conCalcLaborActivityTax1($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				if (PartType::find($activity->part_type_id)->type_name=='estimate') {
					if ($activity->use_timesheet) {
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
					if ($activity->use_timesheet) {
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
					if ($activity->use_timesheet) {
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
					if ($activity->use_timesheet) {
						$total += EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->sum('set_amount');
					} else {
						$amount_set = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','true')->sum('set_amount');
						$amount = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','false')->sum('amount');
						$total += $amount_set + $amount;
					}

				}
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
					if ($activity->use_timesheet) {
						$total += EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->sum('set_amount');
					} else {
						$amount_set = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','true')->sum('set_amount');
						$amount = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','false')->sum('amount');
						$total += $amount_set + $amount;
					}

				}
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
					if ($activity->use_timesheet) {
						$total += EstimateLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->sum('set_amount');
					} else {
						$amount_set = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','true')->sum('set_amount');
						$amount = EstimateLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->where('isset','=','false')->sum('amount');
						$total += $amount_set + $amount;
					}

				}
			}
		}

		return $total * $project->hour_rate;
	}

	public static function conCalcLaborActivityTax1AmountTax($project) {
		return (SetEstimateEndresult::conCalcLaborActivityTax1Amount($project)/100)*21;
	}

	public static function conCalcLaborActivityTax2AmountTax($project) {
		return (SetEstimateEndresult::conCalcLaborActivityTax2Amount($project)/100)*6;
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
			}
		}

		return $total + ($total * ($project->profit_calc_contr_mat)/100);
	}

	public static function conCalcMaterialActivityTax1AmountTax($project) {
		return (SetEstimateEndresult::conCalcMaterialActivityTax1Amount($project)/100)*21;
	}

	public static function conCalcMaterialActivityTax2AmountTax($project) {
		return (SetEstimateEndresult::conCalcMaterialActivityTax2Amount($project)/100)*6;
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
			}
		}

		return $total + ($total * ($project->profit_calc_contr_equip)/100);
	}

	public static function conCalcEquipmentActivityTax1AmountTax($project) {
		return (SetEstimateEndresult::conCalcEquipmentActivityTax1Amount($project)/100)*21;
	}

	public static function conCalcEquipmentActivityTax2AmountTax($project) {
		return (SetEstimateEndresult::conCalcEquipmentActivityTax2Amount($project)/100)*6;
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
			}
		}

		return $total;
	}

	public static function subconCalcLaborActivityTax1AmountTax($project) {
		return (SetEstimateEndresult::subconCalcLaborActivityTax1Amount($project)/100)*21;
	}

	public static function subconCalcLaborActivityTax2AmountTax($project) {
		return (SetEstimateEndresult::subconCalcLaborActivityTax2Amount($project)/100)*6;
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
			}
		}

		return $total + ($total * ($project->profit_calc_subcontr_mat)/100);
	}

	public static function subconCalcMaterialActivityTax1AmountTax($project) {
		return (SetEstimateEndresult::subconCalcMaterialActivityTax1Amount($project)/100)*21;
	}

	public static function subconCalcMaterialActivityTax2AmountTax($project) {
		return (SetEstimateEndresult::subconCalcMaterialActivityTax2Amount($project)/100)*6;
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
			}
		}

		return $total + ($total * ($project->profit_calc_subcontr_equip)/100);
	}

	public static function subconCalcEquipmentActivityTax1AmountTax($project) {
		return (SetEstimateEndresult::subconCalcEquipmentActivityTax1Amount($project)/100)*21;
	}

	public static function subconCalcEquipmentActivityTax2AmountTax($project) {
		return (SetEstimateEndresult::subconCalcEquipmentActivityTax2Amount($project)/100)*6;
	}

	public static function totalContracting($project) {
		$total = 0;

		$total += SetEstimateEndresult::conCalcLaborActivityTax1Amount($project);
		$total += SetEstimateEndresult::conCalcLaborActivityTax2Amount($project);
		$total += SetEstimateEndresult::conCalcLaborActivityTax3Amount($project);

		$total += SetEstimateEndresult::conCalcMaterialActivityTax1Amount($project);
		$total += SetEstimateEndresult::conCalcMaterialActivityTax2Amount($project);
		$total += SetEstimateEndresult::conCalcMaterialActivityTax3Amount($project);

		$total += SetEstimateEndresult::conCalcEquipmentActivityTax1Amount($project);
		$total += SetEstimateEndresult::conCalcEquipmentActivityTax2Amount($project);
		$total += SetEstimateEndresult::conCalcEquipmentActivityTax3Amount($project);

		return $total;
	}

	public static function totalContractingTax($project) {
		$total = 0;

		$total += SetEstimateEndresult::conCalcLaborActivityTax1AmountTax($project);
		$total += SetEstimateEndresult::conCalcLaborActivityTax2AmountTax($project);

		$total += SetEstimateEndresult::conCalcMaterialActivityTax1AmountTax($project);
		$total += SetEstimateEndresult::conCalcMaterialActivityTax2AmountTax($project);

		$total += SetEstimateEndresult::conCalcEquipmentActivityTax1AmountTax($project);
		$total += SetEstimateEndresult::conCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalSubcontracting($project) {
		$total = 0;

		$total += SetEstimateEndresult::subconCalcLaborActivityTax1Amount($project);
		$total += SetEstimateEndresult::subconCalcLaborActivityTax2Amount($project);
		$total += SetEstimateEndresult::subconCalcLaborActivityTax3Amount($project);

		$total += SetEstimateEndresult::subconCalcMaterialActivityTax1Amount($project);
		$total += SetEstimateEndresult::subconCalcMaterialActivityTax2Amount($project);
		$total += SetEstimateEndresult::subconCalcMaterialActivityTax3Amount($project);

		$total += SetEstimateEndresult::subconCalcEquipmentActivityTax1Amount($project);
		$total += SetEstimateEndresult::subconCalcEquipmentActivityTax2Amount($project);
		$total += SetEstimateEndresult::subconCalcEquipmentActivityTax3Amount($project);

		return $total;
	}

	public static function totalSubcontractingTax($project) {
		$total = 0;

		$total += SetEstimateEndresult::subconCalcLaborActivityTax1AmountTax($project);
		$total += SetEstimateEndresult::subconCalcLaborActivityTax2AmountTax($project);

		$total += SetEstimateEndresult::subconCalcMaterialActivityTax1AmountTax($project);
		$total += SetEstimateEndresult::subconCalcMaterialActivityTax2AmountTax($project);

		$total += SetEstimateEndresult::subconCalcEquipmentActivityTax1AmountTax($project);
		$total += SetEstimateEndresult::subconCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalProject($project) {
		return SetEstimateEndresult::totalContracting($project) + SetEstimateEndresult::totalSubcontracting($project);
	}

	public static function totalContractingTax1($project) {
		$total = 0;

		$total += SetEstimateEndresult::conCalcLaborActivityTax1AmountTax($project);
		$total += SetEstimateEndresult::conCalcMaterialActivityTax1AmountTax($project);
		$total += SetEstimateEndresult::conCalcEquipmentActivityTax1AmountTax($project);

		return $total;
	}

	public static function totalContractingTax2($project) {
		$total = 0;

		$total += SetEstimateEndresult::conCalcLaborActivityTax2AmountTax($project);
		$total += SetEstimateEndresult::conCalcMaterialActivityTax2AmountTax($project);
		$total += SetEstimateEndresult::conCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalSubcontractingTax1($project) {
		$total = 0;

		$total += SetEstimateEndresult::subconCalcLaborActivityTax1AmountTax($project);
		$total += SetEstimateEndresult::subconCalcMaterialActivityTax1AmountTax($project);
		$total += SetEstimateEndresult::subconCalcEquipmentActivityTax1AmountTax($project);

		return $total;
	}

	public static function totalSubcontractingTax2($project) {
		$total = 0;

		$total += SetEstimateEndresult::subconCalcLaborActivityTax2AmountTax($project);
		$total += SetEstimateEndresult::subconCalcMaterialActivityTax2AmountTax($project);
		$total += SetEstimateEndresult::subconCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalProjectTax($project) {
		return SetEstimateEndresult::totalContractingTax($project) + SetEstimateEndresult::totalSubcontractingTax($project);
	}

	public static function superTotalProject($project) {
		return SetEstimateEndresult::totalProject($project) + SetEstimateEndresult::totalProjectTax($project);
	}
}
