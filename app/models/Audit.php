<?php

class Audit extends Eloquent {

	protected $table = 'audit';
	protected $guarded = array('id', 'ip');

	public function user() {
		return $this->hasOne('User');
	}
}
