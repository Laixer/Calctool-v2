<?php

namespace Calctool\Calculus;

use \Calctool\Models\Invoice;

/*
 * Eindresultaat
 */
class InvoiceTerm {

	public static function conLaborBalanceTax1($project) {
		$estim = SetEstimateCalculationEndresult::conCalcLaborActivityTax1Amount($project);
		$more = MoreEndresult::conCalcLaborActivityTax1Amount($project);
		$less = LessEndresult::conCalcLaborActivityTax1Amount($project);

		return $estim + $more + $less;
	}

	public static function conLaborBalanceTax2($project) {
		$estim = SetEstimateCalculationEndresult::conCalcLaborActivityTax2Amount($project);
		$more = MoreEndresult::conCalcLaborActivityTax2Amount($project);
		$less = LessEndresult::conCalcLaborActivityTax2Amount($project);

		return $estim + $more + $less;
	}

	public static function conLaborBalanceTax3($project) {
		$estim = SetEstimateCalculationEndresult::conCalcLaborActivityTax3Amount($project);
		$more = MoreEndresult::conCalcLaborActivityTax3Amount($project);
		$less = LessEndresult::conCalcLaborActivityTax3Amount($project);

		return $estim + $more + $less;
	}

	public static function conMaterialBalanceTax1($project) {
		$estim = SetEstimateCalculationEndresult::conCalcMaterialActivityTax1Amount($project);
		$more = MoreEndresult::conCalcMaterialActivityTax1Amount($project);
		$less = LessEndresult::conCalcMaterialActivityTax1Amount($project);

		return $estim + $more + $less;
	}

	public static function conMaterialBalanceTax2($project) {
		$estim = SetEstimateCalculationEndresult::conCalcMaterialActivityTax2Amount($project);
		$more = MoreEndresult::conCalcMaterialActivityTax2Amount($project);
		$less = LessEndresult::conCalcMaterialActivityTax2Amount($project);

		return $estim + $more + $less;
	}

	public static function conMaterialBalanceTax3($project) {
		$estim = SetEstimateCalculationEndresult::conCalcMaterialActivityTax3Amount($project);
		$more = MoreEndresult::conCalcMaterialActivityTax3Amount($project);
		$less = LessEndresult::conCalcMaterialActivityTax3Amount($project);

		return $estim + $more + $less;
	}

	public static function conEquipmentBalanceTax1($project) {
		$estim = SetEstimateCalculationEndresult::conCalcEquipmentActivityTax1Amount($project);
		$more = MoreEndresult::conCalcEquipmentActivityTax1Amount($project);
		$less = LessEndresult::conCalcEquipmentActivityTax1Amount($project);

		return $estim + $more + $less;
	}

	public static function conEquipmentBalanceTax2($project) {
		$estim = SetEstimateCalculationEndresult::conCalcEquipmentActivityTax2Amount($project);
		$more = MoreEndresult::conCalcEquipmentActivityTax2Amount($project);
		$less = LessEndresult::conCalcEquipmentActivityTax2Amount($project);

		return $estim + $more + $less;
	}

	public static function conEquipmentBalanceTax3($project) {
		$estim = SetEstimateCalculationEndresult::conCalcEquipmentActivityTax3Amount($project);
		$more = MoreEndresult::conCalcEquipmentActivityTax3Amount($project);
		$less = LessEndresult::conCalcEquipmentActivityTax3Amount($project);

		return $estim + $more + $less;
	}

	public static function subconLaborBalanceTax1($project) {
		$estim = SetEstimateCalculationEndresult::subconCalcLaborActivityTax1Amount($project);
		$more = MoreEndresult::subconCalcLaborActivityTax1Amount($project);
		$less = LessEndresult::subconCalcLaborActivityTax1Amount($project);

		return $estim + $more + $less;
	}

	public static function subconLaborBalanceTax2($project) {
		$estim = SetEstimateCalculationEndresult::subconCalcLaborActivityTax2Amount($project);
		$more = MoreEndresult::subconCalcLaborActivityTax2Amount($project);
		$less = LessEndresult::subconCalcLaborActivityTax2Amount($project);

		return $estim + $more + $less;
	}

	public static function subconLaborBalanceTax3($project) {
		$estim = SetEstimateCalculationEndresult::subconCalcLaborActivityTax3Amount($project);
		$more = MoreEndresult::subconCalcLaborActivityTax3Amount($project);
		$less = LessEndresult::subconCalcLaborActivityTax3Amount($project);

		return $estim + $more + $less;
	}

	public static function subconMaterialBalanceTax1($project) {
		$estim = SetEstimateCalculationEndresult::subconCalcMaterialActivityTax1Amount($project);
		$more = MoreEndresult::subconCalcMaterialActivityTax1Amount($project);
		$less = LessEndresult::subconCalcMaterialActivityTax1Amount($project);

		return $estim + $more + $less;
	}

	public static function subconMaterialBalanceTax2($project) {
		$estim = SetEstimateCalculationEndresult::subconCalcMaterialActivityTax2Amount($project);
		$more = MoreEndresult::subconCalcMaterialActivityTax2Amount($project);
		$less = LessEndresult::subconCalcMaterialActivityTax2Amount($project);

		return $estim + $more + $less;
	}

	public static function subconMaterialBalanceTax3($project) {
		$estim = SetEstimateCalculationEndresult::subconCalcMaterialActivityTax3Amount($project);
		$more = MoreEndresult::subconCalcMaterialActivityTax3Amount($project);
		$less = LessEndresult::subconCalcMaterialActivityTax3Amount($project);

		return $estim + $more + $less;
	}

