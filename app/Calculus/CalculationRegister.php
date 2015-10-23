<?php

namespace Calctool\Calculus;

use \Calctool\Models\CalculationMaterial;
use \Calctool\Models\CalculationEquipment;
use \Calctool\Models\EstimateMaterial;
use \Calctool\Models\EstimateEquipment;

class CalculationRegister {

/*Calculation labor*/
	public static function calcLaborTotal($rate, $amount) {
		return $rate * $amount;
	}

/*Calculation Material*/
	public static function calcMaterialTotal($activity) {
		$total = 0;

		$rows = CalculationMaterial::where('activity_id', '=', $activity)->get();
		foreach ($rows as $row)
		{
			$total += CalculationRegister::calcLaborTotal($row->rate, $row->amount);
		}

		return $total;
	}

/*Calculation Material Profit*/
	public static function calcMaterialTotalProfit($activity, $profit) {
		$total = CalculationRegister::calcMaterialTotal($activity);

		return (1+($profit/100))*$total;
	}

/*Calculation Equipment*/
	public static function calcEquipmentTotal($activity) {
		$total = 0;

		$rows = CalculationEquipment::where('activity_id', '=', $activity)->get();
		foreach ($rows as $row)
		{
			$total += CalculationRegister::calcLaborTotal($row->rate, $row->amount);
		}

		return $total;
	}

/*Calculation Equipment Profit*/
	public static function calcEquipmentTotalProfit($activity, $profit) {
		$total = CalculationRegister::calcEquipmentTotal($activity);

		return (1+($profit/100))*$total;
	}

/*Calculation Estimate labor*/
	public static function estimLaborTotal($rate, $amount) {
		return $rate * $amount;
	}

/*Calculation Estimate Material*/
	public static function estimMaterialTotal($activity) {
		$total = 0;

		$rows = EstimateMaterial::where('activity_id', '=', $activity)->get();
		foreach ($rows as $row)
		{
			$total += CalculationRegister::estimLaborTotal($row->rate, $row->amount);
		}

		return $total;
	}

/*Calculation Estimate Material Profit*/
	public static function estimMaterialTotalProfit($activity, $profit) {
		$total = CalculationRegister::estimMaterialTotal($activity);

		return (1+($profit/100))*$total;
	}

/*Calculation Estimate Equipment*/
	public static function estimEquipmentTotal($activity) {
		$total = 0;

		$rows = EstimateEquipment::where('activity_id', '=', $activity)->get();
		foreach ($rows as $row)
		{
			$total += CalculationRegister::estimLaborTotal($row->rate, $row->amount);
		}

		return $total;
	}

/*Calculation Estimate Equipment Profit*/
	public static function estimEquipmentTotalProfit($activity, $profit) {
		$total = CalculationRegister::estimEquipmentTotal($activity);

		return (1+($profit/100))*$total;
	}

}
