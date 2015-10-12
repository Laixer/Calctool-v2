<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model {

	protected $table = 'resource';
	protected $guarded = array('id');

	public function user() {
		return $this->hasOne('User');
	}

	public function project() {
		return $this->hasOne('Project');
	}

	public function isOwner() {
		return Auth::id() == $this->user_id;
	}
}
