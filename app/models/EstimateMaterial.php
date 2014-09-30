<?php

class EstimateMaterial extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'estimate_material';

	protected $guarded = array('id');

	protected $fillable = array('material_name', 'unit', 'rate', 'amount', 'set_material_name', 'set_unit', 'set_rate', 'set_amount');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

	public function tax() {
		return $this->hasOne('Tax');
	}
}
