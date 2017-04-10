<?php

namespace CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class Project extends Model {

	protected $table = 'project';
	protected $guarded = array('id', 'project_code');

	public function user() {
		return $this->hasOne('User');
	}

	public function contactor() {
		return $this->hasOne('Relation', 'id', 'client_id');
	}

	public function province() {
		return $this->hasOne('Province');
	}

	public function country() {
		return $this->hasOne('Country');
	}

	public function type() {
		return $this->hasOne('\CalculatieTool\Models\ProjectType', 'id', 'type_id');
	}

	public function isOwner() {
		return Auth::id() == $this->user_id;
	}
}
