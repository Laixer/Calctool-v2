<?php

class UserSeeder extends Seeder {

	public function run()
	{
			UserType::create(array('user_type' => 'zakelijk'));
			UserType::create(array('user_type' => 'particulier'));

			Provance::create(array('provance_name' => 'Groningen'));
			Provance::create(array('provance_name' => 'Friesland'));
			Provance::create(array('provance_name' => 'Drenthe'));
			Provance::create(array('provance_name' => 'Overijssel'));
			Provance::create(array('provance_name' => 'Flevoland'));
			Provance::create(array('provance_name' => 'Gelderland'));
			Provance::create(array('provance_name' => 'Utrecht'));
			Provance::create(array('provance_name' => 'Noord-Holland'));
			Provance::create(array('provance_name' => 'Zuid-Holland'));
			Provance::create(array('provance_name' => 'Noord-Brabant'));
			Provance::create(array('provance_name' => 'Limburg'));
			Provance::create(array('provance_name' => 'Zeeland'));
			Provance::create(array('provance_name' => 'Overig'));

			Country::create(array('country_name' => 'Albanië'));
			Country::create(array('country_name' => 'Andorra'));
			Country::create(array('country_name' => 'Armenië'));
			Country::create(array('country_name' => 'Azerbeidzjan'));
			Country::create(array('country_name' => 'België'));
			Country::create(array('country_name' => 'BosniëenHerzegovina'));
			Country::create(array('country_name' => 'Bulgarije'));
			Country::create(array('country_name' => 'Cyprus'));
			Country::create(array('country_name' => 'Denemarken'));
			Country::create(array('country_name' => 'Duitsland'));
			Country::create(array('country_name' => 'Estland'));
			Country::create(array('country_name' => 'aeröer'));
			Country::create(array('country_name' => 'Finland'));
			Country::create(array('country_name' => 'Frankrijk'));
			Country::create(array('country_name' => 'Georgië'));
			Country::create(array('country_name' => 'Griekenland'));
			Country::create(array('country_name' => 'Groenland'));
			Country::create(array('country_name' => 'Hongarije'));
			Country::create(array('country_name' => 'Ierland'));
			Country::create(array('country_name' => 'IJsland'));
			Country::create(array('country_name' => 'Italië'));
			Country::create(array('country_name' => 'Kazachstan'));
			Country::create(array('country_name' => 'Kosovo'));
			Country::create(array('country_name' => 'Kroatië'));
			Country::create(array('country_name' => 'Letland'));
			Country::create(array('country_name' => 'Liechtenstein'));
			Country::create(array('country_name' => 'Litouwen'));
			Country::create(array('country_name' => 'Luxemburg'));
			Country::create(array('country_name' => 'Macedonië'));
			Country::create(array('country_name' => 'Malta'));
			Country::create(array('country_name' => 'Moldavië'));
			Country::create(array('country_name' => 'Monaco'));
			Country::create(array('country_name' => 'Montenegro'));
			Country::create(array('country_name' => 'Nederland'));
			Country::create(array('country_name' => 'Noorwegen'));
			Country::create(array('country_name' => 'Oekraïne'));
			Country::create(array('country_name' => 'Oostenrijk'));
			Country::create(array('country_name' => 'Polen'));
			Country::create(array('country_name' => 'Portugal'));
			Country::create(array('country_name' => 'Roemenië'));
			Country::create(array('country_name' => 'Rusland'));
			Country::create(array('country_name' => 'SanMarino'));
			Country::create(array('country_name' => 'Servië'));
			Country::create(array('country_name' => 'Slovenië'));
			Country::create(array('country_name' => 'Slowakije'));
			Country::create(array('country_name' => 'Spanje'));
			Country::create(array('country_name' => 'Tsjechië'));
			Country::create(array('country_name' => 'Turkije'));
			Country::create(array('country_name' => 'Vaticaanstad'));
			Country::create(array('country_name' => 'Verenigd Koninkrijk'));
			Country::create(array('country_name' => 'Wit-Rusland'));
			Country::create(array('country_name' => 'Zweden'));
			Country::create(array('country_name' => 'Zwitserland'));

			ProjectType::create(array('type_name' => 'regie'));
			ProjectType::create(array('type_name' => 'calculatie'));
			ProjectType::create(array('type_name' => 'blancoofferte'));
			ProjectType::create(array('type_name' => 'blancofactuur'));

	}
 
}