<?php

class CalculationLabor extends Eloquent {

	protected $table = 'calculation_labor';
	protected $guarded = array('id');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

}
