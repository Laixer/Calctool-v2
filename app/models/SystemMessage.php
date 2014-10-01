<?php

class SystemMessage extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'system_message';

	protected $fillable = array('message_content');
}
