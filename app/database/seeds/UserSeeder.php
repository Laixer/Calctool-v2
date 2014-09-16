<?php

class UserSeeder extends Seeder {

	public function run()
	{
			UserType::create(array('id' => 1, 'user_type' => 'zakelijk'));
			UserType::create(array('id' => 2, 'user_type' => 'particulier'));

			Provance::create(array('id' => 1, 'provance_name' => 'Groningen'));
			Provance::create(array('id' => 2, 'provance_name' => 'Friesland'));
			Provance::create(array('id' => 3, 'provance_name' => 'Drenthe'));
			Provance::create(array('id' => 4, 'provance_name' => 'Overijssel'));
			Provance::create(array('id' => 5, 'provance_name' => 'Flevoland'));
			Provance::create(array('id' => 6, 'provance_name' => 'Gelderland'));
			Provance::create(array('id' => 7, 'provance_name' => 'Utrecht'));
			Provance::create(array('id' => 8, 'provance_name' => 'Noord-Holland'));
			Provance::create(array('id' => 9, 'provance_name' => 'Zuid-Holland'));
			Provance::create(array('id' => 10, 'provance_name' => 'Noord-Brabant'));
			Provance::create(array('id' => 11, 'provance_name' => 'Limburg'));
			Provance::create(array('id' => 12, 'provance_name' => 'Zeeland'));
			Provance::create(array('id' => 13, 'provance_name' => 'Overig'));

			Country::create(array('id' => 1, 'country_name' => 'Albanië'));
			Country::create(array('id' => 2, 'country_name' => 'Andorra'));
			Country::create(array('id' => 3, 'country_name' => 'Armenië'));
			Country::create(array('id' => 4, 'country_name' => 'Azerbeidzjan'));
			Country::create(array('id' => 5, 'country_name' => 'België'));
			Country::create(array('id' => 6, 'country_name' => 'BosniëenHerzegovina'));
			Country::create(array('id' => 7, 'country_name' => 'Bulgarije'));
			Country::create(array('id' => 8, 'country_name' => 'Cyprus'));
			Country::create(array('id' => 9, 'country_name' => 'Denemarken'));
			Country::create(array('id' => 10, 'country_name' => 'Duitsland'));
			Country::create(array('id' => 11, 'country_name' => 'Estland'));
			Country::create(array('id' => 12, 'country_name' => 'aeröer'));
			Country::create(array('id' => 13, 'country_name' => 'Finland'));
			Country::create(array('id' => 14, 'country_name' => 'Frankrijk'));
			Country::create(array('id' => 15, 'country_name' => 'Georgië'));
			Country::create(array('id' => 16, 'country_name' => 'Griekenland'));
			Country::create(array('id' => 17, 'country_name' => 'Groenland'));
			Country::create(array('id' => 18, 'country_name' => 'Hongarije'));
			Country::create(array('id' => 19, 'country_name' => 'Ierland'));
			Country::create(array('id' => 20, 'country_name' => 'IJsland'));
			Country::create(array('id' => 21, 'country_name' => 'Italië'));
			Country::create(array('id' => 22, 'country_name' => 'Kazachstan'));
			Country::create(array('id' => 23, 'country_name' => 'Kosovo'));
			Country::create(array('id' => 24, 'country_name' => 'Kroatië'));
			Country::create(array('id' => 25, 'country_name' => 'Letland'));
			Country::create(array('id' => 26, 'country_name' => 'Liechtenstein'));
			Country::create(array('id' => 27, 'country_name' => 'Litouwen'));
			Country::create(array('id' => 28, 'country_name' => 'Luxemburg'));
			Country::create(array('id' => 29, 'country_name' => 'Macedonië'));
			Country::create(array('id' => 30, 'country_name' => 'Malta'));
			Country::create(array('id' => 31, 'country_name' => 'Moldavië'));
			Country::create(array('id' => 32, 'country_name' => 'Monaco'));
			Country::create(array('id' => 33, 'country_name' => 'Montenegro'));
			Country::create(array('id' => 34, 'country_name' => 'Nederland'));
			Country::create(array('id' => 35, 'country_name' => 'Noorwegen'));
			Country::create(array('id' => 36, 'country_name' => 'Oekraïne'));
			Country::create(array('id' => 37, 'country_name' => 'Oostenrijk'));
			Country::create(array('id' => 38, 'country_name' => 'Polen'));
			Country::create(array('id' => 39, 'country_name' => 'Portugal'));
			Country::create(array('id' => 40, 'country_name' => 'Roemenië'));
			Country::create(array('id' => 41, 'country_name' => 'Rusland'));
			Country::create(array('id' => 42, 'country_name' => 'SanMarino'));
			Country::create(array('id' => 43, 'country_name' => 'Servië'));
			Country::create(array('id' => 44, 'country_name' => 'Slovenië'));
			Country::create(array('id' => 45, 'country_name' => 'Slowakije'));
			Country::create(array('id' => 46, 'country_name' => 'Spanje'));
			Country::create(array('id' => 47, 'country_name' => 'Tsjechië'));
			Country::create(array('id' => 48, 'country_name' => 'Turkije'));
			Country::create(array('id' => 49, 'country_name' => 'Vaticaanstad'));
			Country::create(array('id' => 50, 'country_name' => 'Verenigd Koninkrijk'));
			Country::create(array('id' => 51, 'country_name' => 'Wit-Rusland'));
			Country::create(array('id' => 52, 'country_name' => 'Zweden'));
			Country::create(array('id' => 53, 'country_name' => 'Zwitserland'));

			ProjectType::create(array('id' => 1, 'type_name' => 'regie'));
			ProjectType::create(array('id' => 2, 'type_name' => 'calculatie'));
			ProjectType::create(array('id' => 3, 'type_name' => 'blanco offerte'));
			ProjectType::create(array('id' => 4, 'type_name' => 'blanco factuur'));
			
			User::create(array(
				'username' => 'usernamesystem',
				'secret' => Hash::make('ABC123'),
				'firstname' => 'firstnamesystem',
				'lastname' => 'lastnamesystem',
				'api' => md5(mt_rand()),
				'ip' => '127.0.0.1',
				'active' => 'Y',
				'confirmed_mail' => '2014-09-16',
				'registration_date' => '2014-09-14',
				'last_active' => '2014-09-16',
				'promotion_code' => md5(mt_rand()),
				'address_street' => 'adresssystem',
				'address_number' => '01',
				'address_postal' => '1234AB',
				'address_city' => 'citysystem',
				'website' => 'www.calctool.nl',
				'note' => 'system_user',
				'mobile' => '612345678',
				'phone' => '101234567',
				'email' => 'info@calctool.nl',
				'pref_mailings_optin' => 'Y',
				'pref_hourrate_calc' => '35',
				'pref_hourrate_more' => '45',
				'pref_profit_calc_contr_mat' => '1',
				'pref_profit_calc_contr_equip' => '2',
				'pref_profit_calc_subcontr_mat' => '3',
				'pref_profit_calc_subcontr_equip' => '4',
				'pref_profit_calc_estim_mat' => '5',
				'pref_profit_calc_estim_equip' => '6',
				'pref_profit_more_contr_mat' => '7',
				'pref_profit_more_contr_equip' => '8',
				'pref_profit_more_subcontr_mat' => '9',
				'pref_profit_more_subcontr_equip' => '10',
				'pref_email_offer' => 'test_pref_email_offer',
				'pref_offer_description' => 'pref_offer_description',
				'pref_closure_offer' => 'pref_closure_offer',
				'pref_email_invoice' => 'pref_email_invoice',
				'pref_invoice_description' => 'pref_invoice_description',
				'pref_invoice_closure' => 'pref_invoice_closure',
				'pref_email_invoice_first_reminder' => 'pref_email_invoice_first_reminder',
				'pref_email_invoice_last_reminder' => 'pref_email_invoice_last_reminder',
				'pref_email_invoice_first_demand' => 'pref_email_invoice_first_demand',
				'pref_email_invoice_last_demand' => 'pref_email_invoice_last_demand',
				'administration_cost' => '12.5',
				'user_type' => 1
			));
	}
 
}