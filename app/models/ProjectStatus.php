<?php

class ProjectStatus extends Eloquent {

	protected $table = 'status_date';
	protected $guarded = array('id');

	public function step() {
		return $this->hasOne('ProjectStep');
	}

	public function project() {
		return $this->hasOne('Project');
	}

}
