<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class RelationType extends Model {

	protected $table = 'relation_type';
	protected $guarded = array('id');

	public $timestamps = false;

}
