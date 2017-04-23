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

use Illuminate\Database\Eloquent\Model;

use Auth;

class Project extends Model
{
    protected $table = 'project';
    protected $guarded = array('id', 'project_code');

    public function user() {
        return $this->hasOne('User');
    }

    public function contactor() {
        return $this->hasOne('Relation', 'id', 'client_id');
    }

    public function province() {
        return $this->hasOne('Province');
    }

    public function country() {
        return $this->hasOne('Country');
    }

    public function type() {
        return $this->hasOne('\BynqIO\CalculatieTool\Models\ProjectType', 'id', 'type_id');
    }

    public function isCalculation()
    {
        return $this->type->type_name == 'calculatie';
    }

    public function isDirectWork()
    {
        return $this->type->type_name == 'regiewerk';
    }

    public function isQuickInvoice()
    {
        return $this->type->type_name == 'snelle offerte en factuur';
    }

    public function isOwner() {
        return Auth::id() == $this->user_id;
    }
}
