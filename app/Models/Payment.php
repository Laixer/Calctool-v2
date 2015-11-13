<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model {

	protected $table = 'payment';
	protected $guarded = array('id', 'transaction');

	public function isOwner() {
		return Auth::id() == $this->user_id;
	}
}
