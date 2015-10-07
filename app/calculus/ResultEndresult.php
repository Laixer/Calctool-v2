<?php

/*
 * Eindresultaat
 */
class ResultEndresult {

	public static function conLaborBalanceTax1($project) {
		$estim = CalculationEndresult::conCalcLaborActivityTax1Amount($project);
		$more = MoreEndresult::conCalcLaborActivityTax1Amount($project);
		$less = LessEndresult::conCalcLaborActivityTax1Amount($project);

		return $estim + $more + $less;
	}

	public static function conLaborBalanceTax2($project) {
		$estim = CalculationEndresult::conCalcLaborActivityTax2Amount($project);
		$more = MoreEndresult::conCalcLaborActivityTax2Amount($project);
		$less = LessEndresult::conCalcLaborActivityTax2Amount($project);

		return $estim + $more + $less;
	}

	public static function conLaborBalanceTax3($project) {
		$estim = CalculationEndresult::conCalcLaborActivityTax3Amount($project);
		$more = MoreEndresult::conCalcLaborActivityTax3Amount($project);
		$less = LessEndresult::conCalcLaborActivityTax3Amount($project);

		return $estim + $more + $less;
	}

	public static function conMaterialBalanceTax1($project) {
		$estim = CalculationEndresult::conCalcMaterialActivityTax1Amount($project);
		$more = MoreEndresult::conCalcMaterialActivityTax1Amount($project);
		$less = LessEndresult::conCalcMaterialActivityTax1Amount($project);

		return $estim + $more + $less;
	}

	public static function conMaterialBalanceTax2($project) {
		$estim = CalculationEndresult::conCalcMaterialActivityTax2Amount($project);
		$more = MoreEndresult::conCalcMaterialActivityTax2Amount($project);
		$less = LessEndresult::conCalcMaterialActivityTax2Amount($project);

		return $estim + $more + $less;
	}

	public static function conMaterialBalanceTax3($project) {
		$estim = CalculationEndresult::conCalcMaterialActivityTax3Amount($project);
		$more = MoreEndresult::conCalcMaterialActivityTax3Amount($project);
		$less = LessEndresult::conCalcMaterialActivityTax3Amount($project);

		return $estim + $more + $less;
	}

	public static function conEquipmentBalanceTax1($project) {
		$estim = CalculationEndresult::conCalcEquipmentActivityTax1Amount($project);
		$more = MoreEndresult::conCalcEquipmentActivityTax1Amount($project);
		$less = LessEndresult::conCalcEquipmentActivityTax1Amount($project);

		return $estim + $more + $less;
	}

	public static function conEquipmentBalanceTax2($project) {
		$estim = CalculationEndresult::conCalcEquipmentActivityTax2Amount($project);
		$more = MoreEndresult::conCalcEquipmentActivityTax2Amount($project);
		$less = LessEndresult::conCalcEquipmentActivityTax2Amount($project);

		return $estim + $more + $less;
	}

	public static function conEquipmentBalanceTax3($project) {
		$estim = CalculationEndresult::conCalcEquipmentActivityTax3Amount($project);
		$more = MoreEndresult::conCalcEquipmentActivityTax3Amount($project);
		$less = LessEndresult::conCalcEquipmentActivityTax3Amount($project);

		return $estim + $more + $less;
	}

	public static function totalContracting($project) {
		$total = 0;

		$total += ResultEndresult::conLaborBalanceTax1($project);
		$total += ResultEndresult::conLaborBalanceTax2($project);
		$total += ResultEndresult::conLaborBalanceTax3($project);

		$total += ResultEndresult::conMaterialBalanceTax1($project);
		$total += ResultEndresult::conMaterialBalanceTax2($project);
		$total += ResultEndresult::conMaterialBalanceTax3($project);

		$total += ResultEndresult::conEquipmentBalanceTax1($project);
		$total += ResultEndresult::conEquipmentBalanceTax2($project);
		$total += ResultEndresult::conEquipmentBalanceTax3($project);

		return $total;
	}

	public static function totalSubcontracting($project) {
		$total = 0;

		$total += ResultEndresult::subconLaborBalanceTax1($project);
		$total += ResultEndresult::subconLaborBalanceTax2($project);
		$total += ResultEndresult::subconLaborBalanceTax3($project);

		$total += ResultEndresult::subconMaterialBalanceTax1($project);
		$total += ResultEndresult::subconMaterialBalanceTax2($project);
		$total += ResultEndresult::subconMaterialBalanceTax3($project);

		$total += ResultEndresult::subconEquipmentBalanceTax1($project);
		$total += ResultEndresult::subconEquipmentBalanceTax2($project);
		$total += ResultEndresult::subconEquipmentBalanceTax3($project);

		return $total;
	}

