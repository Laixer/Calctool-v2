<?php

class UserSeeder extends Seeder {

	public function run()
	{
		Project::truncate();
		User::truncate();
		Country::truncate();
		Provance::truncate();
		UserType::truncate();
		
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

		$test_user = User::create(array(
			'username' => 'usernamesystem',
			'secret' => 'ABC123',
			'firstname' => 'firstnamesystem',
			'Lastname' => 'lastnamesystem',
			'Api' => 'ABC',
			'ip' => '127.0.0.1',
			'Active' => 'Y',
			'Confirmed_mail' => 'Y',
			'registration_date' => '2014-09-16',
			'last_active' => '2014-09-15',
			'Promotion_code' => '123',
			'Address_street' => 'adressystem',
			'Address_number' => '1',
			'Address_postal' => '1234AB',
			'Address_city' => 'citysystem',
			'Website' => 'www.calctool.nl',
			'Note' => 'system user',
			'Mobile' => '612345678',
			'Phone' => '101234567',
			'Email' => 'info@calctool.nl',
			'Pref_mailings_optin' => 'Y',
			'Pref_hourrate_calc' => '35',
			'Pref_hourdate_more' => '45',
			'Pref_profit_calc_contr_mat' => '1',
			'Pref_profit_calc_contr_equip' => '2',
			'Pref_profit_calc_subcontr_mat' => '3',
			'Pref_profit_calc_subcontr_equip' => '4',
			'Pref_profit_calc_estim_mat' => '5',
			'Pref_profit_calc_estim_equip' => '6',
			'Pref_profit_more_contr_mat' => '7',
			'Pref_profit_more_contr_equip' => '8',
			'Pref_profit_more_subcontr_mat' => '9',
			'Pref_profit_more_subcontr_equip' => '10',
			'Pref_email_offer' => 'test_pref_email_offer',
			'Pref_offer_description' => 'pref_offer_description',
			'Pref_closure_offer' => 'pref_closure_offer',
			'Pref_email_invoice' => 'pref_email_invoice',
			'Pref_invoice_description' => 'pref_invoice_description',
			'Pref_invoice_closure' => 'pref_invoice_closure',
			'Pref_email_invoice_first_reminder' => 'pref_email_invoice_first_reminder',
			'Pref_email_invoice_last_reminder' => 'pref_email_invoice_last_reminder',
			'Pref_email_invoice_first_demand' => 'pref_email_invoice_first_demand',
			'Pref_email_invoice_last_demand' => 'pref_email_invoice_last_demand',
			'Administration_cost' => '12.50',
		));

		Project::create(array(
			'Project_name' => 'testproject',
			'Project_code' => '123456',
			'Address_street' => 'teststraat',
			'Address_number' => '01',
			'Address_postal' => '1234AB',
			'Address_city' => 'testscity',
			'Registration_date' => '2014-09-16',
			'Note' => 'testopmerking',
			'Hour_rate' => '36',
			'Hour_rate_more' => '37',
			'Profit_calc_contr_mat' => '11',
			'Profit_calc_contr_equip' => '12',
			'Profit_calc_subcontr_mat' => '13',
			'Profit_calc_subcontr_equip' => '14',
			'Profit_calc_estim_mat' => '15',
			'Profit_calc_estim_equip' => '16',
			'Profit_more_contr_mat' => '17',
			'Profit_more_contr_equip' => '18',
			'Profit_more_subcontr_mat' => '19',
			'Profit_more_subcontr_equip' => '20',
			'user_id' => '$test_user->id',
			'provance_id' => '9',
			'country_id' => '14',
			'type_id' => '2',
		));

	}
 }