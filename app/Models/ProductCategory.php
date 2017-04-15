<?php

namespace BynqIO\CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model {

	protected $table = 'product_category';
	protected $guarded = array('id');

	public $timestamps = false;
}
