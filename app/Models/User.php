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

	public function isAdmin() {
		return in_array(UserType::find($this->user_type)->user_type, array('admin', 'system'));
	}

	public function hasPayed() {
		return (strtotime($this->expiration_date) >= strtotime(date('Y-m-d')));
	}

}
