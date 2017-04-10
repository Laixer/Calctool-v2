<?php

namespace CalculatieTool\Calculus;

use \CalculatieTool\Models\Chapter;
use \CalculatieTool\Models\Activity;
use \CalculatieTool\Models\Part;
use \CalculatieTool\Models\Tax;
use \CalculatieTool\Models\MoreLabor;
use \CalculatieTool\Models\MoreMaterial;
use \CalculatieTool\Models\MoreEquipment;
use \CalculatieTool\Models\Timesheet;

/*
 * Eindresultaat
 */
class MoreEndresult {

	public static function conCalcLaborActivityTax1($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				// $count = MoreLabor::where('activity_id','=', $activity->id)->whereNotNull('hour_id')->count('hour_id');
				// if (!$count)
				if (!$activity->use_timesheet)
					$total += MoreLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->sum('amount');
				else
					$total += MoreLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->sum('amount');
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
				// $count = MoreLabor::where('activity_id','=', $activity->id)->whereNotNull('hour_id')->count('hour_id');
				// if (!$count)
				if (!$activity->use_timesheet)
					$total += MoreLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->sum('amount');
				else
					$total += MoreLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->sum('amount');
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
				//$count = MoreLabor::where('activity_id','=', $activity->id)->whereNotNull('hour_id')->count('hour_id');
				//if (!$count)
				if (!$activity->use_timesheet)
					$total += MoreLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->sum('amount');
				else
					$total += MoreLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->sum('amount');
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
				// $count = MoreLabor::where('activity_id','=', $activity->id)->whereNotNull('hour_id')->count('hour_id');
				// if (!$count)
				if (!$activity->use_timesheet)
					 $rows = MoreLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->get();
				 else {
				  foreach(MoreLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->get() as $labor){
				  	$total += Timesheet::find($labor->hour_id)->register_hour * $project->hour_rate_more;
				  }
				continue;
				 }
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				// $count = MoreLabor::where('activity_id','=', $activity->id)->whereNotNull('hour_id')->count('hour_id');
				// if (!$count)
				if (!$activity->use_timesheet)
					 $rows = MoreLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->get();
				 else {
				  foreach(MoreLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->get() as $labor){
				  	$total += Timesheet::find($labor->hour_id)->register_hour * $project->hour_rate_more;
				  }
				continue;
				 }
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
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				// $count = MoreLabor::where('activity_id','=', $activity->id)->whereNotNull('hour_id')->count('hour_id');
				// if (!$count)
				if (!$activity->use_timesheet)
					 $rows = MoreLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->get();
				 else {
				  foreach(MoreLabor::where('activity_id','=',$activity->id)->whereNotNull('hour_id')->get() as $labor){
				  	$total += Timesheet::find($labor->hour_id)->register_hour * $project->hour_rate_more;
				  }
				continue;
				 }
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				
				}
			}
		}

		return $total;
	}

	public static function conCalcLaborActivityTax1AmountTax($project) {
		return (MoreEndresult::conCalcLaborActivityTax1Amount($project)/100)*21;
	}

	public static function conCalcLaborActivityTax2AmountTax($project) {
		return (MoreEndresult::conCalcLaborActivityTax2Amount($project)/100)*6;
	}

	public static function conCalcMaterialActivityTax1Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				$rows = MoreMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_more_contr_mat)/100);
	}

	public static function conCalcMaterialActivityTax2Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','6')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				$rows = MoreMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_more_contr_mat)/100);
	}

	public static function conCalcMaterialActivityTax3Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','0')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				$rows = MoreMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_more_contr_mat)/100);
	}

	public static function conCalcMaterialActivityTax1AmountTax($project) {
		return (MoreEndresult::conCalcMaterialActivityTax1Amount($project)/100)*21;
	}

	public static function conCalcMaterialActivityTax2AmountTax($project) {
		return (MoreEndresult::conCalcMaterialActivityTax2Amount($project)/100)*6;
	}

	public static function conCalcEquipmentActivityTax1Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				$rows = MoreEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_more_contr_equip)/100);
	}

	public static function conCalcEquipmentActivityTax2Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','6')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				$rows = MoreEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_more_contr_equip)/100);
	}

	public static function conCalcEquipmentActivityTax3Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','contracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','0')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				$rows = MoreEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_more_contr_equip)/100);
	}

	public static function conCalcEquipmentActivityTax1AmountTax($project) {
		return (MoreEndresult::conCalcEquipmentActivityTax1Amount($project)/100)*21;
	}

	public static function conCalcEquipmentActivityTax2AmountTax($project) {
		return (MoreEndresult::conCalcEquipmentActivityTax2Amount($project)/100)*6;
	}

	public static function subconCalcLaborActivityTax1($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_labor_id','=',$tax_id)->get() as $activity)
			{
				$total += MoreLabor::where('activity_id','=',$activity->id)->sum('amount');
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
				$total += MoreLabor::where('activity_id','=',$activity->id)->sum('amount');
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
				$total += MoreLabor::where('activity_id','=',$activity->id)->sum('amount');
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
				$rows = MoreLabor::where('activity_id','=',$activity->id)->get();
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
				$rows = MoreLabor::where('activity_id','=',$activity->id)->get();
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
				$rows = MoreLabor::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total;
	}

	public static function subconCalcLaborActivityTax1AmountTax($project) {
		return (MoreEndresult::subconCalcLaborActivityTax1Amount($project)/100)*21;
	}

	public static function subconCalcLaborActivityTax2AmountTax($project) {
		return (MoreEndresult::subconCalcLaborActivityTax2Amount($project)/100)*6;
	}

	public static function subconCalcMaterialActivityTax1Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				$rows = MoreMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_more_subcontr_mat)/100);
	}

	public static function subconCalcMaterialActivityTax2Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','6')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				$rows = MoreMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_more_subcontr_mat)/100);
	}

	public static function subconCalcMaterialActivityTax3Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','0')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_material_id','=',$tax_id)->get() as $activity)
			{
				$rows = MoreMaterial::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_more_subcontr_mat)/100);
	}

	public static function subconCalcMaterialActivityTax1AmountTax($project) {
		return (MoreEndresult::subconCalcMaterialActivityTax1Amount($project)/100)*21;
	}

	public static function subconCalcMaterialActivityTax2AmountTax($project) {
		return (MoreEndresult::subconCalcMaterialActivityTax2Amount($project)/100)*6;
	}

	public static function subconCalcEquipmentActivityTax1Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','21')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				$rows = MoreEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_more_subcontr_equip)/100);
	}

	public static function subconCalcEquipmentActivityTax2Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','6')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				$rows = MoreEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_more_subcontr_equip)/100);
	}

	public static function subconCalcEquipmentActivityTax3Amount($project) {
		$total = 0;
		$part_id = Part::where('part_name','=','subcontracting')->first()->id;
		$tax_id = Tax::where('tax_rate','=','0')->first()->id;

		foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
		{
			foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',$part_id)->where('tax_equipment_id','=',$tax_id)->get() as $activity)
			{
				$rows = MoreEquipment::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					$total += $row->rate * $row->amount;
				}
			}
		}

		return $total + ($total * ($project->profit_more_subcontr_equip)/100);
	}

	public static function subconCalcEquipmentActivityTax1AmountTax($project) {
		return (MoreEndresult::subconCalcEquipmentActivityTax1Amount($project)/100)*21;
	}

	public static function subconCalcEquipmentActivityTax2AmountTax($project) {
		return (MoreEndresult::subconCalcEquipmentActivityTax2Amount($project)/100)*6;
	}

	public static function totalContracting($project) {
		$total = 0;

		$total += MoreEndresult::conCalcLaborActivityTax1Amount($project);
		$total += MoreEndresult::conCalcLaborActivityTax2Amount($project);
		$total += MoreEndresult::conCalcLaborActivityTax3Amount($project);

		$total += MoreEndresult::conCalcMaterialActivityTax1Amount($project);
		$total += MoreEndresult::conCalcMaterialActivityTax2Amount($project);
		$total += MoreEndresult::conCalcMaterialActivityTax3Amount($project);

		$total += MoreEndresult::conCalcEquipmentActivityTax1Amount($project);
		$total += MoreEndresult::conCalcEquipmentActivityTax2Amount($project);
		$total += MoreEndresult::conCalcEquipmentActivityTax3Amount($project);

		return $total;
	}

	public static function totalContractingTax($project) {
		$total = 0;

		$total += MoreEndresult::conCalcLaborActivityTax1AmountTax($project);
		$total += MoreEndresult::conCalcLaborActivityTax2AmountTax($project);

		$total += MoreEndresult::conCalcMaterialActivityTax1AmountTax($project);
		$total += MoreEndresult::conCalcMaterialActivityTax2AmountTax($project);

		$total += MoreEndresult::conCalcEquipmentActivityTax1AmountTax($project);
		$total += MoreEndresult::conCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalSubcontracting($project) {
		$total = 0;

		$total += MoreEndresult::subconCalcLaborActivityTax1Amount($project);
		$total += MoreEndresult::subconCalcLaborActivityTax2Amount($project);
		$total += MoreEndresult::subconCalcLaborActivityTax3Amount($project);

		$total += MoreEndresult::subconCalcMaterialActivityTax1Amount($project);
		$total += MoreEndresult::subconCalcMaterialActivityTax2Amount($project);
		$total += MoreEndresult::subconCalcMaterialActivityTax3Amount($project);

		$total += MoreEndresult::subconCalcEquipmentActivityTax1Amount($project);
		$total += MoreEndresult::subconCalcEquipmentActivityTax2Amount($project);
		$total += MoreEndresult::subconCalcEquipmentActivityTax3Amount($project);

		return $total;
	}

	public static function totalSubcontractingTax($project) {
		$total = 0;

		$total += MoreEndresult::subconCalcLaborActivityTax1AmountTax($project);
		$total += MoreEndresult::subconCalcLaborActivityTax2AmountTax($project);

		$total += MoreEndresult::subconCalcMaterialActivityTax1AmountTax($project);
		$total += MoreEndresult::subconCalcMaterialActivityTax2AmountTax($project);

		$total += MoreEndresult::subconCalcEquipmentActivityTax1AmountTax($project);
		$total += MoreEndresult::subconCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalProject($project) {
		return MoreEndresult::totalContracting($project) + MoreEndresult::totalSubcontracting($project);
	}

	public static function totalContractingTax1($project) {
		$total = 0;

		$total += MoreEndresult::conCalcLaborActivityTax1AmountTax($project);
		$total += MoreEndresult::conCalcMaterialActivityTax1AmountTax($project);
		$total += MoreEndresult::conCalcEquipmentActivityTax1AmountTax($project);

		return $total;
	}

	public static function totalContractingTax2($project) {
		$total = 0;

		$total += MoreEndresult::conCalcLaborActivityTax2AmountTax($project);
		$total += MoreEndresult::conCalcMaterialActivityTax2AmountTax($project);
		$total += MoreEndresult::conCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalSubcontractingTax1($project) {
		$total = 0;

		$total += MoreEndresult::subconCalcLaborActivityTax1AmountTax($project);
		$total += MoreEndresult::subconCalcMaterialActivityTax1AmountTax($project);
		$total += MoreEndresult::subconCalcEquipmentActivityTax1AmountTax($project);

		return $total;
	}

	public static function totalSubcontractingTax2($project) {
		$total = 0;

		$total += MoreEndresult::subconCalcLaborActivityTax2AmountTax($project);
		$total += MoreEndresult::subconCalcMaterialActivityTax2AmountTax($project);
		$total += MoreEndresult::subconCalcEquipmentActivityTax2AmountTax($project);

		return $total;
	}

	public static function totalProjectTax($project) {
		return MoreEndresult::totalContractingTax($project) + MoreEndresult::totalSubcontractingTax($project);
	}

	public static function superTotalProject($project) {
		return MoreEndresult::totalProject($project) + MoreEndresult::totalProjectTax($project);
	}
}
