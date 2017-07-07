<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activity';
    protected $guarded = ['id'];

    public function __construct()
    {
        $this->priority = 0;
        $this->part_id = 1;
        $this->part_type_id = 1;
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    // public function tax() {
    //     return $this->hasOne('Tax', 'id', 'tax_id');
    // }

    public function isEstimate()
    {
        return PartType::findOrFail($this->part_type_id)->type_name == 'estimate';
    }

    public function isMore()
    {
        return Detail::findOrFail($this->detail_id)->detail_name == 'more';
    }

    public function isSubcontracting()
    {
        return Part::findOrFail($this->part_id)->part_name == 'subcontracting';
    }
}
