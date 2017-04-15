<?php

namespace BynqIO\CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model {

	protected $table = 'promotion';
	protected $guarded = array('id','code');

}
