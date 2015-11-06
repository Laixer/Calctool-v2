<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model {

	protected $table = 'country';
	protected $guarded = array('id');

	public $timestamps = false;

}
