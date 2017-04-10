<?php

namespace CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

class DeliverTime extends Model {

	protected $table = 'deliver_time';
	protected $guarded = array('id');

	public $timestamps = false;

}
