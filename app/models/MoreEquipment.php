<?php

class MoreEquipment extends Eloquent {

	protected $table = 'more_equipment';
	protected $guarded = array('id');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

}
