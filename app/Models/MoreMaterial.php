<?php

namespace CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

class MoreMaterial extends Model {

	protected $table = 'more_material';
	protected $guarded = array('id');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

}
