<?php

namespace BynqIO\CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class Wholesale extends Model {

	protected $table = 'wholesale';
	protected $guarded = array('id');

	public function user() {
		return $this->hasOne('User');
	}

	public function province() {
		return $this->hasOne('Province');
	}

	public function country() {
		return $this->hasOne('Country');
	}

	public function resource() {
		return $this->hasOne('Resource');
	}

	public function type() {
		return $this->hasOne('WholesaleType');
	}

	public function isOwner() {
		return Auth::id() == $this->user_id;
	}

	public function isActive() {
		return $this->active;
	}

}
