<?php

class LessLabor extends Eloquent {

	protected $table = 'less_labor';
	protected $guarded = array('id');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

	public function original() {
		return $this->hasOne('CalculationLabor', 'original_id');
	}

}
