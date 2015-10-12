<?php

class Payment extends Eloquent {

	protected $table = 'payment';
	protected $guarded = array('id', 'transaction');

	public function isOwner() {
		return Auth::id() == $this->user_id;
	}
}
