<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use \Calctool\Models\UserType;
use \Calctool\Models\User;
use \Calctool\Models\MessageBox;

/*
 * Static Models Only
 * Test are performed on other seeds
 */
class SystemSeeder extends Seeder {

	public function run()
	{
		DB::table('user_account')->delete();
		$this->command->info('Tables deleted');

		$system_user_type = UserType::where('user_type','=','system')->first();
		$guest_user_type = UserType::where('user_type','=','guest')->first();

		$system_user = new User;
		$system_user->username = 'system';
		$system_user->secret = Hash::make('ABC@123');
		$system_user->firstname = 'SYSTEM';
		$system_user->api = md5(mt_rand());
		$system_user->token = sha1(Hash::make('ABC@123'));
		$system_user->ip = '::1';
		$system_user->active = 'Y';
		$system_user->confirmed_mail = date('Y-m-d');
		$system_user->registration_date = date('Y-m-d');
		$system_user->expiration_date = date('Y-m-d', strtotime("+1 month", time())); // date('Y-m-d', strtotime("+100 year", time())),
		$system_user->referral_key = md5(mt_rand());
		$system_user->email = 'info@calculatietool.com';
		$system_user->user_type = $system_user_type->id;
		$system_user->save();

		$guest_user = new User;
		$guest_user->username = 'guest';
		$guest_user->secret = Hash::make('ABC@123');
		$guest_user->firstname = 'Guest';
		$guest_user->api = md5(mt_rand());
		$guest_user->token = sha1(Hash::make('ABC@123'));
		$guest_user->ip = '::1';
		$guest_user->active = 'Y';
		$guest_user->confirmed_mail = date('Y-m-d');
		$guest_user->registration_date = date('Y-m-d');
		$guest_user->expiration_date = date('Y-m-d', strtotime("+1 month", time())); // date('Y-m-d', strtotime("+100 year", time())),
		$guest_user->referral_key = md5(mt_rand());
		$guest_user->email = 'guest@calctool.nl';
		$guest_user->user_type = $guest_user_type->id;
		$guest_user->save();

		$message = new MessageBox;
		$message->subject = 'Standaard notificatie';
		$message->message = 'Beste Systeem,<br /><br />Het systeem is geladen en de database is opgebouwd. Materialendatabase kan nog ontbreken als deze niet is geladen via de commandline.<br />Vergeet niet de standaardmelding te verwijderen als alles in orde is.<br /><br />Systeem';
		$message->from_user = $system_user->id;
		$message->user_id = $system_user->id;
		$message->save();
	}
 }
