<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {

	protected $table = 'invoice';
	protected $guarded = array('id');

	public function type() {
		return $this->hasOne('InvoiceType');
	}

	public function offer() {
		return $this->hasOne('Offer');
	}

}
