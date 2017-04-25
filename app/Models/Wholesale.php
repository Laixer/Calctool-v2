<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the BynqIO\CalculatieTool.com.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  CalculatieTool
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
 */

namespace BynqIO\CalculatieTool\Models;

use BynqIO\CalculatieTool\Models\Traits\Ownable;
use Illuminate\Database\Eloquent\Model;

class Wholesale extends Model
{
    use Ownable;

    protected $table = 'wholesale';
    protected $guarded = array('id');

    // public function user() {
    //     return $this->hasOne('User');
    // }

    // public function province() {
    //     return $this->hasOne('Province');
    // }

    // public function country() {
    //     return $this->hasOne('Country');
    // }

    // public function resource() {
    //     return $this->hasOne('Resource');
    // }

    // public function type() {
    //     return $this->hasOne('WholesaleType');
    // }

    public function isActive() {
        return $this->active;
    }

}
