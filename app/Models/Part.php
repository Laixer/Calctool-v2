<?php

namespace CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model {

	protected $table = 'part';
	protected $guarded = array('id');

	public $timestamps = false;

	public function partType() {
		return $this->belongsToMany('PartType', 'part_part_type', 'part_id', 'type_id');
	}

}
