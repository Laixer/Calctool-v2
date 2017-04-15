<?php

namespace BynqIO\CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

class EstimateLabor extends Model {

	protected $table = 'estimate_labor';
	protected $guarded = array('id');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

	public function timesheet() {
		return $this->hasOne('Timesheet', 'hour_id');
	}

}
