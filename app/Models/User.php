<?php

namespace Calctool\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

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
		return $this->belongsToMany('\Calctool\Models\Product', 'product_favorite', 'user_id', 'product_id');
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
		return (strtotime($this->expiration_date) >= time());
	}

	public function isAlmostDue() {
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

	public function isOnline() {
		return $this->updated_at->diffInSeconds() < 60;
	}

	public function currentStatus() {
		if ($this->updated_at->diffInSeconds() < 60)
			return "Online";
		return $this->updated_at->diffForHumans();
	}
}
