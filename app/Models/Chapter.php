<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model {

	protected $table = 'chapter';
	protected $guarded = array('id', 'priority');

	public $timestamps = false;

	public function project() {
		return $this->hasOne('Project');
	}

}
