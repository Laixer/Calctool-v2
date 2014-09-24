<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_account';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('secret', 'remember_token', 'api');
	
	protected $fillable = array('username', 'firstname', 'lastname', 'email');

	protected $guarded = array('id', 'promotion_code');

	public function projects() {
		return $this->hasMany('Project');
	}

	public function type() {
		return $this->hasOne('UserType');
	}
}
