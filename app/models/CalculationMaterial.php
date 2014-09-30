<?php

class CalculationMaterial extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'calculation_material';

	protected $guarded = array('id');

	protected $fillable = array('material_name', 'unit', 'rate', 'amount');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

	public function tax() {
		return $this->hasOne('Tax');
	}
}
