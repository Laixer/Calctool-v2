<?php

class SystemOption extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'system_option';

	protected $fillable = array('option_key', 'option_value');

	public $timestamps = false;
}
