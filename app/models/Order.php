<?php

class Order extends Eloquent {

	protected $table = 'order';
	protected $guarded = array('id', 'transaction');

}
