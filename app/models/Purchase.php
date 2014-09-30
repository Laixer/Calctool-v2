<?php

class Purchase extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'purchase';

	protected $guarded = array('id');

	protected $fillable = array('note');

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
