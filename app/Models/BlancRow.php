<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class BlancRow extends Model {

	protected $table = 'blanc_row';
	protected $guarded = array('id');

	public $timestamps = false;

}
