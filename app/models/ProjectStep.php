<?php

class ProjectStep extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'project_step';

	public $timestamps = false;

	public function projectType() {
		return $this->belongsToMany('ProjectType', 'project_type_project_step', 'step_id', 'type_id');
	}
}
