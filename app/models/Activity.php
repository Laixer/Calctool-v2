<?php

class Activity extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'activity';

	protected $guarded = array('id', 'priority');

	protected $fillable = array('activity_name');

	public $timestamps = false;

	public function chapter() {
		return $this->hasOne('Chapter');
	}
}
