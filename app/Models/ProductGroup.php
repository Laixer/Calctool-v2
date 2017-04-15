<?php

namespace BynqIO\CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

class ProductGroup extends Model {

	protected $table = 'product_group';
	protected $guarded = array('id');

	public $timestamps = false;
}
