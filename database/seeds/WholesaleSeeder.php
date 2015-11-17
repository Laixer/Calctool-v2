<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use \Calctool\Models\WholesaleType;
use \Calctool\Models\Wholesale;
use \Calctool\Models\Province;
use \Calctool\Models\Country;

/*
 * Static Models Only
 * Test are performed on other seeds
 */
class WholesaleSeeder extends Seeder {

	public function run()
	{
		DB::table('wholesale')->delete();
		$this->command->info('Tables deleted');

		$province = Province::where('province_name','=','zuid-holland')->first();
		$country = Country::where('country_name','=','nederland')->first();
		$test_type_bouw = WholesaleType::where('type_name','=','bouw')->first();

		/* Test leverancier */
		$wholesale = new Wholesale;
		$wholesale->company_name = "TestCorp";
		$wholesale->type_id = $test_type_bouw->id;
		$wholesale->phone = "0104752745";
		$wholesale->email = "info@ct.nl";
		$wholesale->website = "https://www.ct.nl";

		/* Adress */
		$wholesale->address_street = "Hoofdweg";
		$wholesale->address_number = "71a";
		$wholesale->address_postal = "8241EA";
		$wholesale->address_city = "010";
		$wholesale->province_id = $province->id;
		$wholesale->country_id = $country->id;

		$wholesale->save();

	}
 }
