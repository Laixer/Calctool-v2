<?php

namespace CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteEquipment extends Model {

	protected $table = 'favorite_equipment';
	protected $guarded = array('id');

	public $timestamps = false;

	public function project() {
		return $this->hasOne('Project');
	}

}
