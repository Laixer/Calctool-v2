<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class Valid extends Model {

	protected $table = 'valid';
	protected $guarded = array('id');

	public $timestamps = false;

}
