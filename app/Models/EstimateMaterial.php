<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

class EstimateMaterial extends Model {

	protected $table = 'estimate_material';
	protected $guarded = array('id');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

}
