<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

class Element extends Model {

	protected $table = 'element';
	protected $guarded = array('id');

	public $timestamps = false;

}
