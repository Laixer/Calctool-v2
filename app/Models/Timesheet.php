<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model {

	protected $table = 'timesheet';
	protected $guarded = array('id');

	public $timestamps = false;

	public function part() {
		return $this->hasOne('Part');
	}

	public function project() {
		return $this->hasOne('Project');
	}

}
