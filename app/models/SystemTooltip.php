<?php

class SystemTooltip extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'system_tooltip';

	protected $fillable = array('title', 'tooltip_content');

	public $timestamps = false;
}
