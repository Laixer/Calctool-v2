<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'offer';
    protected $guarded = array('id');

    public function invoices() {
        return $this->hasMany(Invoice::class);
    }

    public function fromContact() {
        return $this->hasOne(Contact::class, 'id', 'from_contact_id');
    }

    public function toContact() {
        return $this->hasOne(Contact::class, 'id', 'to_contact_id');
    }

    // public function deliverTime() {
    //     return $this->hasOne('DeliverTime');
    // }

    // public function specification() {
    //     return $this->hasOne('Specification');
    // }

    // public function valid() {
    //     return $this->hasOne('Valid');
    // }

    // public function project() {
    //     return $this->hasOne('Project');
    // }

}
