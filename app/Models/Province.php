<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model {

	protected $table = 'province';
	protected $guarded = array('id');

	public $timestamps = false;

}
