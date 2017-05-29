<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class CalculationEquipment extends Model
{
    protected $table = 'calculation_equipment';
    protected $guarded = array('id');

    public $timestamps = false;

    public function isOriginal()
    {
        return true;
    }

    public function getName($original = false)
    {
        return $this->equipment_name;
    }

    public function getUnit($original = false)
    {
        return $this->unit;
    }

    public function getRate($original = false)
    {
        if ($this->isless && !$original) {
            return $this->less_rate;
        } else {
            return $this->rate;
        }
    }

    public function getAmount($original = false)
    {
        if ($this->isless && !$original) {
            return $this->less_amount;
        } else {
            return $this->amount;
        }
    }

    // public function activity() {
    //     return $this->hasOne('Activity');
    // }

}
