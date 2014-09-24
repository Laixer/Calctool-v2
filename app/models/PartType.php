<?php

class PartType extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'part_type';

	protected $fillable = array('type_name');

	public $timestamps = false;

	public function part() {
		return $this->belongsToMany('Part', 'part_part_type', 'type_id', 'part_id');
	}

	public function detail() {
		return $this->belongsToMany('Detail', 'part_part_detail', 'detail_id', 'type_id');
	}
}
