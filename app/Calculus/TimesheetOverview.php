<?php

namespace Calctool\Calculus;

use \Calctool\Models\CalculationLabor;
use \Calctool\Models\EstimateLabor;
use \Calctool\Models\Timesheet;

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

	public static function calcLessTotalAmount($activity) {
		return CalculationLabor::where('activity_id','=',$activity)->sum('less_amount');
	}

	public static function calcOrigTotalAmount($activity) {
		return CalculationLabor::where('activity_id','=',$activity)->sum('amount');
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

	public static function estimSetTotalAmount($activity) {
		return EstimateLabor::where('activity_id', '=', $activity)->whereNull('hour_id')->sum('set_amount');
	}

	public static function estimOrigTotalAmount($activity) {
		return EstimateLabor::where('activity_id', '=', $activity)->sum('amount');
	}

	public static function estimTimesheetTotalAmount($activity) {
		return Timesheet::where('activity_id', '=', $activity)->sum('register_hour');
	}

	public static function calcTotalHour($rate, $activity) {
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

	public static function calcTotalHourCalculation($project) {
		$total = 0;

		$chapters = Chapter::where('project_id','=', $project->id)->get();
		foreach ($chapters as $chapter)
		{
			$activities = Activity::where('chapter_id','=', $chapter->id)->get();
			foreach ($activities as $activity)
			{
				$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row['isless'])
						$total += $project->hour_rate * $row['less_amount'];
					else
						$total += $project->hour_rate * $row['amount'];
				}
			}
		}

		return $total;
	}

	public static function estimTotalHour($rate, $activity) {
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

	public static function calcTotalCalculation($project) {
		$total = 0;

		$chapters = Chapter::where('project_id','=', $project->id)->get();
		foreach ($chapters as $chapter)
		{
			$activities = Activity::where('chapter_id','=', $chapter->id)->get();
			foreach ($activities as $activity)
			{
				$rows = CalculationLabor::where('activity_id','=',$activity->id)->get();
				foreach ($rows as $row)
				{
					if ($row['isless'])
						$total += $row['less_amount'];
					else
						$total += $row['amount'];
				}
			}
		}

		return $total;
	}

	public static function calcTotalTimesheet($project) {
		$total = 0;

		$chapters = Chapter::where('project_id','=', $project->id)->get();
		foreach ($chapters as $chapter)
		{
			$activities = Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',Part::where('part_name','=','contracting')->first()->id)->get();
			foreach ($activities as $activity)
			{
				$total += Timesheet::where('activity_id','=', $activity->id)->sum('register_hour');
			}
		}

		return $total;
	}

	public static function estimTotalCalculation($project) {
		$total = 0;

		$chapters = Chapter::where('project_id','=', $project->id)->get();
		foreach ($chapters as $chapter)
		{
			$activities = Activity::where('chapter_id','=', $chapter->id)->get();
			foreach ($activities as $activity)
			{
				$count = EstimateLabor::where('activity_id','=', $activity->id)->where('isset','=','true')->where('original','=','false')->count('id');
				$rows = EstimateLabor::where('activity_id', '=', $activity->id)->get();
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
			}
		}

		return $total;
	}

	public static function estimTotalTimesheet($project) {
		$total = 0;

		$chapters = Chapter::where('project_id','=', $project->id)->get();
		foreach ($chapters as $chapter)
		{
			$activities = Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get();
			foreach ($activities as $activity)
			{
				$total += Timesheet::where('activity_id','=', $activity->id)->sum('register_hour');
			}
		}

		return $total;
	}

	public static function estimTotalHourCalculation($project) {
		$total = 0;

		$chapters = Chapter::where('project_id','=', $project->id)->get();
		foreach ($chapters as $chapter)
		{
			$activities = Activity::where('chapter_id','=', $chapter->id)->get();
			foreach ($activities as $activity)
			{
				$count = EstimateLabor::where('activity_id','=', $activity->id)->where('isset','=','true')->where('original','=','false')->count('id');
				$rows = EstimateLabor::where('activity_id', '=', $activity->id)->get();
				foreach ($rows as $row)
				{
					if ($count) {
						if ($row->isset && !$row->original) {
							$total += $rate * $row->set_amount;
						}
					} else {
						if ($row->isset)
							$total += $project->hour_rate * $row->set_amount;
						else
							$total += $project->hour_rate * $row->amount;
					}
				}
			}
		}

		return $total;
	}



	public static function timesheetTotalTimesheet($project) {
		$total = 0;

		$chapters = Chapter::where('project_id','=', $project->id)->get();
		foreach ($chapters as $chapter)
		{
			$activities = Activity::where('chapter_id','=', $chapter->id)->get();
			foreach ($activities as $activity)
			{
				$total += Timesheet::where('activity_id','=', $activity->id)->where('timesheet_kind_id','=',TimesheetKind::where('kind_name','=','meerwerk')->first()->id)->sum('register_hour');
			}
		}

		return $total;
	}

	public static function moreTotalTimesheet($project) {
		$total = 0;

		$chapters = Chapter::where('project_id','=', $project->id)->get();
		foreach ($chapters as $chapter)
		{
			$activities = Activity::where('chapter_id','=', $chapter->id)->get();
			foreach ($activities as $activity)
			{
				$total += MoreLabor::where('activity_id','=', $activity->id)->whereNull('hour_id')->sum('amount');
			}
		}

		return $total;
	}

}
