<?php

class Supplier extends Eloquent {

	protected $table = 'supplier';
	protected $guarded = array('id');

	public $timestamps = false;

	public function user() {
		return $this->hasOne('User');
	}

	public function isOwner() {
		return Auth::id() == $this->user_id;
	}
}
