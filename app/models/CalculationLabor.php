<?php

class CalculationLabor extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'calculation_labor';

	protected $guarded = array('id');

	protected $fillable = array('rate', 'amount');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

	public function tax() {
		return $this->hasOne('Tax');
	}
}
