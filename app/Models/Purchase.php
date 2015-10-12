<?php

class Purchase extends Eloquent {

	protected $table = 'purchase';
	protected $guarded = array('id');

	public $timestamps = false;

	public function part() {
		return $this->hasOne('Part');
	}

	public function project() {
		return $this->hasOne('Project');
	}

	public function relation() {
		return $this->hasOne('Relation');
	}

}
