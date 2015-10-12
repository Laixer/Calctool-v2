<?php

class Iban extends Eloquent {

	protected $table = 'iban';
	protected $guarded = array('id');

	public $timestamps = false;

	public function user() {
		return $this->hasOne('User');
	}

	public function isOwner() {
		return Auth::id() == $this->user_id;
	}
}
