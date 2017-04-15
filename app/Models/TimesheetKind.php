<?php

namespace BynqIO\CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

class TimesheetKind extends Model {

	protected $table = 'timesheet_kind';
	protected $guarded = array('id');

	public $timestamps = false;

}
