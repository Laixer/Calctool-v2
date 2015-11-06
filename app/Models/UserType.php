<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model {

	protected $table = 'user_type';
	protected $guarded = array('id');

	public $timestamps = false;

}
