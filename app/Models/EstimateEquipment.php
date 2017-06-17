<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class EstimateEquipment extends Model
{
    protected $table = 'estimate_equipment';
    protected $guarded = array('id');

    public $timestamps = false;

    public function isOriginal()
    {
        return $this->getAttribute('original');
    }

    public function getName($original = false)
    {
        /* Check if row was created in earlier stage */
        if ($this->isOriginal()) {
            /* Check updated in this stage */
            if ($this->isset && !$original) {
                return $this->set_equipment_name;
            } else {
                return $this->equipment_name;
            }
        } else {
            return $this->set_equipment_name;
        }
    }

    public function getUnit($original = false)
    {
        /* Check if row was created in earlier stage */
        if ($this->isOriginal()) {
            /* Check updated in this stage */
            if ($this->isset && !$original) {
                return $this->set_unit;
            } else {
                return $this->unit;
            }
        } else {
            return $this->set_unit;
        }
    }

    public function getRate($original = false)
    {
        /* Check if row was created in earlier stage */
        if ($this->isOriginal()) {
            /* Check updated in this stage */
            if ($this->isset && !$original) {
                return $this->set_rate;
            } else {
                return $this->rate;
            }
        } else {
            return $this->set_rate;
        }
    }

    public function getAmount($original = false)
    {
        /* Check if row was created in earlier stage */
        if ($this->isOriginal()) {
            /* Check updated in this stage */
            if ($this->isset && !$original) {
                return $this->set_amount;
            } else {
                return $this->amount;
            }
        } else {
            return $this->set_amount;
        }
    }

    // public function activity() {
    //     return $this->hasOne('Activity');
    // }

}
