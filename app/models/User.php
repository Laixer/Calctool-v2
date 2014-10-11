<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $table = 'user_account';
	protected $hidden = array('secret', 'remember_token', 'api', 'promotion_code');
	protected $guarded = array('id', 'ip', 'secret', 'remember_token', 'api', 'promotion_code');

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

}
