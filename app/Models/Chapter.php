<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model {

    protected $table = 'chapter';
    protected $guarded = array('id');

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function activities() {
        return $this->hasMany(Activity::class);
    }

}