	public static function subconEquipmentBalanceTax1($project) {
		$estim = SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax1Amount($project);
		$more = MoreEndresult::subconCalcEquipmentActivityTax1Amount($project);
		$less = LessEndresult::subconCalcEquipmentActivityTax1Amount($project);

		return $estim + $more + $less;
	}

	public static function subconEquipmentBalanceTax2($project) {
		$estim = SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax2Amount($project);
		$more = MoreEndresult::subconCalcEquipmentActivityTax2Amount($project);
		$less = LessEndresult::subconCalcEquipmentActivityTax2Amount($project);

		return $estim + $more + $less;
	}

	public static function subconEquipmentBalanceTax3($project) {
		$estim = SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax3Amount($project);
		$more = MoreEndresult::subconCalcEquipmentActivityTax3Amount($project);
		$less = LessEndresult::subconCalcEquipmentActivityTax3Amount($project);

		return $estim + $more + $less;
	}

	public static function totalContracting($project) {
		$total = 0;

		$total += InvoiceTerm::conLaborBalanceTax1($project);
		$total += InvoiceTerm::conLaborBalanceTax2($project);
		$total += InvoiceTerm::conLaborBalanceTax3($project);

		$total += InvoiceTerm::conMaterialBalanceTax1($project);
		$total += InvoiceTerm::conMaterialBalanceTax2($project);
		$total += InvoiceTerm::conMaterialBalanceTax3($project);

		$total += InvoiceTerm::conEquipmentBalanceTax1($project);
		$total += InvoiceTerm::conEquipmentBalanceTax2($project);
		$total += InvoiceTerm::conEquipmentBalanceTax3($project);

		return $total;
	}

	public static function totalSubcontracting($project) {
		$total = 0;

		$total += InvoiceTerm::subconLaborBalanceTax1($project);
		$total += InvoiceTerm::subconLaborBalanceTax2($project);
		$total += InvoiceTerm::subconLaborBalanceTax3($project);

		$total += InvoiceTerm::subconMaterialBalanceTax1($project);
		$total += InvoiceTerm::subconMaterialBalanceTax2($project);
		$total += InvoiceTerm::subconMaterialBalanceTax3($project);

		$total += InvoiceTerm::subconEquipmentBalanceTax1($project);
		$total += InvoiceTerm::subconEquipmentBalanceTax2($project);
		$total += InvoiceTerm::subconEquipmentBalanceTax3($project);

		return $total;
	}

	public static function totalProject($project) {
		return ResultEndresult::totalContracting($project) + ResultEndresult::totalSubcontracting($project);
	}

	public static function totalTax1($project) {
		$total = 0;

		$total += InvoiceTerm::conLaborBalanceTax1($project);
		$total += InvoiceTerm::conMaterialBalanceTax1($project);
		$total += InvoiceTerm::conEquipmentBalanceTax1($project);

		$total += InvoiceTerm::subconLaborBalanceTax1($project);
		$total += InvoiceTerm::subconMaterialBalanceTax1($project);
		$total += InvoiceTerm::subconEquipmentBalanceTax1($project);

		return $total;
	}

	public static function totalTax2($project) {
		$total = 0;

		$total += InvoiceTerm::conLaborBalanceTax2($project);
		$total += InvoiceTerm::conMaterialBalanceTax2($project);
		$total += InvoiceTerm::conEquipmentBalanceTax2($project);

		$total += InvoiceTerm::subconLaborBalanceTax2($project);
		$total += InvoiceTerm::subconMaterialBalanceTax2($project);
		$total += InvoiceTerm::subconEquipmentBalanceTax2($project);

		return $total;
	}

	public static function totalTax3($project) {
		$total = 0;

		$total += InvoiceTerm::conLaborBalanceTax3($project);
		$total += InvoiceTerm::conMaterialBalanceTax3($project);
		$total += InvoiceTerm::conEquipmentBalanceTax3($project);

		$total += InvoiceTerm::subconLaborBalanceTax3($project);
		$total += InvoiceTerm::subconMaterialBalanceTax3($project);
		$total += InvoiceTerm::subconEquipmentBalanceTax3($project);

		return $total;
	}

	public static function totalProjectBalance($project, $invoice) {
		$total = 0;

		$total += (InvoiceTerm::totalTax1($project) - Invoice::where('offer_id','=',$invoice->offer_id)->where('priority','<',$invoice->priority)->where('isclose','=',false)->sum('rest_21'));
		$total += (InvoiceTerm::totalTax2($project) - Invoice::where('offer_id','=',$invoice->offer_id)->where('priority','<',$invoice->priority)->where('isclose','=',false)->sum('rest_6'));
		$total += (InvoiceTerm::totalTax3($project) - Invoice::where('offer_id','=',$invoice->offer_id)->where('priority','<',$invoice->priority)->where('isclose','=',false)->sum('rest_0'));

		return $total;
	}

	public static function partTax1($project, $invoice) {
		return (InvoiceTerm::totalTax1($project) - Invoice::where('offer_id','=',$invoice->offer_id)->where('priority','<',$invoice->priority)->where('isclose','=',false)->sum('rest_21')) / InvoiceTerm::totalProjectBalance($project, $invoice);
	}

	public static function partTax2($project, $invoice) {
		return (InvoiceTerm::totalTax2($project) - Invoice::where('offer_id','=',$invoice->offer_id)->where('priority','<',$invoice->priority)->where('isclose','=',false)->sum('rest_6')) / InvoiceTerm::totalProjectBalance($project, $invoice);
	}

	public static function partTax3($project, $invoice) {
		return (InvoiceTerm::totalTax3($project) - Invoice::where('offer_id','=',$invoice->offer_id)->where('priority','<',$invoice->priority)->where('isclose','=',false)->sum('rest_0')) / InvoiceTerm::totalProjectBalance($project, $invoice);
	}
}
