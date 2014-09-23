<?php

class Detail extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'detail';

	protected $fillable = array('detail_name');

	public $timestamps = false;

	public function partType() {
		return $this->belongsToMany('PartType', 'part_part_detail', 'type_id', 'detail_id');
	}
}
