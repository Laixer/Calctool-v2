<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteMaterial extends Model {

    protected $table = 'favorite_material';
    protected $guarded = array('id');

    public $timestamps = false;

    public function getName($original = false)
    {
        return $this->material_name;
    }

    public function getUnit($original = false)
    {
        return $this->unit;
    }

    public function getRate($original = false)
    {
        return $this->rate;
    }

    public function getAmount($original = false)
    {
        return $this->amount;
    }

    // public function project() {
    // 	return $this->hasOne('Project');
    // }

}
