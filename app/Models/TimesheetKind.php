<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class TimesheetKind extends Model {

	protected $table = 'timesheet_kind';
	protected $guarded = array('id');

	public $timestamps = false;

}
