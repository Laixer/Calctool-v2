<?php

class LessLabor extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'less_labor';

	protected $guarded = array('id');

	protected $fillable = array('amount');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

	public function original() {
		return $this->hasOne('CalculationLabor', 'original_id');
	}
}
