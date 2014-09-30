<?php

class LessEquipment extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'less_equipment';

	protected $guarded = array('id');

	protected $fillable = array('rate', 'amount');

	public $timestamps = false;

	public function activity() {
		return $this->hasOne('Activity');
	}

	public function original() {
		return $this->hasOne('CalculationEquipment', 'original_id');
	}
}
