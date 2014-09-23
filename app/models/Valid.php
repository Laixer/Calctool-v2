<?php

class Valid extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'valid';

	protected $fillable = array('valid_name');

	public $timestamps = false;
}
