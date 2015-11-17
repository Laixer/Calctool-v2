<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use \Calctool\Models\WholesaleType;
use \Calctool\Models\Wholesale;
use \Calctool\Models\Supplier;
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
		$type_bouw = WholesaleType::where('type_name','=','bouw')->first();
		//$type_installatie = WholesaleType::where('type_name','=','installatie')->first();
		//$type_electra = WholesaleType::where('type_name','=','electra')->first();
		//$type_schilder = WholesaleType::where('type_name','=','schilder')->first();
		//$type_hovenier = WholesaleType::where('type_name','=','hovenier')->first();
		//$type_sanitair = WholesaleType::where('type_name','=','sanitair')->first();
		$type_verhuur = WholesaleType::where('type_name','=','verhuur')->first();
		//$type_groothandel = WholesaleType::where('type_name','=','groothandel')->first();
		//$type_veiling = WholesaleType::where('type_name','=','veiling')->first();
		//$type_overig = WholesaleType::where('type_name','=','overig')->first();


		$wholesale = new Wholesale;
		$wholesale->company_name = "Bouwmaat NL";
		$wholesale->type_id = $type_bouw->id;
		$wholesale->phone = "0332992800";
		$wholesale->email = "klantenservice@bouwmaat.nl";
		$wholesale->website = "https://www.bouwmaat.nl";
		$wholesale->address_street = "Nijverheidsweg";
		$wholesale->address_number = "17";
		$wholesale->address_postal = "3751LP";
		$wholesale->address_city = "Bunschoten";
		$wholesale->province_id = $province->id;
		$wholesale->country_id = $country->id;
		$wholesale->save();
		Supplier::create(array('wholesale_id' => $wholesale->id));

		$wholesale = new Wholesale;
		$wholesale->company_name = "Stiho Schiedam";
		$wholesale->type_id = $type_bouw->id;
		$wholesale->phone = "0104273333";
		$wholesale->email = "stihoschiedam@stiho.nl";
		$wholesale->website = "https://www.stiho.nl";
		$wholesale->address_street = "J. van Galenstraat";
		$wholesale->address_number = "15";
		$wholesale->address_postal = "3115JG";
		$wholesale->address_city = "Shiedam";
		$wholesale->province_id = $province->id;
		$wholesale->country_id = $country->id;
		$wholesale->save();
		Supplier::create(array('wholesale_id' => $wholesale->id));

		$wholesale = new Wholesale;
		$wholesale->company_name = "Bo-Rent Schiedam";
		$wholesale->type_id = $type_verhuur->id;
		$wholesale->phone = "0786170800";
		$wholesale->email = "info@bo-rent.nl";
		$wholesale->website = "https://www.bo-rent.nl";
		$wholesale->address_street = "Diodeweg";
		$wholesale->address_number = "12";
		$wholesale->address_postal = "1627LL";
		$wholesale->address_city = "Hoorn";
		$wholesale->province_id = $province->id;
		$wholesale->country_id = $country->id;
		$wholesale->save();
		Supplier::create(array('wholesale_id' => $wholesale->id));

		/*Supplier::create(array('supplier_name' => 'bouwmaat'));
		Supplier::create(array('supplier_name' => 'stiho'));
		Supplier::create(array('supplier_name' => 'bo-rent'));
		Supplier::create(array('supplier_name' => 'boels'));
		Supplier::create(array('supplier_name' => 'megamat'));
		Supplier::create(array('supplier_name' => 'jongeneel'));
		Supplier::create(array('supplier_name' => 'hornbach'));
		Supplier::create(array('supplier_name' => 'technische unie'));
		Supplier::create(array('supplier_name' => 'simonis'));
		Supplier::create(array('supplier_name' => 'spr coatings'));
		Supplier::create(array('supplier_name' => 'destil'));
		Supplier::create(array('supplier_name' => 'sigma coatings'));
		Supplier::create(array('supplier_name' => 'molenaar'));
		$this->command->info('Supplier created');*/

	}
 }
 