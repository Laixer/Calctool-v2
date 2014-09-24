<?php

class Project extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'project';

	protected $fillable = array('project_name');

	protected $guarded = array('id', 'project_code');

	public function user() {
		return $this->hasOne('User');
	}

	public function provance() {
		return $this->hasOne('Provance');
	}

	public function country() {
		return $this->hasOne('Country');
	}

	public function type() {
		return $this->hasOne('ProjectType');
	}
}
