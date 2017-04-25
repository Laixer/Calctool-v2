<?php

namespace BynqIO\CalculatieTool\Models;

use BynqIO\CalculatieTool\Models\Traits\Ownable;
use Illuminate\Database\Eloquent\Model;

class FavoriteActivity extends Model
{
    use Ownable;

    protected $table = 'favorite_activity';
    protected $guarded = array('id');

    // public function user() {
    //     return $this->hasOne('User');
    // }

    // public function tax() {
    //     return $this->hasOne('Tax', 'id', 'tax_id');
    // }
}
