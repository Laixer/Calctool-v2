<?php

namespace BynqIO\Dynq\Models;

use BynqIO\Dynq\Models\Traits\Ownable;
use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    use Ownable;

    protected $table = 'relation';
    protected $guarded = ['id', 'debtor_code'];

    // public function user() {
    //     return $this->hasOne('User');
    // }

    // public function province() {
    //     return $this->hasOne('Province');
    // }

    public function contacts() {
        return $this->hasMany(Contact::class);
    }

    // public function country() {
    //     return $this->hasOne('Country');
    // }

    // public function resource() {
    //     return $this->hasOne('Resource');
    // }

    // public function type() {
    //     return $this->hasOne('RelationType');
    // }

    /**
     * Get the project slug name.
     *
     * @param  string  $value
     * @return string
     */
    public function slug()
    {
        return strtolower(str_slug(substr($this->name(), 0, 25)));
    }

    public function kind() {
        return $this->hasOne(RelationKind::class, 'id', 'kind_id')->first(); //change
    }

    public function isActive() {
        return $this->active;
    }

    public function isBusiness() {
        return $this->kind()->kind_name == 'zakelijk';
    }

    public function logo() {
        return $this->hasOne(Resource::class, 'id', 'logo_id');
    }

    public function name() {
        if ($this->isBusiness()) {
            return $this->company_name;
        } else {
            return Contact::where('relation_id','=',$this->id)->first()['firstname'] . ' ' . Contact::where('relation_id','=',$this->id)->first()['lastname'];
        }
    }

    public function encodedName() {
        return str_replace(' ', '_', strtolower($this->name()));
    }

    public function fullAddress() {
        return "$this->address_street $this->address_number, $this->address_postal, $this->address_city";
    }
}
