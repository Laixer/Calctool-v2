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
class DemoEnvSeeder extends Seeder {

	public function run()
	{
		$user_type = UserType::where('user_type', 'user')->first();

		$demo_user = new User;
		$demo_user->username = 'demo';
		$demo_user->secret = Hash::make('demo');
		$demo_user->firstname = 'Demo';
		$demo_user->ip = '::1';
		$demo_user->active = 'Y';
		$demo_user->confirmed_mail = date('Y-m-d');
		$demo_user->registration_date = date('Y-m-d');
		$demo_user->expiration_date = date('Y-m-d', strtotime("+100 month", time()));
		$demo_user->referral_key = md5(mt_rand());
		$demo_user->email = 'demo@calculatietool.com';
		$demo_user->user_type = $user_type->id;
		$demo_user->user_group = 100;
		$demo_user->save();

		$relation = new Relation;
		$relation->user_id = $demo_user->id;
		$relation->kind_id = 1;
		$relation->debtor_code = 'DEMO42';
		$relation->company_name = 'CalculatieTool.com';
		$relation->type_id = 28;
		$relation->kvk = '54565243';
		$relation->btw = 'NL851353423B01';
		$relation->phone = '0612345678';
		$relation->email = 'demo@calculatietool.com';
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

		$demo_user->self_id = $relation->id;
		$demo_user->save();

		$contact = new Contact;
		$contact->salutation = 'Dhr.';
		$contact->firstname = 'Arie';
		$contact->lastname = 'Kaas';
		$contact->mobile = '0612345678';
		$contact->phone = '01012345763';
		$contact->email = 'demo@calculatietool.com';
		$contact->relation_id = $relation->id;
		$contact->function_id = 7;
		$contact->gender = 'M';
		$contact->save();
	}
 }
