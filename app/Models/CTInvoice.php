<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class CTInvoice extends Model {

	protected $table = 'ctinvoice';
	protected $guarded = array('id');

	public $timestamps = false;
}
