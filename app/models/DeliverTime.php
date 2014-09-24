<?php

class DeliverTime extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'deliver_time';

	protected $fillable = array('delivertime_name');

	public $timestamps = false;
}
