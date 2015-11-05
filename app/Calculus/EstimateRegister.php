<?php

namespace Calctool\Calculus;

use \Calctool\Models\EstimateMaterial;
use \Calctool\Models\EstimateEquipment;
use \Calctool\Models\EstimateEndresult;


class EstimateRegister {

/*Calculation labor*/
	public static function estimLaborTotal($rate, $amount) {
		return $rate * $amount;
	}

/*Calculation Material*/
	public static function estimMaterialTotal($activity) {
		$total = 0;

		$rows = EstimateMaterial::where('activity_id', '=', $activity)->get();
		foreach ($rows as $row)
		{
			if ($row->original) {
				if ($row->isset)
					$total += EstimateRegister::estimLaborTotal($row->set_rate, $row->set_amount);
				else
					$total += EstimateRegister::estimLaborTotal($row->rate, $row->amount);
			} else {
				$total += EstimateRegister::estimLaborTotal($row->set_rate, $row->set_amount);
			}
		}

		return $total;
	}

/*Calculation Material Profit*/
	public static function estimMaterialTotalProfit($activity, $profit) {
		$total = EstimateRegister::estimMaterialTotal($activity);

		return (1+($profit/100))*$total;
	}

/*Calculation Equipment*/
	public static function estimEquipmentTotal($activity) {
		$total = 0;

		$rows = EstimateEquipment::where('activity_id', '=', $activity)->get();
		foreach ($rows as $row)
		{
			if ($row->original) {
				if ($row->isset)
					$total += EstimateRegister::estimLaborTotal($row->set_rate, $row->set_amount);
				else
					$total += EstimateRegister::estimLaborTotal($row->rate, $row->amount);
			} else {
				$total += EstimateRegister::estimLaborTotal($row->set_rate, $row->set_amount);
			}
		}

		return $total;
	}

/*Calculation Equipment Profit*/
	public static function estimEquipmentTotalProfit($activity, $profit) {
		$total = EstimateRegister::estimEquipmentTotal($activity);

		return (1+($profit/100))*$total;
	}

}
