<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class EstimateLabor extends Model
{
    protected $table = 'estimate_labor';
    protected $guarded = array('id');

    public $timestamps = false;

    // public function activity() {
    // 	return $this->hasOne('Activity');
    // }

    public function timesheet() {
        return $this->hasOne(Timesheet::class, 'id', 'hour_id');
    }

    public function isOriginal()
    {
        return $this->getAttribute('original');
    }

    public function getRateAttribute($value) //?
    {
        /* Check if row was created in earlier stage */
        if ($this->isOriginal()) {
            /* Check updated in this stage */
            if ($this->isset) {
                return $this->set_rate;
            } else {
                return $value;
            }
        } else {
            return $this->set_rate;
        }
    }

    public function getRate($original = false)
    {
        /* Check if row was created in earlier stage */
        if ($this->isOriginal()) {
            /* Check updated in this stage */
            if ($this->isset) {
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

    public function getAmountAttribute($value) //?
    {
        /* Check if row was created in earlier stage */
        if ($this->isOriginal()) {
            /* Check updated in this stage */
            if ($this->isset) {
                return $this->set_amount;
            } else {
                return $value;
            }
        } else {
            return $this->set_amount;
        }
    }
}
