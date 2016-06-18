<?php

namespace Calctool\Calculus;

use \Calctool\Models\CalculationMaterial;
use \Calctool\Models\CalculationEquipment;
use \Calctool\Models\Part;

class LessRegister {

	public static function lessLaborDeltaTotal($labor, $activity, $project) {
		$rate = $labor->rate;
		if (Part::find($activity->part_id)->part_name == 'contracting') {
			$rate = $project->hour_rate;
		}

		if ($labor->isless)
			return ($labor->less_amount - $labor->amount) * $rate;
		return 0;
	}

/*Calculation labor*/
	public static function lessLaborTotal($rate, $amount) {
		return $rate * $amount;
	}

/*Calculation Material*/
	public static function lessMaterialTotal($activity) {
		$total = 0;

		$rows = CalculationMaterial::where('activity_id', '=', $activity)->get();
		foreach ($rows as $row)
		{
			if ($row->isless)
				$total += LessRegister::lessLaborTotal($row->less_rate, $row->less_amount);
			else
				$total += LessRegister::lessLaborTotal($row->rate, $row->amount);
		}

		return $total;
	}

/*Calculation Material Profit*/
	public static function lessMaterialTotalProfit($activity, $profit) {
		$total = LessRegister::lessMaterialTotal($activity);

		return (1+($profit/100))*$total;
	}

/*Calculation Equipment*/
	public static function lessEquipmentTotal($activity) {
		$total = 0;

		$rows = CalculationEquipment::where('activity_id', '=', $activity)->get();
		foreach ($rows as $row)
		{
			if ($row->isless)
				$total += LessRegister::lessLaborTotal($row->less_rate, $row->less_amount);
			else
				$total += LessRegister::lessLaborTotal($row->rate, $row->amount);
		}

		return $total;
	}

/*Calculation Equipment Profit*/
	public static function lessEquipmentTotalProfit($activity, $profit) {
		$total = LessRegister::lessEquipmentTotal($activity);

		return (1+($profit/100))*$total;
	}

	public static function lessMaterialDeltaTotal($activity, $profit) {
		$supertotal = 0;

		$rows = CalculationMaterial::where('activity_id', '=', $activity)->get();
		foreach ($rows as $row)
		{
			if ($row->isless) {
				$total = (LessRegister::lessLaborTotal($row->less_rate, $row->less_amount) * (1+($profit/100)));
				$less_total = (LessRegister::lessLaborTotal($row->rate, $row->amount) * (1+($profit/100)));
				$supertotal += $total - $less_total;
			}
		}

		return $supertotal;
	}

	public static function lessEquipmentDeltaTotal($activity, $profit) {
		$supertotal = 0;

		$rows = CalculationEquipment::where('activity_id', '=', $activity)->get();
		foreach ($rows as $row)
		{
			if ($row->isless) {
				$total = (LessRegister::lessLaborTotal($row->less_rate, $row->less_amount) * (1+($profit/100)));
				$less_total = (LessRegister::lessLaborTotal($row->rate, $row->amount) * (1+($profit/100)));
				$supertotal += $total - $less_total;
			}
		}

		return $supertotal;
	}

}
