<?php

namespace BynqIO\CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteMaterial extends Model {

	protected $table = 'favorite_material';
	protected $guarded = array('id');

	public $timestamps = false;

	public function project() {
		return $this->hasOne('Project');
	}

}
