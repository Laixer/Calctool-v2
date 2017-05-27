<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the Dynq project.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  Dynq
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
 */

namespace BynqIO\Dynq\Models;

use BynqIO\Dynq\Models\Traits\Ownable;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use Ownable;

    protected $table = 'project';
    protected $guarded = array('id', 'project_code');

    // public function user() {
    //     return $this->hasOne('User');
    // }

    // public function contactor() {
    //     return $this->hasOne('Relation', 'id', 'client_id');
    // }

    // public function province() {
    //     return $this->hasOne('Province');
    // }

    public function chapters() {
        return $this->hasMany(Chapter::class);
    }

    /**
     * Get the project slug name.
     *
     * @param  string  $value
     * @return string
     */
    public function slug()
    {
        return strtolower(str_slug($this->project_name));
    }

    public function status()
    {
        if ($this->project_close) {
            if ($this->is_dilapidated) {
                return 'vervallen';
            }
            
            return 'gesloten';
        }

        return 'open';
    }

    public function type() {
        return $this->hasOne(ProjectType::class, 'id', 'type_id');
    }

    public function client() {
        return $this->hasOne(Relation::class, 'id', 'client_id');
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
}
