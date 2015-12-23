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

	public function isExpiredFirst() { // condition
		if (!$this->bill_date)
			return false;

		if ($this->payment_date)
			return false;

		if (strtotime(date('Y-m-d')) == strtotime($this->bill_date . " +".$this->payment_condition." days")) {
			return true;
		}
		return false;
	}

	public function isExpiredSecond() { // condition + 14
		if (!$this->bill_date)
			return false;

		if ($this->payment_date)
			return false;

		if (strtotime(date('Y-m-d')) == strtotime($this->bill_date . " +".($this->payment_condition + 14)." days")) {
			return true;
		}
		return false;
	}

	public function isExpiredDemand() { // condition + 14 + 7
		if (!$this->bill_date)
			return false;

		if ($this->payment_date)
			return false;

		if (strtotime(date('Y-m-d')) == strtotime($this->bill_date . " +".($this->payment_condition + 14 + 7)." days")) {
			return true;
		}
		return false;
	}

}