	public static function subconLaborBalanceTax1($project) {
		$estim = CalculationEndresult::subconCalcLaborActivityTax1Amount($project);
		$more = MoreEndresult::subconCalcLaborActivityTax1Amount($project);
		$less = LessEndresult::subconCalcLaborActivityTax1Amount($project);

		return $estim + $more + $less;
	}

	public static function subconLaborBalanceTax2($project) {
		$estim = CalculationEndresult::subconCalcLaborActivityTax2Amount($project);
		$more = MoreEndresult::subconCalcLaborActivityTax2Amount($project);
		$less = LessEndresult::subconCalcLaborActivityTax2Amount($project);

		return $estim + $more + $less;
	}

	public static function subconLaborBalanceTax3($project) {
		$estim = CalculationEndresult::subconCalcLaborActivityTax3Amount($project);
		$more = MoreEndresult::subconCalcLaborActivityTax3Amount($project);
		$less = LessEndresult::subconCalcLaborActivityTax3Amount($project);

		return $estim + $more + $less;
	}

	public static function subconMaterialBalanceTax1($project) {
		$estim = CalculationEndresult::subconCalcMaterialActivityTax1Amount($project);
		$more = MoreEndresult::subconCalcMaterialActivityTax1Amount($project);
		$less = LessEndresult::subconCalcMaterialActivityTax1Amount($project);

		return $estim + $more + $less;
	}

	public static function subconMaterialBalanceTax2($project) {
		$estim = CalculationEndresult::subconCalcMaterialActivityTax2Amount($project);
		$more = MoreEndresult::subconCalcMaterialActivityTax2Amount($project);
		$less = LessEndresult::subconCalcMaterialActivityTax2Amount($project);

		return $estim + $more + $less;
	}

	public static function subconMaterialBalanceTax3($project) {
		$estim = CalculationEndresult::subconCalcMaterialActivityTax3Amount($project);
		$more = MoreEndresult::subconCalcMaterialActivityTax3Amount($project);
		$less = LessEndresult::subconCalcMaterialActivityTax3Amount($project);

		return $estim + $more + $less;
	}

	public static function subconEquipmentBalanceTax1($project) {
		$estim = CalculationEndresult::subconCalcEquipmentActivityTax1Amount($project);
		$more = MoreEndresult::subconCalcEquipmentActivityTax1Amount($project);
		$less = LessEndresult::subconCalcEquipmentActivityTax1Amount($project);

		return $estim + $more + $less;
	}

	public static function subconEquipmentBalanceTax2($project) {
		$estim = CalculationEndresult::subconCalcEquipmentActivityTax2Amount($project);
		$more = MoreEndresult::subconCalcEquipmentActivityTax2Amount($project);
		$less = LessEndresult::subconCalcEquipmentActivityTax2Amount($project);

		return $estim + $more + $less;
	}

	public static function subconEquipmentBalanceTax3($project) {
		$estim = CalculationEndresult::subconCalcEquipmentActivityTax3Amount($project);
		$more = MoreEndresult::subconCalcEquipmentActivityTax3Amount($project);
		$less = LessEndresult::subconCalcEquipmentActivityTax3Amount($project);

		return $estim + $more + $less;
	}

	public static function conLaborBalanceTax1AmountTax($project) {
		return (ResultEndresult::conLaborBalanceTax1($project)/100)*21;
	}

	public static function conLaborBalanceTax2AmountTax($project) {
		return (ResultEndresult::conLaborBalanceTax2($project)/100)*6;
	}

	public static function conMaterialBalanceTax1AmountTax($project) {
		return (ResultEndresult::conMaterialBalanceTax1($project)/100)*21;
	}

	public static function conMaterialBalanceTax2AmountTax($project) {
		return (ResultEndresult::conMaterialBalanceTax2($project)/100)*6;
	}

	public static function conEquipmentBalanceTax1AmountTax($project) {
		return (ResultEndresult::conEquipmentBalanceTax1($project)/100)*21;
	}

	public static function conEquipmentBalanceTax2AmountTax($project) {
		return (ResultEndresult::conEquipmentBalanceTax2($project)/100)*6;
	}

	public static function subconLaborBalanceTax1AmountTax($project) {
		return (ResultEndresult::subconLaborBalanceTax1($project)/100)*21;
	}

	public static function subconLaborBalanceTax2AmountTax($project) {
		return (ResultEndresult::subconLaborBalanceTax2($project)/100)*6;
	}

	public static function subconMaterialBalanceTax1AmountTax($project) {
		return (ResultEndresult::subconMaterialBalanceTax1($project)/100)*21;
	}

	public static function subconMaterialBalanceTax2AmountTax($project) {
		return (ResultEndresult::subconMaterialBalanceTax2($project)/100)*6;
	}

	public static function subconEquipmentBalanceTax1AmountTax($project) {
		return (ResultEndresult::subconEquipmentBalanceTax1($project)/100)*21;
	}

	public static function subconEquipmentBalanceTax2AmountTax($project) {
		return (ResultEndresult::subconEquipmentBalanceTax2($project)/100)*6;
	}

