<?php

class Timesheet extends Eloquent {

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
