<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

class CalculationEquipment extends Model {

	protected $table = 'calculation_equipment';
	protected $guarded = array('id');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

}
