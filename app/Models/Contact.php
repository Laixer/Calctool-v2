<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model {

	protected $table = 'contact';
	protected $guarded = array('id');

	public $timestamps = false;

	public function contactFunction() {
		return $this->hasOne('ContactFunction');
	}

	public function relation() {
		return $this->hasOne('Relation');
	}

}
