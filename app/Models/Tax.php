<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model {

	protected $table = 'tax';
	protected $guarded = array('id');

	public $timestamps = false;

}
