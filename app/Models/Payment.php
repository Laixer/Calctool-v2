<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model {

	protected $table = 'payment';
	protected $guarded = array('id', 'transaction');

	public function isOwner() {
		return Auth::id() == $this->user_id;
	}

	public function getStatusName() {
		switch ($this->status) {
			case 'paid':
				return 'Betaald';
			case 'cancelled':
				return 'Afgebroken';
			
			default:
				return $this->status;
		}
	}
}
