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

class Payment extends Model
{
    use Ownable;

    protected $table = 'payment';
    protected $guarded = array('id', 'transaction');

    public function getStatusName() {
        switch ($this->status) {
            case 'paid':
                return 'Betaald';
            case 'cancelled':
                return 'Afgebroken';
            case 'expired':
                return 'Verlopen';
            case 'open':
                return 'Open';
            
            default:
                return $this->status;
        }
    }

    public function getTypeName() {
        switch ($this->recurring_type) {
            case 'first':
                return 'Eerste betaling met incasso';
            
            default:
                return 'Eenmalige afschrijving';
        }
    }
}
