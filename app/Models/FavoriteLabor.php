<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteLabor extends Model {

	protected $table = 'favorite_labor';
	protected $guarded = array('id');

	public $timestamps = false;

	public function project() {
		return $this->hasOne('Project');
	}

}
