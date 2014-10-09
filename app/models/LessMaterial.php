<?php

class LessMaterial extends Eloquent {

	protected $table = 'less_material';
	protected $guarded = array('id');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

	public function original() {
		return $this->hasOne('CalculationMaterial', 'original_id');
	}

}
