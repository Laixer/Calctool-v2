<?php

namespace BynqIO\CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model {

	protected $table = 'chapter';
	protected $guarded = array('id', 'priority');

	public function project() {
		return $this->hasOne('Project');
	}

}
