<?php

/*
 * Static Models Only
 * Test are performed on other seeds
 */
class TestProjectSeeder extends Seeder {

	public function run()
	{
		DB::table('user_account')->delete();
		DB::table('project')->delete();
		$this->command->info('Tables deleted');

		$test_user = User::create(array(
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
