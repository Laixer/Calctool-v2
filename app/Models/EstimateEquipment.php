<?php

class EstimateEquipment extends Eloquent {

	protected $table = 'estimate_equipment';
	protected $guarded = array('id');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

}