	public static function totalContractingTax($project) {
		$total = 0;

		$total += ResultEndresult::conLaborBalanceTax1AmountTax($project);
		$total += ResultEndresult::conLaborBalanceTax2AmountTax($project);

		$total += ResultEndresult::conMaterialBalanceTax1AmountTax($project);
		$total += ResultEndresult::conMaterialBalanceTax2AmountTax($project);

		$total += ResultEndresult::conEquipmentBalanceTax1AmountTax($project);
		$total += ResultEndresult::conEquipmentBalanceTax2AmountTax($project);

		return $total;
	}

	public static function totalSubcontractingTax($project) {
		$total = 0;

		$total += ResultEndresult::subconLaborBalanceTax1AmountTax($project);
		$total += ResultEndresult::subconLaborBalanceTax2AmountTax($project);

		$total += ResultEndresult::subconMaterialBalanceTax1AmountTax($project);
		$total += ResultEndresult::subconMaterialBalanceTax2AmountTax($project);

		$total += ResultEndresult::subconEquipmentBalanceTax1AmountTax($project);
		$total += ResultEndresult::subconEquipmentBalanceTax2AmountTax($project);

		return $total;
	}

	public static function totalProject($project) {
		return ResultEndresult::totalContracting($project) + ResultEndresult::totalSubcontracting($project);
	}

	public static function totalContractingTax1($project) {
		return ResultEndresult::conLaborBalanceTax1AmountTax($project) + ResultEndresult::conMaterialBalanceTax1AmountTax($project) + ResultEndresult::conEquipmentBalanceTax1AmountTax($project);
	}

	public static function totalContractingTax2($project) {
		return ResultEndresult::conLaborBalanceTax2AmountTax($project) + ResultEndresult::conMaterialBalanceTax2AmountTax($project) + ResultEndresult::conEquipmentBalanceTax2AmountTax($project);
	}

	public static function totalSubcontractingTax1($project) {
		return ResultEndresult::subconLaborBalanceTax1AmountTax($project) + ResultEndresult::subconMaterialBalanceTax1AmountTax($project) + ResultEndresult::subconEquipmentBalanceTax1AmountTax($project);
	}

	public static function totalSubcontractingTax2($project) {
		return ResultEndresult::subconLaborBalanceTax2AmountTax($project) + ResultEndresult::subconMaterialBalanceTax2AmountTax($project) + ResultEndresult::subconEquipmentBalanceTax2AmountTax($project);
	}

	public static function totalProjectTax($project) {
		return ResultEndresult::totalContractingTax1($project) + ResultEndresult::totalContractingTax2($project) + ResultEndresult::totalSubcontractingTax1($project) + ResultEndresult::totalSubcontractingTax2($project);
	}

	public static function superTotalProject($project) {
		return ResultEndresult::totalProject($project) + ResultEndresult::totalProjectTax($project);
	}

	public static function totalTimesheet($project) {
		$total = 0;

		$chapters = Chapter::where('project_id','=', $project->id)->get();
		foreach ($chapters as $chapter)
		{
			$activities = Activity::where('chapter_id','=', $chapter->id)->get();
			foreach ($activities as $activity)
			{
				$hour_calc = Timesheet::where('activity_id','=', $activity->id)->where('timesheet_kind_id','=',TimesheetKind::where('kind_name','=','aanneming')->first()->id)->sum('register_hour');
				$total += $project->hour_rate * $hour_calc;

				$hour_estim = Timesheet::where('activity_id','=', $activity->id)->where('timesheet_kind_id','=',TimesheetKind::where('kind_name','=','stelpost')->first()->id)->sum('register_hour');
				$total += $project->hour_rate * $hour_estim;

				$hour_more = Timesheet::where('activity_id','=', $activity->id)->where('timesheet_kind_id','=',TimesheetKind::where('kind_name','=','meerwerk')->first()->id)->sum('register_hour');
				$total += $project->hour_rate_more * $hour_more;
			}
		}

		return $total;
	}

	public static function totalContractingPurchase($project) {
		return Purchase::where('project_id','=',$project->id)->where('kind_id','=',PurchaseKind::where('kind_name','=','aanneming')->first()->id)->sum('amount');
	}

	public static function totalSubcontractingPurchase($project) {
		return Purchase::where('project_id','=',$project->id)->where('kind_id','=',PurchaseKind::where('kind_name','=','onderaanneming')->first()->id)->sum('amount');
	}

	public static function totalContractingBudget($project) {
		return ResultEndresult::totalContracting($project) - ResultEndresult::totalTimesheet($project) - ResultEndresult::totalContractingPurchase($project);
	}

	public static function totalSubcontractingBudget($project) {
		return ResultEndresult::totalSubcontracting($project) - ResultEndresult::totalSubcontractingPurchase($project);
	}
}
