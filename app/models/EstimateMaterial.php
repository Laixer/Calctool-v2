<?php

class EstimateMaterial extends Eloquent {

	protected $table = 'estimate_material';
	protected $guarded = array('id');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

	public function tax() {
		return $this->hasOne('Tax');
	}

}
