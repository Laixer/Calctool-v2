<?php

class Iban extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'iban';

	protected $fillable = array('iban_name');

	public function user() {
		return $this->hasOne('User');
	}

	public $timestamps = false;
}
