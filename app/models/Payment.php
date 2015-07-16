<?php

class Payment extends Eloquent {

	protected $table = 'payment';
	protected $guarded = array('id', 'transaction');

}
