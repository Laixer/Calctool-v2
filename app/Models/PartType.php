<?php

namespace BynqIO\CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

class PartType extends Model {

	protected $table = 'part_type';
	protected $guarded = array('id');

	public $timestamps = false;

	public function part() {
		return $this->belongsToMany('Part', 'part_part_type', 'type_id', 'part_id');
	}

	public function detail() {
		return $this->belongsToMany('Detail', 'part_part_detail', 'detail_id', 'type_id');
	}

}
