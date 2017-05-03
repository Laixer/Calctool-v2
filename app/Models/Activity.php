<?php

namespace BynqIO\CalculatieTool\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model {

    protected $table = 'activity';
    protected $guarded = array('id');

    // public function chapter() {
    //     return $this->hasOne('Chapter');
    // }

    // public function tax() {
    //     return $this->hasOne('Tax', 'id', 'tax_id');
    // }

}
