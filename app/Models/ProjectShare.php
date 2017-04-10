<?php

namespace CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectShare extends Model {

	protected $table = 'project_share';
	protected $guarded = array('id', 'token');

	public function __construct()
	{
		$this->token = \Calctool::generateToken();
	}

}
