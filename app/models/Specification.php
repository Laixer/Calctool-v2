<?php

class Specification extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'specification';

	protected $fillable = array('specification_name');

	public $timestamps = false;
}
