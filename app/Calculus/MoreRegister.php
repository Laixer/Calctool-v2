<?php

namespace BynqIO\CalculatieTool\Calculus;

use \BynqIO\CalculatieTool\Models\MoreMaterial;
use \BynqIO\CalculatieTool\Models\MoreEquipment;

class MoreRegister {

/*Calculation labor*/
	public static function laborTotal($rate, $amount) {
		return $rate * $amount;
	}

/*Calculation Material*/
	public static function materialTotal($activity) {
		$total = 0;

		$rows = MoreMaterial::where('activity_id', '=', $activity)->get();
		foreach ($rows as $row)
		{
			$total += MoreRegister::laborTotal($row->rate, $row->amount);
		}

		return $total;
	}

/*Calculation Material Profit*/
	public static function materialTotalProfit($activity, $profit) {
		$total = MoreRegister::materialTotal($activity);

		return (1+($profit/100))*$total;
	}

/*Calculation Equipment*/
	public static function equipmentTotal($activity) {
		$total = 0;

		$rows = MoreEquipment::where('activity_id', '=', $activity)->get();
		foreach ($rows as $row)
		{
			$total += MoreRegister::laborTotal($row->rate, $row->amount);
		}

		return $total;
	}

/*Calculation Equipment Profit*/
	public static function equipmentTotalProfit($activity, $profit) {
		$total = MoreRegister::equipmentTotal($activity);

		return (1+($profit/100))*$total;
	}

}
