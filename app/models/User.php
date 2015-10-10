<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

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
		return $this->belongsToMany('Product', 'product_favorite', 'user_id', 'product_id');
	}

	public function isAdmin() {
		return in_array(UserType::find($this->user_type)->user_type, array('admin', 'system'));
	}

	public function hasPayed() {
		return (strtotime($this->expiration_date) >= strtotime(date('Y-m-d')));
	}

}
