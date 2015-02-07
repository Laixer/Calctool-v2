<?php

class Register {

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
			$total += Register::calcLaborTotal($row->rate, $row->amount);
		}

		return $total;
	}

/*Calculation Material Profit*/
	public static function calcMaterialTotalProfit($activity, $userid) {
		$profit = Project::where('user_id', '=', $userid)->first()['profit_calc_contr_mat'];
		$total = Register::calcMaterialTotal($activity);

		return (1+($profit/100))*$total;
	}

/*Calculation Equipment*/
	public static function calcEquipmentTotal($activity) {
		$total = 0;

		$rows = CalculationEquipment::where('activity_id', '=', $activity)->get();
		foreach ($rows as $row)
		{
			$total += Register::calcLaborTotal($row->rate, $row->amount);
		}

		return $total;
	}

/*Calculation Equipment Profit*/
	public static function calcEquipmentTotalProfit($activity, $userid) {
		$profit = Project::where('user_id', '=', $userid)->first()['profit_calc_contr_equip'];
		$total = Register::calcEquipmentTotal($activity);

		return (1+($profit/100))*$total;
	}

}
