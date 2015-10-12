<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

class Iban extends Model {

	protected $table = 'iban';
	protected $guarded = array('id');

	public $timestamps = false;

	public function user() {
		return $this->hasOne('User');
	}

	public function isOwner() {
		return Auth::id() == $this->user_id;
	}
}
