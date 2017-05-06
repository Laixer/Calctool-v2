<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSubCategory extends Model {

	protected $table = 'product_sub_category';
	protected $guarded = array('id');

	public $timestamps = false;
}
