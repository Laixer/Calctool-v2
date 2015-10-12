<?php

class Invoice extends Eloquent {

	protected $table = 'invoice';
	protected $guarded = array('id');

	public function type() {
		return $this->hasOne('InvoiceType');
	}

	public function offer() {
		return $this->hasOne('Offer');
	}

}
