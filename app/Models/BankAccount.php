<?php

namespace Calctool\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class BankAccount extends Model {

	protected $table = 'bank_account';
	protected $guarded = array('id');

	public function isOwner() {
		return Auth::id() == $this->user_id;
	}

}
