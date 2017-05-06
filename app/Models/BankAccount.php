<?php

namespace BynqIO\Dynq\Models;

use BynqIO\Dynq\Models\Traits\Ownable;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use Ownable;

    protected $table = 'bank_account';
    protected $guarded = array('id');
}
