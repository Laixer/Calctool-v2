<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class Relation extends Model {

	protected $table = 'relation';
	protected $guarded = array('id', 'debtor_code');

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
		return $this->hasOne('RelationType');
	}

	public function kind() {
		return $this->hasOne('RelationKind', 'id');
	}

	public function isOwner() {
		return Auth::id() == $this->user_id;
	}
	
}
