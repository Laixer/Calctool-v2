<?php

namespace CalculatieTool\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Carbon\Carbon;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract {

    use Authenticatable, Authorizable, CanResetPassword;

    protected $table = 'user_account';
    protected $hidden = array('secret', 'remember_token', 'api', 'promotion_code', 'note');
    protected $guarded = array('id', 'ip', 'secret', 'remember_token', 'api', 'promotion_code', 'note');

    public function getAuthPassword(){
        return $this->secret;
    }

    public function projects() {
        return $this->hasMany('Project');
    }

    public function type() {
        return $this->hasOne('UserType');
    }

    public function productFavorite() {
        return $this->belongsToMany('\CalculatieTool\Models\Product', 'product_favorite', 'user_id', 'product_id');
    }

    public function tag() {
        return $this->hasOne('\CalculatieTool\Models\UserTag', 'id', 'user_tag_id');
    }

    public function isSuperUser() {
        return in_array(UserType::find($this->user_type)->user_type, array('superuser', 'admin', 'system'));
    }

    public function isAdmin() {
        return in_array(UserType::find($this->user_type)->user_type, array('admin', 'system'));
    }

    public function isSystem() {
        return UserType::find($this->user_type)->user_type == 'system';
    }

    public function hasPayed() {
        if ($this->isAdmin())
            return true;

        return (strtotime($this->expiration_date) >= time());
    }

    public function isTryPeriod() {
        return (strtotime($this->confirmed_mail . "+30 days") > time());
    }

    public function isNewPeriod()
    {
        return $this->login_count < 5;
    }

    public function isAlmostDue() {
        if ($this->isAdmin())
            return false;

        return strtotime($this->expiration_date . "-5 days") == strtotime(date('Y-m-d'));
    }

    public function canArchive() {
        return (strtotime($this->registration_date . "+2 days") < time());
    }

    public function myCompany() {
        if ($this->self_id)
            return Relation::find($this->self_id);
        else
            return null;
    }

    public function encodedName() {
        return str_replace(' ', '_', strtolower($this->username));
    }

    public function dueDateHuman() {
        $date =  strftime('%e %B %Y', strtotime($this->expiration_date));
        $en_months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $nl_months = array("Januari", "Februari", "Maart", "April", "Mei", "Juni", "Juli", "Augustus", "September", "Oktober", "November", "December");
        return str_replace($en_months, $nl_months, $date);
    }

    public function monthsBehind() {
        $d1 = new \DateTime($this->expiration_date);
        $d2 = new \DateTime();

        if ($d1 > $d2)
            return 0;

        $interval = $d2->diff($d1);

        return $d1->diff($d2)->m + ($d1->diff($d2)->y*12) + 1;
    }

    public function humanTiming($time) {
        $time = time() - $time; // to get the time since that moment
        $time = ($time<1)? 1 : $time;
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        $units = NULL;
        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            $units = $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
            break;
        }

        return $units . ' ago';
    }

    public function isOnline() {
        if (!$this->online_at)
            return false;

        $d1 = new \DateTime($this->online_at);
        $d2 = new \DateTime();

        $diffInSeconds = $d2->getTimestamp() - $d1->getTimestamp();
        if ($diffInSeconds < 60)
            return true;

        return false;
    }

    public function currentStatus() {
        if (!$this->online_at)
            return 'Never';
        
        $d1 = new \DateTime($this->online_at);
        $d2 = new \DateTime();

        $diffInSeconds = $d2->getTimestamp() - $d1->getTimestamp();
        if ($diffInSeconds < 60)
            return "Online";

        return $this->humanTiming($d1->getTimestamp());
    }
}
