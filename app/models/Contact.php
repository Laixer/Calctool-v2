<?php

class Contact extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'contact';

	protected $fillable = array('firstname', 'lastname', 'email');

	protected $guarded = array('id');

	public $timestamps = false;

	public function contactFunction() {
		return $this->hasOne('ContactFunction');
	}

	public function relation() {
		return $this->hasOne('Relation');
	}
}
