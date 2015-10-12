<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

class EstimateEquipment extends Model {

	protected $table = 'estimate_equipment';
	protected $guarded = array('id');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

}
