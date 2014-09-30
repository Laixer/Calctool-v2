<?php

class LessMaterial extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'less_material';

	protected $guarded = array('id');

	protected $fillable = array('rate', 'amount');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

	public function original() {
		return $this->hasOne('CalculationMaterial', 'original_id');
	}
}
