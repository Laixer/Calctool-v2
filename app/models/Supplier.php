<?php

class Supplier extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'supplier';

	protected $fillable = array('supplier_name');

	public $timestamps = false;

	public function user() {
		return $this->hasOne('User');
	}
}
