<?php

class StaticSeeder extends Seeder {

	public function run()
	{
		DB::table('project_step')->delete();
		DB::table('project_type')->delete();
		DB::table('country')->delete();
		DB::table('provance')->delete();
		DB::table('user_type')->delete();
		$this->command->info('Tables deleted');
		
		UserType::create(array('user_type' => 'zakelijk'));
		UserType::create(array('user_type' => 'particulier'));
		$this->command->info('UserType created');

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
		$this->command->info('Provance created');

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
		$this->command->info('Country created');

		$ProjectType1 = ProjectType::create(array('type_name' => 'regie'));
		$ProjectType2 = ProjectType::create(array('type_name' => 'calculatie'));
		$ProjectType3 = ProjectType::create(array('type_name' => 'blanco offerte'));
		$ProjectType4 = ProjectType::create(array('type_name' => 'blanco factuur'));
		$this->command->info('ProjectType created');

		$ProjectStep1 = ProjectStep::create(array('step_name' => 'calculation'));
		$ProjectStep2 = ProjectStep::create(array('step_name' => 'offer'));
		$ProjectStep3 = ProjectStep::create(array('step_name' => 'contracting'));
		$ProjectStep4 = ProjectStep::create(array('step_name' => 'estimate'));
		$ProjectStep5 = ProjectStep::create(array('step_name' => 'more'));
		$ProjectStep6 = ProjectStep::create(array('step_name' => 'less'));
		$ProjectStep7 = ProjectStep::create(array('step_name' => 'invoice'));
		$this->command->info('ProjectStep created');

		$ProjectType1->projectStep()->attach($ProjectStep5->id);
		$ProjectType1->projectStep()->attach($ProjectStep7->id);

		$ProjectType2->projectStep()->attach($ProjectStep1->id);
		$ProjectType2->projectStep()->attach($ProjectStep2->id);
		$ProjectType2->projectStep()->attach($ProjectStep3->id);
		$ProjectType2->projectStep()->attach($ProjectStep4->id);
		$ProjectType2->projectStep()->attach($ProjectStep5->id);
		$ProjectType2->projectStep()->attach($ProjectStep6->id);
		$ProjectType2->projectStep()->attach($ProjectStep7->id);

		$ProjectType3->projectStep()->attach($ProjectStep2->id);
		$ProjectType3->projectStep()->attach($ProjectStep7->id);

		$ProjectType4->projectStep()->attach($ProjectStep2->id);
		$this->command->info('ProjectType / ProjectStep attached');

/*		$test_user = User::create(array(
			'username' => 'system',
			'secret' => Hash::make('ABC@123'),
			'firstname' => 'system',
			'lastname' => 'system',
			'api' => md5(mt_rand()),
			'ip' => '127.0.0.1',
			'active' => 'Y',
			'confirmed_mail' => '2014-09-16',
			'registration_date' => '2014-09-16',
			'last_active' => '2014-09-15',
			'promotion_code' => md5(mt_rand()),
			'address_street' => 'adressystem',
			'address_number' => '1',
			'address_postal' => '1234AB',
			'address_city' => 'citysystem',
			'website' => 'www.calctool.nl',
			'note' => 'system user',
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
			'administration_cost' => '12.50',
			'user_type' => 1
		));*/

/*		Project::create(array(
			'project_name' => 'testproject',
			'project_code' => '123456',
			'address_street' => 'teststraat',
			'address_number' => '01',
			'address_postal' => '1234AB',
			'address_city' => 'testscity',
			'note' => 'testopmerking',
			'hour_rate' => '36',
			'hour_rate_more' => '37',
			'profit_calc_contr_mat' => '11',
			'profit_calc_contr_equip' => '12',
			'profit_calc_subcontr_mat' => '13',
			'profit_calc_subcontr_equip' => '14',
			'profit_calc_estim_mat' => '15',
			'profit_calc_estim_equip' => '16',
			'profit_more_contr_mat' => '17',
			'profit_more_contr_equip' => '18',
			'profit_more_subcontr_mat' => '19',
			'profit_more_subcontr_equip' => '20',
			'user_id' => $test_user->id,
			'provance_id' => 9,
			'country_id' => 14,
			'type_id' => 2,
		));*/

	}
 }
