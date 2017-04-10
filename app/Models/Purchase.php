<?php

namespace CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model {

	protected $table = 'purchase';
	protected $guarded = array('id');

	public $timestamps = false;

	public function part() {
		return $this->hasOne('Part');
	}

	public function project() {
		return $this->hasOne('Project');
	}

	public function relation() {
		return $this->hasOne('Relation');
	}

}
