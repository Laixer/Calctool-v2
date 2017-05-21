<?php

namespace BynqIO\Dynq\Models;

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

    public function isEstimate()
    {
        return PartType::findOrFail($this->part_type_id)->type_name == 'estimate';
    }

    public function isSubcontracting()
    {
        return Part::findOrFail($this->part_id)->part_name == 'subcontracting';
    }

}
