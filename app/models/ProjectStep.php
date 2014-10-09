<?php

class ProjectStep extends Eloquent {

	protected $table = 'project_step';
	protected $guarded = array('id');

	public $timestamps = false;

	public function projectType() {
		return $this->belongsToMany('ProjectType', 'project_type_project_step', 'step_id', 'type_id');
	}

}
