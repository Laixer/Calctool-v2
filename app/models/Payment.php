<?php

class Payment extends Eloquent {

	protected $table = 'payment';
	protected $guarded = array('id');

	public $timestamps = false;

	public function user() {
		return $this->hasOne('User');
	}

}
