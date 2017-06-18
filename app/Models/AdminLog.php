<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{

    protected $table = 'admin_log';

    // public function user() {
    //     return $this->hasOne('User');
    // }

    public function label() {
        return $this->hasOne('BynqIO\Dynq\Models\AdminLogLabel', 'id', 'label_id');
    }

}
