<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use \Calctool\Models\UserType;
use \Calctool\Models\User;
use \Calctool\Models\Relation;
use \Calctool\Models\Contact;
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
		$system_user->username = 'admin';
		$system_user->secret = Hash::make('ABC@123');
		$system_user->firstname = 'SYSTEM';
		$system_user->api = md5(mt_rand());
		$system_user->token = sha1(Hash::make('ABC@123'));
		$system_user->ip = '::1';
		$system_user->active = 'Y';
		$system_user->confirmed_mail = date('Y-m-d');
		$system_user->registration_date = date('Y-m-d');
		$system_user->expiration_date = date('Y-m-d', strtotime("+100 month", time()));
		$system_user->referral_key = md5(mt_rand());
		$system_user->email = 'info@calculatietool.com';
		$system_user->user_type = $system_user_type->id;
		$system_user->user_group = 100;
		$system_user->save();

		$guest_user = new User;
		$guest_user->username = 'guest';
		$guest_user->secret = Hash::make('ABC@123');
		$guest_user->firstname = 'Guest';
		$guest_user->api = md5(mt_rand());
		$guest_user->token = sha1(Hash::make('ABC@123'));
		$guest_user->ip = '::1';
		$guest_user->active = 'N';
		$guest_user->confirmed_mail = date('Y-m-d');
		$guest_user->registration_date = date('Y-m-d');
		$guest_user->expiration_date = date('Y-m-d', strtotime("+1 month", time()));
		$guest_user->referral_key = md5(mt_rand());
		$guest_user->email = 'guest@calctool.nl';
		$guest_user->user_type = $guest_user_type->id;
		$guest_user->user_group = 100;
		$guest_user->save();

		$relation = new Relation;
		$relation->user_id = $guest_user->id;
		$relation->kind_id = 1;
		$relation->debtor_code = '12345';
		$relation->company_name = 'CalculatieTool.com';
		$relation->type_id = 28;
		$relation->kvk = '54565243';
		$relation->btw = 'NL851353423B01';
		$relation->phone = '0612345678';
		$relation->email = 'info@calculatietool.com';
		$relation->website = 'http://www.calculatietool.com';
		$relation->address_street = 'Odinholm';
		$relation->address_number = '25';
		$relation->address_postal = '3124SC';
		$relation->address_city = 'Schiedam';
		$relation->province_id = 9;
		$relation->country_id = 34;
		$relation->iban = 'NL29INGB0006863509';
		$relation->iban_name = 'CalculatieTool.com';
		$relation->save();

		$guest_user->self_id = $relation->id;
		$guest_user->save();

		$contact = new Contact;
		$contact->firstname = 'Cal';
		$contact->lastname = 'CT';
		$contact->mobile = '0612345678';
		$contact->phone = '01012345763';
		$contact->email = 'info@calculatietool.com';
		$contact->relation_id = $relation->id;
		$contact->function_id = 7;
		$contact->gender = 'M';
		$contact->save();

		$message = new MessageBox;
		$message->subject = 'Standaard notificatie';
		$message->message = 'Beste Systeem,<br /><br />Het systeem is geladen en de database is opgebouwd. Materialendatabase kan nog ontbreken als deze niet is geladen via de commandline.<br />Vergeet niet de standaardmelding te verwijderen als alles in orde is.<br /><br />Systeem';
		$message->from_user = $system_user->id;
		$message->user_id = $system_user->id;
		$message->save();
	}
 }
