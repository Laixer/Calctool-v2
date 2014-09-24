<?php

class Part extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'part';

	protected $fillable = array('part_name', 'acronym');

	public $timestamps = false;

	public function partType() {
		return $this->belongsToMany('PartType', 'part_part_type', 'part_id', 'type_id');
	}
}
