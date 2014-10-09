<?php

class Project extends Eloquent {

	protected $table = 'project';
	protected $guarded = array('id', 'project_code');

	public function user() {
		return $this->hasOne('User');
	}

	public function province() {
		return $this->hasOne('Province');
	}

	public function country() {
		return $this->hasOne('Country');
	}

	public function type() {
		return $this->hasOne('ProjectType');
	}

}
