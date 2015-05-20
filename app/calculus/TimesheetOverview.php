<?php

/*
 * Urenregistratie
 */
class TimesheetOverview {

	public static function calcTotalAmount($activity) {
		$total = 0;

		$rows = CalculationLabor::where('activity_id','=',$activity)->get();
		foreach ($rows as $row)
		{
			if ($row['isless'])
				$total += $row['less_amount'];
			else
				$total += $row['amount'];
		}

		return $total;
	}

	public static function estimTotalAmount($activity) {
		$total = 0;

		$count = EstimateLabor::where('activity_id','=', $activity)->where('isset','=','true')->where('original','=','false')->count('id');
		$rows = EstimateLabor::where('activity_id', '=', $activity)->get();
		foreach ($rows as $row)
		{
			if ($count) {
				if ($row->isset && !$row->original) {
					$total += $row->set_amount;
				}
			} else {
				if ($row->isset)
					$total += $row->set_amount;
				else
					$total += $row->amount;
			}
		}

		return $total;
	}

	public static function calcTotal($rate, $activity) {
		$total = 0;

		$rows = CalculationLabor::where('activity_id','=',$activity)->get();
		foreach ($rows as $row)
		{
			if ($row['isless'])
				$total += $rate * $row['less_amount'];
			else
				$total += $rate * $row['amount'];
		}

		return $total;
	}

	public static function estimTotal($rate, $activity) {
		$total = 0;

		$count = EstimateLabor::where('activity_id','=', $activity)->where('isset','=','true')->where('original','=','false')->count('id');
		$rows = EstimateLabor::where('activity_id', '=', $activity)->get();
		foreach ($rows as $row)
		{
			if ($count) {
				if ($row->isset && !$row->original) {
					$total += $rate * $row->set_amount;
				}
			} else {
				if ($row->isset)
					$total += $rate * $row->set_amount;
				else
					$total += $rate * $row->amount;
			}
		}

		return $total;
	}

}
