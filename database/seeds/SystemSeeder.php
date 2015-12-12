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
		$user_user_type = UserType::where('user_type','=','user')->first();

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
			'expiration_date' => date('Y-m-d', strtotime("+100 year", time())),
			'referral_key' => md5(mt_rand()),
			'email' => 'info@calctool.nl',
			'user_type' => $system_user_type->id
		));

		$cal_user = User::create(array(
			'username' => 'cal',
			'secret' => Hash::make('ABC@123'),
			'firstname' => 'Cal',
			'api' => md5(mt_rand()),
			'token' => sha1(Hash::make('ABC@123')),
			'ip' => '::1',
			'active' => 'Y',
			'confirmed_mail' => date('Y-m-d'),
			'registration_date' => date('Y-m-d'),
			'expiration_date' => date('Y-m-d', strtotime("+100 year", time())),
			'referral_key' => md5(mt_rand()),
			'email' => 'cal@calctool.nl',
			'user_type' => $user_user_type->id
		));

		MessageBox::create(array(
			'subject' => 'Standaard notificatie',
			'message' => 'Beste Systeem,<br /><br />Het systeem is geladen en de database is opgebouwd. Materialendatabase kan nog ontbreken als deze niet is geladen via de commandline.<br />Vergeet niet de standaardmelding te verwijderen als alles in orde is.<br /><br />Systeem',
			'from_user' => $system_user->id,
			'user_id' => $system_user->id
		));
	}
 }
