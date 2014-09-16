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
			ProjectType::create(array('type_name' => 'blanco offerte'));
			ProjectType::create(array('type_name' => 'blanco factuur'));
			
			user_account::create(array(
				'username' => 'usernamesystem'
				'secret' => 'ABC123'
				'firstname' => 'firstnamesystem'
				'Lastname' => 'lastnamesystem'
				'Api' => ''
				'ip' => '127.0.0.1'
				'Active' => 'Y'
				'Confirmed_mail' => 'Y'
				'registration_date' => '41898'
				'last_active' => 'ABC'
				'Promotion_code' => '123'
				'Address_street' => 'adresssystem'
				'Address_number' => '01'
				'Address_postal' => '1234AB'
				'Address_city' => 'citysystem'
				'Website' => 'www.calctool.nl'
				'Note' => 'system_user'
				'Mobile' => '612345678'
				'Phone' => '101234567'
				'Email' => 'info@calctool.nl'
				'Pref_mailings_optin' => 'Y'
				'Pref_hourrate_calc' => '35'
				'Pref_hourdate_more' => '45'
				'Pref_profit_calc_contr_mat' => '1'
				'Pref_profit_calc_contr_equip' => '2'
				'Pref_profit_calc_subcontr_mat' => '3'
				'Pref_profit_calc_subcontr_equip' => '4'
				'Pref_profit_calc_estim_mat' => '5'
				'Pref_profit_calc_estim_equip' => '6'
				'Pref_profit_more_contr_mat' => '7'
				'Pref_profit_more_contr_equip' => '8'
				'Pref_profit_more_subcontr_mat' => '9'
				'Pref_profit_more_subcontr_equip' => '10'
				'Pref_email_offer' => 'test_pref_email_offer'
				'Pref_offer_description' => 'pref_offer_description'
				'Pref_closure_offer' => 'pref_closure_offer'
				'Pref_email_invoice' => 'pref_email_invoice'
				'Pref_invoice_description' => 'pref_invoice_description'
				'Pref_invoice_closure' => 'pref_invoice_closure'
				'Pref_email_invoice_first_reminder' => 'pref_email_invoice_first_reminder'
				'Pref_email_invoice_last_reminder' => 'pref_email_invoice_last_reminder'
				'Pref_email_invoice_first_demand' => 'pref_email_invoice_first_demand'
				'Pref_email_invoice_last_demand' => 'pref_email_invoice_last_demand'
				'Administration_cost' => '12,5'
				));
	}
 
}