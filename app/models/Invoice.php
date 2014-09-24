<?php

class Invoice extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'invoice';

	protected $guarded = array('id', 'reference', 'invoice_code', 'book_code');

	public function type() {
		return $this->hasOne('InvoiceType');
	}

	public function offer() {
		return $this->hasOne('Offer');
	}
}
