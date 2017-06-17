<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class ContactFunction extends Model {

	protected $table = 'contact_function';
	protected $guarded = array('id');

	public $timestamps = false;

}
