<?php

namespace CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectType extends Model {

	protected $table = 'project_type';
	protected $guarded = array('id');

	public $timestamps = false;

	public function projectStep() {
		return $this->belongsToMany('ProjectStep', 'project_type_project_step', 'type_id', 'step_id');
	}

}
