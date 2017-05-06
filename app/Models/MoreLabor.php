<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class MoreLabor extends Model {

	protected $table = 'more_labor';
	protected $guarded = array('id');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

	public function timesheet() {
		return $this->hasOne('Timesheet', 'hour_id');
	}

}
