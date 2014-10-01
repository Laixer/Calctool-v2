<?php

class EstimateLabor extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'estimate_labor';

	protected $guarded = array('id');

	protected $fillable = array('rate', 'amount', 'set_rate', 'set_amount');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

	public function tax() {
		return $this->hasOne('Tax');
	}

	public function timesheet() {
		return $this->hasOne('Timesheet', 'hour_id');
	}
}
