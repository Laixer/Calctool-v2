<?php

namespace CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class MessageBox extends Model {

	protected $table = 'messagebox';
	protected $guarded = array('id');

	public function isOwner() {
		return Auth::id() == $this->user_id;
	}

}
