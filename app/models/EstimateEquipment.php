<?php

class EstimateEquipment extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'estimate_equipment';

	protected $guarded = array('id');

	protected $fillable = array('equipment_name', 'unit', 'rate', 'amount', 'set_equipment_name', 'set_unit', 'set_rate', 'set_amount');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

	public function tax() {
		return $this->hasOne('Tax');
	}
}
