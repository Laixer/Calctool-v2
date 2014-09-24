<?php

class Resource extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'resource';

	public function user() {
		return $this->hasOne('User');
	}

	public function project() {
		return $this->hasOne('Project');
	}
}
