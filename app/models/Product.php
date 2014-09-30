<?php

class Product extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'product';

	protected $fillable = array('description', 'unit', 'unit_price');

	public $timestamps = false;

	public function group() {
		return $this->hasOne('SubGroup');
	}

	public function supplier() {
		return $this->hasOne('Supplier');
	}

	public function user() {
		return $this->belongsToMany('User', 'product_favorite', 'product_id', 'user_id');
	}
}
