<?php

namespace BynqIO\CalculatieTool\Models;

use BynqIO\CalculatieTool\Models\Traits\Ownable;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use Ownable;

    protected $table = 'bank_account';
    protected $guarded = array('id');
}
