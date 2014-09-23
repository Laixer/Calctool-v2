<?php

class Relation extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'relation';

	protected $fillable = array('company_name');

	protected $guarded = array('id', 'kvk', 'btw', 'debtor_code');

	public function user() {
		return $this->hasOne('User');
	}

	public function provance() {
		return $this->hasOne('Provance');
	}

	public function country() {
		return $this->hasOne('Country');
	}

	public function resource() {
		return $this->hasOne('Resource');
	}

	public function type() {
		return $this->hasOne('RelationType');
	}

	public function kind() {
		return $this->hasOne('RelationKind');
	}
}
