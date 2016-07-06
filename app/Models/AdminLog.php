<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

use \Auth;

class AdminLog extends Model {

	protected $table = 'admin_log';

	public function user() {
		return $this->hasOne('User');
	}

}
