<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteLabor extends Model
{
    protected $table = 'favorite_labor';
    protected $guarded = array('id');

    public $timestamps = false;

    public function getAmount($original = false)
    {
        return $this->amount;
    }

    // public function project() {
    //     return $this->hasOne('Project');
    // }

}
