<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model {

	protected $table = 'audit';
	protected $guarded = array('id', 'ip');

	public function user() {
		return $this->hasOne('User');
	}

	public function __construct($event = null)
	{
		if (!empty($event)) {
			$this->event = $event;
		}

		$this->ip = \Calctool::remoteAddr();
	}

	public function setUserId($id)
	{
		$this->user_id = $id;
		return $this;
	}
}
