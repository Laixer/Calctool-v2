<?php

namespace BynqIO\Dynq\Models;

use BynqIO\Dynq\Models\Traits\Ownable;
use Illuminate\Database\Eloquent\Model;

class FavoriteActivity extends Model
{
    use Ownable;

    protected $table = 'favorite_activity';
    protected $guarded =['id'];

    // public function user() {
    //     return $this->hasOne('User');
    // }

    // public function tax() {
    //     return $this->hasOne('Tax', 'id', 'tax_id');
    // }

    public function isEstimate()
    {
        return false;
    }

    public function isMore()
    {
        return false;
    }

    public function isSubcontracting()
    {
        return false;
    }

}
