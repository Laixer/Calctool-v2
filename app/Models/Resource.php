<?php

class Resource extends Eloquent {

	protected $table = 'resource';
	protected $guarded = array('id');

	public function user() {
		return $this->hasOne('User');
	}

	public function project() {
		return $this->hasOne('Project');
	}

	public function isOwner() {
		return Auth::id() == $this->user_id;
	}
}
