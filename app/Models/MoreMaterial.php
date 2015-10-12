<?php

class MoreMaterial extends Eloquent {

	protected $table = 'more_material';
	protected $guarded = array('id');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

}
