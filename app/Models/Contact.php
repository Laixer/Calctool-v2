<?php

namespace BynqIO\CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model {

	protected $table = 'contact';
	protected $guarded = array('id');

	public $timestamps = false;

	public function contactFunction() {
		return $this->hasOne('ContactFunction');
	}

	public function relation() {
		return $this->hasOne('Relation');
	}

	public function getFormalName() {
		if ($this->salutation) {
			return $this->salutation  . " " . $this->lastname;
		} else if ($this->gender) {
			if ($this->gender == 'M')
				return 'heer ' . $this->lastname;
			else
				return 'mevrouw ' . $this->lastname;
		} else {
			return $this->firstname . " " . $this->lastname;
		}
	}

}
