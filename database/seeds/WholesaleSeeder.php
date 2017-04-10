<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use \CalculatieTool\Models\WholesaleType;
use \CalculatieTool\Models\Wholesale;
use \CalculatieTool\Models\Supplier;
use \CalculatieTool\Models\Province;
use \CalculatieTool\Models\Country;

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
        $type_ijzerwaren = WholesaleType::where('type_name','=','ijzerwaren')->first();
        $type_verf = WholesaleType::where('type_name','=','verf')->first();
        //$type_hovenier = WholesaleType::where('type_name','=','hovenier')->first();
        //$type_sanitair = WholesaleType::where('type_name','=','sanitair')->first();
        $type_verhuur = WholesaleType::where('type_name','=','verhuur')->first();
        $type_electra = WholesaleType::where('type_name','=','electra')->first();
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
        $wholesale->company_name = "Stiho NL";
        $wholesale->type_id = $type_bouw->id;
        $wholesale->phone = "0302808280";
        $wholesale->email = "info@destihogroep.nl";
        $wholesale->website = "http://www.destihogroep.nl/";
        $wholesale->address_street = "Laagraven";
        $wholesale->address_number = "44";
        $wholesale->address_postal = "3439LK";
        $wholesale->address_city = "Nieuwegein";
        $wholesale->province_id = $province->id;
        $wholesale->country_id = $country->id;
        $wholesale->save();
        Supplier::create(array('wholesale_id' => $wholesale->id));

        $wholesale = new Wholesale;
        $wholesale->company_name = "Megamat Rotterdam";
        $wholesale->type_id = $type_bouw->id;
        $wholesale->phone = " 0102382777";
        $wholesale->email = "info@megamat.nl";
        $wholesale->website = "http://www.megamat.nl/";
        $wholesale->address_street = "Vareseweg";
        $wholesale->address_number = "20";
        $wholesale->address_postal = "3047AV";
        $wholesale->address_city = "Rotterdam";
        $wholesale->province_id = $province->id;
        $wholesale->country_id = $country->id;
        $wholesale->save();
        Supplier::create(array('wholesale_id' => $wholesale->id));

        $wholesale = new Wholesale;
        $wholesale->company_name = "Koninklijke Jongeneel B.V.";
        $wholesale->type_id = $type_bouw->id;
        $wholesale->phone = "0302346347";
        $wholesale->email = "info@jongeneel.nl";
        $wholesale->website = "http://www.jongeneel.nl/";
        $wholesale->address_street = "Atoomweg";
        $wholesale->address_number = "300";
        $wholesale->address_postal = "3500AA";
        $wholesale->address_city = "Utrecht";
        $wholesale->province_id = $province->id;
        $wholesale->country_id = $country->id;
        $wholesale->save();
        Supplier::create(array('wholesale_id' => $wholesale->id));

        $wholesale = new Wholesale;
        $wholesale->company_name = "Raab Karcher";
        $wholesale->type_id = $type_bouw->id;
        $wholesale->phone = "0134580200";
        $wholesale->email = "tilburg@raabkarcher.nl";
        $wholesale->website = "http://www.raabkarcher.nl";
        $wholesale->address_street = "Jules Verneweg";
        $wholesale->address_number = "104";
        $wholesale->address_postal = "5015BM";
        $wholesale->address_city = "Tilburg";
        $wholesale->province_id = $province->id;
        $wholesale->country_id = $country->id;
        $wholesale->save();
        Supplier::create(array('wholesale_id' => $wholesale->id));

        $wholesale = new Wholesale;
        $wholesale->company_name = "PontMeyer";
        $wholesale->type_id = $type_bouw->id;
        $wholesale->phone = "0756536262";
        $wholesale->email = "info@pontmeyer.nl";
        $wholesale->website = "http://www.pontmeyer.nl";
        $wholesale->address_street = "Symon Spiersweg";
        $wholesale->address_number = "17";
        $wholesale->address_postal = "1506RZ";
        $wholesale->address_city = "Zaandam";
        $wholesale->province_id = $province->id;
        $wholesale->country_id = $country->id;
        $wholesale->save();
        Supplier::create(array('wholesale_id' => $wholesale->id));

        $wholesale = new Wholesale;
        $wholesale->company_name = "Breur";
        $wholesale->type_id = $type_ijzerwaren->id;
        $wholesale->phone = "0102888444";
        $wholesale->email = "info@breur.nl";
        $wholesale->website = "http://www.breur.nl/";
        $wholesale->address_street = "Rivium Boulevard";
        $wholesale->address_number = "147";
        $wholesale->address_postal = "2909LK";
        $wholesale->address_city = "Capelle a/d IJssel";
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

        $wholesale = new Wholesale;
        $wholesale->company_name = "Boels";
        $wholesale->type_id = $type_verhuur->id;
        $wholesale->phone = "900463626357";
        $wholesale->email = "klantenservice@boels.nl";
        $wholesale->website = "http://www.boels.nl/";
        $wholesale->address_street = "Dr. Nolenslaan";
        $wholesale->address_number = "140";
        $wholesale->address_postal = "6136GV";
        $wholesale->address_city = "Sittard";
        $wholesale->province_id = $province->id;
        $wholesale->country_id = $country->id;
        $wholesale->save();
        Supplier::create(array('wholesale_id' => $wholesale->id));

        $wholesale = new Wholesale;
        $wholesale->company_name = "Hornbach";
        $wholesale->type_id = $type_bouw->id;
        $wholesale->phone = "0302669898";
        $wholesale->email = "info_nl@hornbach.com";
        $wholesale->website = "http://www.hornbach.nl/";
        $wholesale->address_street = "Ravenswade";
        $wholesale->address_number = "56";
        $wholesale->address_postal = "3439LD";
        $wholesale->address_city = "Nieuwegein";
        $wholesale->province_id = $province->id;
        $wholesale->country_id = $country->id;
        $wholesale->save();
        Supplier::create(array('wholesale_id' => $wholesale->id));

        $wholesale = new Wholesale;
        $wholesale->company_name = "Technische unie";
        $wholesale->type_id = $type_electra->id;
        $wholesale->phone = "0205450345";
        $wholesale->email = "communicatie@technischeunie.com";
        $wholesale->website = "https://www.mijntu.nl/";
        $wholesale->address_street = "Bovenkerkerweg";
        $wholesale->address_number = "10";
        $wholesale->address_postal = "1185XE";
        $wholesale->address_city = "Amstelveen";
        $wholesale->province_id = $province->id;
        $wholesale->country_id = $country->id;
        $wholesale->save();
        Supplier::create(array('wholesale_id' => $wholesale->id));

        $wholesale = new Wholesale;
        $wholesale->company_name = "Simonis";
        $wholesale->type_id = $type_verf->id;
        $wholesale->phone = "0104322888";
        $wholesale->email = "info@simonisverf.nl";
        $wholesale->website = "http://simonisverf.com/";
        $wholesale->address_street = "Berkenwoudestraat";
        $wholesale->address_number = "22";
        $wholesale->address_postal = "3076JA";
        $wholesale->address_city = "Rotterdam-Zuid";
        $wholesale->province_id = $province->id;
        $wholesale->country_id = $country->id;
        $wholesale->save();
        Supplier::create(array('wholesale_id' => $wholesale->id));

        $wholesale = new Wholesale;
        $wholesale->company_name = "Spr Coatings";
        $wholesale->type_id = $type_verf->id;
        $wholesale->phone = "0104796999";
        $wholesale->email = "info@sprcoatings.nl";
        $wholesale->website = "http://www.sprcoatings.nl/";
        $wholesale->address_street = "Bankwerkerstraat";
        $wholesale->address_number = "15";
        $wholesale->address_postal = "3077MB";
        $wholesale->address_city = "Rotterdam";
        $wholesale->province_id = $province->id;
        $wholesale->country_id = $country->id;
        $wholesale->save();
        Supplier::create(array('wholesale_id' => $wholesale->id));

        $wholesale = new Wholesale;
        $wholesale->company_name = "Destil";
        $wholesale->type_id = $type_ijzerwaren->id;
        $wholesale->phone = "0134653000";
        $wholesale->email = "http://www.destil.nl";
        $wholesale->website = "info@destil.nl";
        $wholesale->address_street = "Laurent Janssensstraat";
        $wholesale->address_number = "101";
        $wholesale->address_postal = "5048AR";
        $wholesale->address_city = "Tilburg-Noord";
        $wholesale->province_id = $province->id;
        $wholesale->country_id = $country->id;
        $wholesale->save();
        Supplier::create(array('wholesale_id' => $wholesale->id));

        $wholesale = new Wholesale;
        $wholesale->company_name = "Sigma Coatings";
        $wholesale->type_id = $type_verf->id;
        $wholesale->phone = "0297541911";
        $wholesale->email = "http://www.sigma.nl/";
        $wholesale->website = "info@sigma.nl";
        $wholesale->address_street = "Amsterdamseweg";
        $wholesale->address_number = "14";
        $wholesale->address_postal = "1422AD";
        $wholesale->address_city = "Uithoorn";
        $wholesale->province_id = $province->id;
        $wholesale->country_id = $country->id;
        $wholesale->save();
        Supplier::create(array('wholesale_id' => $wholesale->id));

    }
 }
 