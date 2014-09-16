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
			
			user_account::create(array(
				'id'=> 1, 'username' => 'usernamesystem',
				'id'=> 2, 'secret' => 'ABC123',
				'id'=> 3, 'firstname' => 'firstnamesystem',
				'id'=> 4, 'Lastname' => 'lastnamesystem',
				'id'=> 5, 'Api' => 'ABC',
				'id'=> 6, 'ip' => '127.0.0.1',
				'id'=> 7, 'Active' => 'Y',
				'id'=> 8, 'Confirmed_mail' => 'Y',
				'id'=> 9, 'registration_date' => '2014-09-16',
				'id'=> 10, 'last_active' => '2014-09-15',
				'id'=> 11, 'Promotion_code' => '123',
				'id'=> 12, 'Address_street' => 'adressystem',
				'id'=> 13, 'Address_number' => '1',
				'id'=> 14, 'Address_postal' => '1234AB',
				'id'=> 15, 'Address_city' => 'citysystem',
				'id'=> 16, 'Website' => 'www.calctool.nl',
				'id'=> 17, 'Note' => 'system user',
				'id'=> 18, 'Mobile' => '612345678',
				'id'=> 19, 'Phone' => '101234567',
				'id'=> 20, 'Email' => 'info@calctool.nl',
				'id'=> 21, 'Pref_mailings_optin' => 'Y',
				'id'=> 22, 'Pref_hourrate_calc' => '35',
				'id'=> 23, 'Pref_hourdate_more' => '45',
				'id'=> 24, 'Pref_profit_calc_contr_mat' => '1',
				'id'=> 25, 'Pref_profit_calc_contr_equip' => '2',
				'id'=> 26, 'Pref_profit_calc_subcontr_mat' => '3',
				'id'=> 27, 'Pref_profit_calc_subcontr_equip' => '4',
				'id'=> 28, 'Pref_profit_calc_estim_mat' => '5',
				'id'=> 29, 'Pref_profit_calc_estim_equip' => '6',
				'id'=> 30, 'Pref_profit_more_contr_mat' => '7',
				'id'=> 31, 'Pref_profit_more_contr_equip' => '8',
				'id'=> 32, 'Pref_profit_more_subcontr_mat' => '9',
				'id'=> 33, 'Pref_profit_more_subcontr_equip' => '10',
				'id'=> 34, 'Pref_email_offer' => 'test_pref_email_offer',
				'id'=> 35, 'Pref_offer_description' => 'pref_offer_description',
				'id'=> 36, 'Pref_closure_offer' => 'pref_closure_offer',
				'id'=> 37, 'Pref_email_invoice' => 'pref_email_invoice',
				'id'=> 38, 'Pref_invoice_description' => 'pref_invoice_description',
				'id'=> 39, 'Pref_invoice_closure' => 'pref_invoice_closure',
				'id'=> 40, 'Pref_email_invoice_first_reminder' => 'pref_email_invoice_first_reminder',
				'id'=> 41, 'Pref_email_invoice_last_reminder' => 'pref_email_invoice_last_reminder',
				'id'=> 42, 'Pref_email_invoice_first_demand' => 'pref_email_invoice_first_demand',
				'id'=> 43, 'Pref_email_invoice_last_demand' => 'pref_email_invoice_last_demand',
				'id'=> 44, 'Administration_cost' => '12.50',
			));

			project::create(array(
				'id'=> 1, 'Project_name' => 'testproject',
				'id'=> 2, 'Project_code' => '123456',
				'id'=> 3, 'Address_street' => 'teststraat',
				'id'=> 4, 'Address_number' => '01',
				'id'=> 5, 'Address_postal' => '1234AB',
				'id'=> 6, 'Address_city' => 'testscity',
				'id'=> 7, 'Registration_date' => '2014-09-16',
				'id'=> 8, 'Note' => 'testopmerking',
				'id'=> 9, 'Hour_rate' => '36',
				'id'=> 10, 'Hour_rate_more' => '37',
				'id'=> 11, 'Profit_calc_contr_mat' => '11',
				'id'=> 12, 'Profit_calc_contr_equip' => '12',
				'id'=> 13, 'Profit_calc_subcontr_mat' => '13',
				'id'=> 14, 'Profit_calc_subcontr_equip' => '14',
				'id'=> 15, 'Profit_calc_estim_mat' => '15',
				'id'=> 16, 'Profit_calc_estim_equip' => '16',
				'id'=> 17, 'Profit_more_contr_mat' => '17',
				'id'=> 18, 'Profit_more_contr_equip' => '18',
				'id'=> 19, 'Profit_more_subcontr_mat' => '19',
				'id'=> 20, 'Profit_more_subcontr_equip' => '20',
			));
	}
 }