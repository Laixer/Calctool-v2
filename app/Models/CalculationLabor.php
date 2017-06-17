<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class CalculationLabor extends Model
{
    protected $table = 'calculation_labor';
    protected $guarded = array('id');

    public $timestamps = false;

    public function isOriginal()
    {
        return true;
    }

    public function getAmount($original = false)
    {
        /* Check updated in this stage */
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
