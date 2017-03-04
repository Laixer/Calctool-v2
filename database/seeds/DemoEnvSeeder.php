<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use \Calctool\Models\UserType;
use \Calctool\Models\User;
use \Calctool\Models\Relation;
use \Calctool\Models\Contact;
use \Calctool\Models\MessageBox;
use \Calctool\Models\Resource;

/*
 * Static Models Only
 * Test are performed on other seeds
 */
class DemoEnvSeeder extends Seeder {

	public function run()
	{
		DB::table('system_message')->delete();
		$user_type = UserType::where('user_type', 'demo')->first();

		$demo_user = new User;
		$demo_user->username = 'mitch';
		$demo_user->secret = Hash::make('deurzen');
		$demo_user->firstname = 'Mitch';
		$demo_user->lastname = 'Deurzen';
		$demo_user->ip = '::1';
		$demo_user->active = 'Y';
		$demo_user->confirmed_mail = date('Y-m-d');
		$demo_user->registration_date = date('Y-m-d');
		$demo_user->expiration_date = date('Y-m-d', strtotime("+100 month", time()));
		$demo_user->referral_key = md5(mt_rand());
		$demo_user->mobile = '0612345678';
		$demo_user->phone = '01012345763';
		$demo_user->email = 'info@mitchdeurzen.nl';
		$demo_user->website = 'https://www.mitchdeurzen.nl/';
		$demo_user->user_type = $user_type->id;
		$demo_user->user_group = 100;
		$demo_user->pref_hourrate_calc = 35;
		$demo_user->pref_hourrate_more = 45;
		$demo_user->gender = 'M';
		$demo_user->save();

		$relation = new Relation;
		$relation->user_id = $demo_user->id;
		$relation->kind_id = 1;
		$relation->debtor_code = 'DEMO42';
		$relation->company_name = 'Deurzen Onderhoud';
		$relation->type_id = 28;
		$relation->kvk = '54565243';
		$relation->btw = 'NL851353423B01';
		$relation->phone = '0612345678';
		$relation->email = 'info@mitchdeurzen.nl';
		$relation->website = 'https://www.mitchdeurzen.nl/';
		$relation->address_street = 'Straatweg';
		$relation->address_number = '11';
		$relation->address_postal = '3116CD';
		$relation->address_city = 'Rotterdam';
		$relation->province_id = 9;
		$relation->country_id = 34;
		$relation->iban = 'NL29INGB0006863509';
		$relation->iban_name = 'DEURZEN ONDERHOUD';
		$relation->save();

		$demo_user->self_id = $relation->id;
		$demo_user->save();

		$contact = new Contact;
		$contact->salutation = 'Dhr.';
		$contact->firstname = 'Mitch';
		$contact->lastname = 'Deurzen';
		$contact->mobile = '0612345678';
		$contact->phone = '01012345763';
		$contact->email = 'mitch@mitchdeurzen.nl';
		$contact->relation_id = $relation->id;
		$contact->function_id = 7;
		$contact->gender = 'M';
		$contact->save();

		$resource = new Resource;
		$resource->resource_name = md5(mt_rand());
		$resource->file_location = 'images/logo-demo.png';
		$resource->file_size = 29385;
		$resource->user_id = $demo_user->id;
		$resource->description = 'Relatielogo';

		$resource->save();

		$relation->logo_id = $resource->id;
		$relation->save();
	}
 }
