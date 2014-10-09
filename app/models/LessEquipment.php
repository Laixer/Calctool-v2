<?php

class LessEquipment extends Eloquent {

	protected $table = 'less_equipment';
	protected $guarded = array('id');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

	public function original() {
		return $this->hasOne('CalculationEquipment', 'original_id');
	}

}
