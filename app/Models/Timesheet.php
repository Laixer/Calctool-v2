<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    protected $table = 'timesheet';
    protected $guarded = ['id'];

    public $timestamps = false;

    public function getName()
    {
        return $this->note;
    }

    // public function part() {
    //     return $this->hasOne('Part');
    // }

    // public function project() {
    //     return $this->hasOne('Project');
    // }

}
