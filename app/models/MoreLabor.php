<?php

class MoreLabor extends Eloquent {

	protected $table = 'more_labor';
	protected $guarded = array('id');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

	public function tax() {
		return $this->hasOne('Tax');
	}

	public function timesheet() {
		return $this->hasOne('Timesheet', 'hour_id');
	}

}
