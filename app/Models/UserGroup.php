<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model {

	protected $table = 'user_group';
	protected $guarded = array('id');

	public $timestamps = false;

}
