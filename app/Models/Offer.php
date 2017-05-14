<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model {

    protected $table = 'offer';
    protected $guarded = array('id');

    public function deliverTime() {
        return $this->hasOne('DeliverTime');
    }

    public function specification() {
        return $this->hasOne('Specification');
    }

    public function valid() {
        return $this->hasOne('Valid');
    }

    public function project() {
        return $this->hasOne('Project');
    }

}
