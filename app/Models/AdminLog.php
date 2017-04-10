<?php

namespace CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

use \Auth;

class AdminLog extends Model {

	protected $table = 'admin_log';

	public function user() {
		return $this->hasOne('User');
	}

	public function label() {
		return $this->hasOne('CalculatieTool\Models\AdminLogLabel', 'id', 'label_id');
	}

}
