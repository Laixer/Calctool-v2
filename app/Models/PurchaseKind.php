<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseKind extends Model {

	protected $table = 'purchase_kind';
	protected $guarded = array('id');

	public $timestamps = false;

}
