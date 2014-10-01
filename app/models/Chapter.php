<?php

class Chapter extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'chapter';

	protected $guarded = array('id', 'priority');

	protected $fillable = array('chapter_name');

	public $timestamps = false;

	public function project() {
		return $this->hasOne('Project');
	}
}
