<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class RelationKind extends Model {

	protected $table = 'relation_kind';
	protected $guarded = array('id');

	public $timestamps = false;

}
