<?php

class SystemOption extends Eloquent {

	protected $table = 'system_option';
	protected $guarded = array('id');

	public $timestamps = false;

}
