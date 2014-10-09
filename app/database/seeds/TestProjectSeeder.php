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

		$test_user_type = UserType::where('user_type','=','user')->first();
		$test_province = Province::where('province_name','=','zuid-holland')->first();
		$test_country = Country::where('country_name','=','duitsland')->first();
		$test_projecttype = ProjectType::where('type_name','=','calculatie')->first();
		$test_contact_function = ContactFunction::where('function_name','=','voorzitter')->first();
		$test_type_name = RelationType::where('type_name','=','adviesbureau')->first();
		$test_kind_name = RelationKind::where('kind_name','=','zakelijk')->first();
		$test_part = Part::where('part_name','=','subcontracting')->first();
		$test_part_type = PartType::where('type_name','=','calculation')->first();
		$test_detail = Detail::where('detail_name','=','more')->first();
		$test_step = ProjectStep::where('step_name','=','estimate')->first();
		$deliver = DeliverTime::where('delivertime_name','=','3 weken')->first();
		$specification = Specification::where('specification_name','=','gespecificeerd, exclusief omschrijving')->first();
		$valid = Valid::where('valid_name','=','3 maanden')->first();

		$test_user = User::create(array(
			'username' => 'system',
			'secret' => Hash::make('ABC@123'),
			'firstname' => 'system_firtname',
			'lastname' => 'system_lastname',
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
			'user_type' => $test_user_type->id,
			'self_id' => NULL
		));

		Payment::create(array(
			'payment_date' => '08-10-2014',
			'due_date' => '12-08-2014',
			'amount' => '1250',
			'payed' => 'N',
			'note' => 'note on payment',
			'user_id' => $test_user->id
		));

		$test_resource1 = Resource::create(array(
			'resource_name' => 'resource naam',
			'file_location' => 'dit is natuurlijk een link',
			'file_size' => '1000',
			'description' => 'beschrijving_bij_afbeeding',
			'user_id' => $test_user->id,
			'project_id' => NULL
		));

		$test_relation = Relation::create(array(
			'company_name' => 'bedrijfsnaam relatie',
			'address_street' => 'straat',
			'address_number' => '11',
			'address_postal' => '1234AB',
			'address_city' => 'Rotterdam',
			'kvk' => '1234567',
			'btw' => '12345678912345',
			'debtor_code' => 'UG83824',
			'phone' => '0612345678',
			'email' => 'info@website.nl',
			'note' => 'omschrijving relatie',
			'website' => 'www.website.nl',
			'logo_id' => $test_resource1->id,
			'user_id' => $test_user->id,
			'type_id' => $test_type_name->id,
			'kind_id' => $test_kind_name->id,
			'province_id' => $test_province->id,
			'country_id' => $test_country->id
		));

		$test_project = Project::create(array(
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
			'province_id' => $test_province->id,
			'country_id' => $test_country->id,
			'type_id' => $test_projecttype->id,
			'client_id' => $test_relation->id
		));

		ProjectStatus::create(array(
			'finish' => '2014-04-11',
			'step_id' => $test_step->id,
			'project_id' => $test_project->id
		));

		Resource::create(array(
			'resource_name' => 'resource naam2',
			'file_location' => 'dit is natuurlijk een link2',
			'file_size' => '10002',
			'description' => 'beschrijving_bij_afbeeding2',
			'user_id' => $test_user->id,
			'project_id' => $test_project->id
		));

		Contact::create(array(
			'firstname' => 'voornaam',
			'lastname' => 'achternaam',
			'email' => 'info@naam.nl',
			'mobile' => '0612345678',
			'phone' => '0101234567',
			'note' => 'omschrijving contact',
			'relation_id' => $test_relation->id,
			'function_id' => $test_contact_function->id
		));

		Iban::create(array(
			'iban' => 'NL45RABO0123456789m',
			'iban_name' => 'system_iban',
			'user_id' => $test_user->id,
			'relation_id' => $test_relation->id,
		));

		$test_chapter1 = Chapter::create(array(
			'chapter_name' => 'lHoofdstuk 1',
			'priority' => '1',
			'note' => 'omschrijving van hoofdstuk 1',
			'project_id' => $test_project->id
		));

		$test_activity1 = Activity::create(array(
			'activity_name' => 'Werkzaamheid a',
			'priority' => '2',
			'note' => 'omschrijving van werkzaamheid a',
			'chapter_id' => $test_chapter1->id,
			'part_id' => $test_part->id,
			'part_type_id' => $test_part_type->id,
			'detail_id' => $test_detail->id
		));

		$test_activity3 = Activity::create(array(
			'activity_name' => 'Werkzaamheid c',
			'priority' => '3',
			'note' => 'omschrijving van werkzaamheid c',
			'chapter_id' => $test_chapter1->id,
			'part_id' => $test_part->id,
			'part_type_id' => $test_part_type->id,
			'detail_id' => $test_detail->id
		));

		$test_chapter2 = Chapter::create(array(
			'chapter_name' => 'Hoofdstuk 2',
			'priority' => '4',
			'note' => 'omschrijving van hoofdstuk 2',
			'project_id' => $test_project->id
		));

		$test_activity2 = Activity::create(array(
			'activity_name' => 'Werkzaamheid b',
			'priority' => '5',
			'note' => 'omschrijving van werkzaamheid b',
			'chapter_id' => $test_chapter2->id,
			'part_id' => $test_part->id,
			'part_type_id' => $test_part_type->id,
			'detail_id' => $test_detail->id
		));

		$test_activity4 = Activity::create(array(
			'activity_name' => 'Werkzaamheid d',
			'priority' => '6',
			'note' => 'omschrijving van werkzaamheid d',
			'chapter_id' => $test_chapter2->id,
			'part_id' => $test_part->id,
			'part_type_id' => $test_part_type->id,
			'detail_id' => $test_detail->id
		));

		Timesheet::create(array(
			'register_date' => '09-10-2014',
			'register_hour' => '24',
			'note' => 'omschrijving bij uren invoer',
			'part_id' => $test_part->id,
			'part_type_id' => $test_part_type->id,
			'detail_id' => $test_detail->id,
			'project_id' => $test_project->id
		));

		Purchase::create(array(
			'amount' => '33',
			'note' => 'aankoop factuu omschrijving',
			'register_date' => '09-10-2014',
			'part_id' => $test_part->id,
			'project_id' => $test_project->id,
			'relation_id' => $test_relation->id
		));

		$test_offer = Offer::create(array(
			'display_tax' => 'Y',
			'description' => 'omschrijving voor op offerte',
			'closure' => 'dit was de omschrijving voor op offerte',
			'end_invoice' => 'Y',
			'auto_email_reminder' => 'Y',
			'offer_finish' => '01-11-2014',
			'deliver_id' => $deliver->id,
			'specification_id' => $specification->id,
			'valid_id' => $valid->id,
			'project_id' => $test_project->id
		));

		Invoice::create(array(
			'invoice_close' => 'Y',
			'description' => 'omschrijving',
			'reference' => '24366',
			'invoice_code' => '8765432',
			'book_code' => '8765432123',
			'amount' => '1230',
			'payment_condition' => '30',
			'payment_date' => '12-10-2014',
			'display_tax' => 'Y',
			'closure' => 'dit was de clusure tekst',
			'auto_email_reminder' => 'Y',
			'closure' => 'dit was de clusure tekst',
			'offer_id' => $test_offer->id,
		));
	}
 }
