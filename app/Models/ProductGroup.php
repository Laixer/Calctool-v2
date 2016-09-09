<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

class ProductGroup extends Model {

	protected $table = 'product_group';
	protected $guarded = array('id');

	public $timestamps = false;
}
