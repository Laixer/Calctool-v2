<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class MoreLabor extends Model
{
    protected $table = 'more_labor';
    protected $guarded = ['id'];

    public $timestamps = false;

    // public function activity() {
    //     return $this->hasOne('Activity');
    // }

    // public function timesheet() {
    //     return $this->hasOne('Timesheet', 'hour_id');
    // }

    public function getAmount($original = false)
    {
        return $this->amount;
    }

}
