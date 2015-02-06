<?php

class Register {

	public static function calcLaborTotal($rate, $amount) {
		return $rate * $amount;
	}

	public static function calcMaterialTotal($activity) {
		$total = 0;

		$rows = CalculationMaterial::where('activity_id', '=', $activity)->get();
		foreach ($rows as $row)
		{
			$total += Register::calcLaborTotal($row->rate, $row->amount);
		}

		return $total;
	}

	public static function calcMaterialTotalProfit($activity, $userid) {
		$profit = Project::where('user_id', '=', $userid)->first()['profit_calc_contr_mat'];
		$total = Register::calcMaterialTotal($activity);

		return (1+($profit/100))*$total;
	}

}
