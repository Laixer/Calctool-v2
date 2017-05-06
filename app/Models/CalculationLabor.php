<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class CalculationLabor extends Model {

	protected $table = 'calculation_labor';
	protected $guarded = array('id');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

}
