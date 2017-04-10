<?php

namespace CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model {

	protected $table = 'detail';
	protected $guarded = array('id');

	public $timestamps = false;

	public function partType() {
		return $this->belongsToMany('PartType', 'part_part_detail', 'type_id', 'detail_id');
	}

}
