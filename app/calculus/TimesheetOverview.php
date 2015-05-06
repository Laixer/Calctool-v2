<?php

/*
 * Urenregistratie
 */
class TimesheetOverview {

/*--Timesheet Overview - total per activitys--*/
/*labor activity total*/
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
}
