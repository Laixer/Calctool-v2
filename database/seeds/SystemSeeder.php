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

		$system_user = User::create(array(
			'username' => 'system',
			'secret' => Hash::make('ABC@123'),
			'firstname' => 'SYSTEM',
			'api' => md5(mt_rand()),
			'token' => sha1(Hash::make('ABC@123')),
			'ip' => '::1',
			'active' => 'Y',
			'confirmed_mail' => date('Y-m-d'),
			'registration_date' => date('Y-m-d'),
			'expiration_date' => date('Y-m-d', strtotime("+1 month", time())), // date('Y-m-d', strtotime("+100 year", time())),
			'referral_key' => md5(mt_rand()),
			'email' => 'info@calculatietool.com',
			'user_type' => $system_user_type->id
		));

		$guest_user = User::create(array(
			'username' => 'guest',
			'secret' => Hash::make('ABC@123'),
			'firstname' => 'Guest',
			'api' => md5(mt_rand()),
			'token' => sha1(Hash::make('ABC@123')),
			'ip' => '::1',
			'active' => 'Y',
			'confirmed_mail' => date('Y-m-d'),
			'registration_date' => date('Y-m-d'),
			'expiration_date' => date('Y-m-d', strtotime("+1 month", time())), // date('Y-m-d', strtotime("+100 year", time())),
			'referral_key' => md5(mt_rand()),
			'email' => 'guest@calctool.nl',
			'user_type' => $guest_user_type->id
		));

		MessageBox::create(array(
			'subject' => 'Standaard notificatie',
			'message' => 'Beste Systeem,<br /><br />Het systeem is geladen en de database is opgebouwd. Materialendatabase kan nog ontbreken als deze niet is geladen via de commandline.<br />Vergeet niet de standaardmelding te verwijderen als alles in orde is.<br /><br />Systeem',
			'from_user' => $system_user->id,
			'user_id' => $system_user->id
		));
	}
 }
