<?php

class Chapter extends Eloquent {

	protected $table = 'chapter';
	protected $guarded = array('id', 'priority');

	public $timestamps = false;

	public function project() {
		return $this->hasOne('Project');
	}

}
