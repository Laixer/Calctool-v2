<?php

class MoreLabor extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'more_labor';

	protected $guarded = array('id');

	protected $fillable = array('rate', 'amount', 'note');

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
