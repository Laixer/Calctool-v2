<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model {

	protected $table = 'audit';
	protected $guarded = array('id', 'ip');

	public function user() {
		return $this->hasOne('User');
	}
}
