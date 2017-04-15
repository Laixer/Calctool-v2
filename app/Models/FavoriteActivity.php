<?php

namespace BynqIO\CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class FavoriteActivity extends Model {

	protected $table = 'favorite_activity';
	protected $guarded = array('id');

	public function user() {
		return $this->hasOne('User');
	}

	public function tax() {
		return $this->hasOne('Tax', 'id', 'tax_id');
	}

	public function isOwner() {
		return Auth::id() == $this->user_id;
	}
}
