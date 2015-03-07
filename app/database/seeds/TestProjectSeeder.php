<?php

/*
 * Static Models Only
 * Test are performed on other seeds
 */
class TestProjectSeeder extends Seeder {

	public function run()
	{
		DB::table('product_favorite')->delete();
		DB::table('estimate_equipment')->delete();
		DB::table('estimate_material')->delete();
		DB::table('estimate_labor')->delete();
		DB::table('more_equipment')->delete();
		DB::table('more_material')->delete();
		DB::table('more_labor')->delete();
		DB::table('less_equipment')->delete();
		DB::table('less_material')->delete();
		DB::table('less_labor')->delete();
		DB::table('calculation_equipment')->delete();
		DB::table('calculation_material')->delete();
		DB::table('calculation_labor')->delete();
		DB::table('invoice')->delete();
		DB::table('offer')->delete();
		DB::table('purchase')->delete();
		DB::table('timesheet')->delete();
		DB::table('activity')->delete();
		DB::table('chapter')->delete();
		DB::table('iban')->delete();
		DB::table('contact')->delete();
		DB::table('status_date')->delete();
		DB::table('project')->delete();
		DB::table('relation')->delete();
		DB::table('resource')->delete();
		DB::table('payment')->delete();
		DB::table('user_account')->delete();
		$this->command->info('Tables deleted');

		$test_user_type = UserType::where('user_type','=','user')->first();
		$test_province = Province::where('province_name','=','zuid-holland')->first();
		$test_country = Country::where('country_name','=','duitsland')->first();
		$test_projecttype = ProjectType::where('type_name','=','calculatie')->first();
		$test_contact_function = ContactFunction::where('function_name','=','voorzitter')->first();
		$test_type_name = RelationType::where('type_name','=','adviesbureau')->first();
		$test_kind_name = RelationKind::where('kind_name','=','zakelijk')->first();

		$test_part_contract = Part::where('part_name','=','contracting')->first();
		$test_part_type_calc = PartType::where('type_name','=','calculation')->first();

		$test_part_subcontract = Part::where('part_name','=','subcontracting')->first();
		$test_part_type_est = PartType::where('type_name','=','estimate')->first();

		$test_detail = Detail::where('detail_name','=','more')->first();
		$test_step = ProjectStep::where('step_name','=','estimate')->first();
		$test_deliver = DeliverTime::where('delivertime_name','=','3 weken')->first();
		$test_specification = Specification::where('specification_name','=','gespecificeerd, exclusief omschrijving')->first();
		$test_valid = Valid::where('valid_name','=','3 maanden')->first();
		$test_tax = Tax::where('tax_rate','=','21')->first();
		$test_product = Product::where('description','=','Hoge kast deur links 60cm met nis 450/485/880mm')->first();

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
		$this->command->info('User created');

		Payment::create(array(
			'payment_date' => '08-10-2014',
			'due_date' => '12-08-2014',
			'amount' => '1250',
			'payed' => 'N',
			'note' => 'note on payment',
			'user_id' => $test_user->id
		));
		$this->command->info('Payment created');

		$test_resource1 = Resource::create(array(
			'resource_name' => 'resource naam',
			'file_location' => 'dit is natuurlijk een link',
			'file_size' => '1000',
			'description' => 'beschrijving_bij_afbeeding',
			'user_id' => $test_user->id,
			'project_id' => NULL
		));
		$this->command->info('Resource created');

		$test_relation = Relation::create(array(
			'company_name' => 'bedrijfsnaam relatie',
			'address_street' => 'straat',
			'address_number' => '11',
			'address_postal' => '1234AB',
			'address_city' => 'Rotterdam',
			'kvk' => '174365278954',
			'btw' => '214855241B01',
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
		$this->command->info('Relation created');

		$test_project = Project::create(array(
			'project_name' => 'testproject',
			'project_code' => '123456',
			'address_street' => 'teststraat',
			'address_number' => '01',
			'address_postal' => '1234AB',
			'address_city' => 'testscity',
			'note' => 'testopmerking',
			'hour_rate' => '35',
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
		$this->command->info('Project created');

		ProjectStatus::create(array(
			'finish' => '2014-04-11',
			'step_id' => $test_step->id,
			'project_id' => $test_project->id
		));
		$this->command->info('ProjectStatus created');

		Resource::create(array(
			'resource_name' => 'resource naam2',
			'file_location' => 'dit is natuurlijk een link2',
			'file_size' => '10002',
			'description' => 'beschrijving_bij_afbeeding2',
			'user_id' => $test_user->id,
			'project_id' => $test_project->id
		));
		$this->command->info('Resource created');

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
		$this->command->info('Contact created');

		Iban::create(array(
			'iban' => 'NL45RABO0123456789m',
			'iban_name' => 'system_iban',
			'user_id' => $test_user->id,
			'relation_id' => $test_relation->id,
		));
		$this->command->info('Iban created');

		$test_chapter1 = Chapter::create(array(
			'chapter_name' => 'Hoofdstuk 1',
			'priority' => '1',
			'note' => 'omschrijving van hoofdstuk 1',
			'project_id' => $test_project->id
		));
		$this->command->info('Chapter created');

		$test_activity1 = Activity::create(array(
			'activity_name' => 'Werkzaamheid a',
			'priority' => '1',
			'note' => 'omschrijving van werkzaamheid a',
			'chapter_id' => $test_chapter1->id,
			'part_id' => $test_part_contract->id,
			'part_type_id' => $test_part_type_calc->id,
			'detail_id' => $test_detail->id,
			'tax_calc_labor_id' => $test_tax->id,
			'tax_calc_material_id' => $test_tax->id,
			'tax_calc_equipment_id' => $test_tax->id,
			'tax_more_labor_id' => $test_tax->id,
			'tax_more_material_id' => $test_tax->id,
			'tax_more_equipment_id' => $test_tax->id,
			'tax_estimate_labor_id' => $test_tax->id,
			'tax_estimate_material_id' => $test_tax->id,
			'tax_estimate_equipment_id' => $test_tax->id
		));
		$this->command->info('Activity created');

		$test_activity2 = Activity::create(array(
			'activity_name' => 'Werkzaamheid b',
			'priority' => '2',
			'note' => 'omschrijving van werkzaamheid b',
			'chapter_id' => $test_chapter1->id,
			'part_id' => $test_part_subcontract->id,
			'part_type_id' => $test_part_type_calc->id,
			'detail_id' => $test_detail->id,
			'tax_calc_labor_id' => $test_tax->id,
			'tax_calc_material_id' => $test_tax->id,
			'tax_calc_equipment_id' => $test_tax->id,
			'tax_more_labor_id' => $test_tax->id,
			'tax_more_material_id' => $test_tax->id,
			'tax_more_equipment_id' => $test_tax->id,
			'tax_estimate_labor_id' => $test_tax->id,
			'tax_estimate_material_id' => $test_tax->id,
			'tax_estimate_equipment_id' => $test_tax->id
		));
		$this->command->info('Activity created');

		$test_chapter2 = Chapter::create(array(
			'chapter_name' => 'Hoofdstuk 2',
			'priority' => '4',
			'note' => 'omschrijving van hoofdstuk 2',
			'project_id' => $test_project->id
		));
		$this->command->info('Chapter created');

		$test_activity3 = Activity::create(array(
			'activity_name' => 'Werkzaamheid d',
			'priority' => '2',
			'note' => 'omschrijving van werkzaamheid d',
			'chapter_id' => $test_chapter2->id,
			'part_id' => $test_part_contract->id,
			'part_type_id' => $test_part_type_calc->id,
			'detail_id' => $test_detail->id,
			'tax_calc_labor_id' => $test_tax->id,
			'tax_calc_material_id' => $test_tax->id,
			'tax_calc_equipment_id' => $test_tax->id,
			'tax_more_labor_id' => $test_tax->id,
			'tax_more_material_id' => $test_tax->id,
			'tax_more_equipment_id' => $test_tax->id,
			'tax_estimate_labor_id' => $test_tax->id,
			'tax_estimate_material_id' => $test_tax->id,
			'tax_estimate_equipment_id' => $test_tax->id
		));
		$this->command->info('Activity created');

		$test_activity4 = Activity::create(array(
			'activity_name' => 'Werkzaamheid e',
			'priority' => '4',
			'note' => 'omschrijving van werkzaamheid e',
			'chapter_id' => $test_chapter2->id,
			'part_id' => $test_part_subcontract->id,
			'part_type_id' => $test_part_type_calc->id,
			'detail_id' => $test_detail->id,
			'tax_calc_labor_id' => $test_tax->id,
			'tax_calc_material_id' => $test_tax->id,
			'tax_calc_equipment_id' => $test_tax->id,
			'tax_more_labor_id' => $test_tax->id,
			'tax_more_material_id' => $test_tax->id,
			'tax_more_equipment_id' => $test_tax->id,
			'tax_estimate_labor_id' => $test_tax->id,
			'tax_estimate_material_id' => $test_tax->id,
			'tax_estimate_equipment_id' => $test_tax->id
		));
		$this->command->info('Activity created');

		$test_chapter3 = Chapter::create(array(
			'chapter_name' => 'Hoofdstuk 2',
			'priority' => '4',
			'note' => 'omschrijving van hoofdstuk 2',
			'project_id' => $test_project->id
		));
		$this->command->info('Chapter created');

		$test_activity5 = Activity::create(array(
			'activity_name' => 'Werkzaamheid f',
			'priority' => '5',
			'note' => 'omschrijving van werkzaamheid f',
			'chapter_id' => $test_chapter3->id,
			'part_id' => $test_part_contract->id,
			'part_type_id' => $test_part_type_calc->id,
			'detail_id' => $test_detail->id,
			'tax_calc_labor_id' => $test_tax->id,
			'tax_calc_material_id' => $test_tax->id,
			'tax_calc_equipment_id' => $test_tax->id,
			'tax_more_labor_id' => $test_tax->id,
			'tax_more_material_id' => $test_tax->id,
			'tax_more_equipment_id' => $test_tax->id,
			'tax_estimate_labor_id' => $test_tax->id,
			'tax_estimate_material_id' => $test_tax->id,
			'tax_estimate_equipment_id' => $test_tax->id
		));
		$this->command->info('Activity created');

		$test_activity6 = Activity::create(array(
			'activity_name' => 'Werkzaamheid g',
			'priority' => '6',
			'note' => 'omschrijving van werkzaamheid g',
			'chapter_id' => $test_chapter3->id,
			'part_id' => $test_part_contract->id,
			'part_type_id' => $test_part_type_calc->id,
			'detail_id' => $test_detail->id,
			'tax_calc_labor_id' => $test_tax->id,
			'tax_calc_material_id' => $test_tax->id,
			'tax_calc_equipment_id' => $test_tax->id,
			'tax_more_labor_id' => $test_tax->id,
			'tax_more_material_id' => $test_tax->id,
			'tax_more_equipment_id' => $test_tax->id,
			'tax_estimate_labor_id' => $test_tax->id,
			'tax_estimate_material_id' => $test_tax->id,
			'tax_estimate_equipment_id' => $test_tax->id
		));
		$this->command->info('Activity created');

		$test_activity7 = Activity::create(array(
			'activity_name' => 'Werkzaamheid g',
			'priority' => '7',
			'note' => 'omschrijving van werkzaamheid g',
			'chapter_id' => $test_chapter3->id,
			'part_id' => $test_part_contract->id,
			'part_type_id' => $test_part_type_est->id,
			'detail_id' => $test_detail->id,
			'tax_calc_labor_id' => $test_tax->id,
			'tax_calc_material_id' => $test_tax->id,
			'tax_calc_equipment_id' => $test_tax->id,
			'tax_more_labor_id' => $test_tax->id,
			'tax_more_material_id' => $test_tax->id,
			'tax_more_equipment_id' => $test_tax->id,
			'tax_estimate_labor_id' => $test_tax->id,
			'tax_estimate_material_id' => $test_tax->id,
			'tax_estimate_equipment_id' => $test_tax->id
		));
		$this->command->info('Activity created');

		$test_activity8 = Activity::create(array(
			'activity_name' => 'Werkzaamheid h',
			'priority' => '8',
			'note' => 'omschrijving van werkzaamheid h',
			'chapter_id' => $test_chapter3->id,
			'part_id' => $test_part_subcontract->id,
			'part_type_id' => $test_part_type_est->id,
			'detail_id' => $test_detail->id,
			'tax_calc_labor_id' => $test_tax->id,
			'tax_calc_material_id' => $test_tax->id,
			'tax_calc_equipment_id' => $test_tax->id,
			'tax_more_labor_id' => $test_tax->id,
			'tax_more_material_id' => $test_tax->id,
			'tax_more_equipment_id' => $test_tax->id,
			'tax_estimate_labor_id' => $test_tax->id,
			'tax_estimate_material_id' => $test_tax->id,
			'tax_estimate_equipment_id' => $test_tax->id
		));
		$this->command->info('Activity created');

		$test_timesheet = Timesheet::create(array(
			'register_date' => '09-10-2014',
			'register_hour' => '24',
			'note' => 'omschrijving bij uren invoer',
			'part_id' => $test_part_contract->id,
			'part_type_id' => $test_part_type_calc->id,
			'detail_id' => $test_detail->id,
			'project_id' => $test_project->id
		));
		$this->command->info('Timesheet created');

		Purchase::create(array(
			'amount' => '33',
			'note' => 'aankoop factuur omschrijving',
			'register_date' => '09-10-2014',
			'part_id' => $test_part_subcontract->id,
			'project_id' => $test_project->id,
			'relation_id' => $test_relation->id
		));
		$this->command->info('Purchase created');

		$test_offer = Offer::create(array(
			'display_tax' => 'Y',
			'description' => 'omschrijving voor op offerte',
			'closure' => 'dit was de omschrijving voor op offerte',
			'end_invoice' => 'Y',
			'auto_email_reminder' => 'Y',
			'offer_finish' => '01-11-2014',
			'deliver_id' => $test_deliver->id,
			'specification_id' => $test_specification->id,
			'valid_id' => $test_valid->id,
			'project_id' => $test_project->id
		));
		$this->command->info('Offer created');

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
		$this->command->info('Invoice created');

		$calculation_labor_activity1 = CalculationLabor::create(array(
			'rate' => '35.00',
			'amount' => '12.50',
			'activity_id' => $test_activity1->id
		));
		$this->command->info('CalculationLabor created');

		$calculation_material_activity1_1 = CalculationMaterial::create(array(
			'material_name' => 'regel 1',
			'unit' => 'm',
			'rate' => '1.00',
			'amount' => '2.00',
			'activity_id' => $test_activity1->id
		));
		$calculation_equipment_activity1_1 = CalculationEquipment::create(array(
			'equipment_name' => 'regel a',
			'unit' => 'stuk',
			'rate' => '1.01',
			'amount' => '2.02',
			'activity_id' => $test_activity1->id
		));
		$calculation_material_activity1_2 = CalculationMaterial::create(array(
			'material_name' => 'regel 2',
			'unit' => 'm',
			'rate' => '2.00',
			'amount' => '1.00',
			'activity_id' => $test_activity1->id
		));
		$calculation_equipment_activity1_2 = CalculationEquipment::create(array(
			'equipment_name' => 'regel b',
			'unit' => 'stuk',
			'rate' => '2.01',
			'amount' => '1.01',
			'activity_id' => $test_activity1->id
		));
		$calculation_material_activity1_3 = CalculationMaterial::create(array(
			'material_name' => 'regel 3',
			'unit' => 'm',
			'rate' => '1.10',
			'amount' => '2.20',
			'activity_id' => $test_activity1->id
		));
		$calculation_equipment_activity1_3 = CalculationEquipment::create(array(
			'equipment_name' => 'regel c',
			'unit' => 'stuk',
			'rate' => '2.20',
			'amount' => '1.10',
			'activity_id' => $test_activity1->id
		));
		$calculation_material_activity1_4 = CalculationMaterial::create(array(
			'material_name' => 'regel 4',
			'unit' => 'm',
			'rate' => '1.11',
			'amount' => '2.22',
			'activity_id' => $test_activity1->id
		));
		$calculation_equipment_activity1_4 = CalculationEquipment::create(array(
			'equipment_name' => 'regel d',
			'unit' => 'stuk',
			'rate' => '2.22',
			'amount' => '1.11',
			'activity_id' => $test_activity1->id
		));
		$calculation_material_activity1_5 = CalculationMaterial::create(array(
			'material_name' => 'regel 5',
			'unit' => 'm',
			'rate' => '11.11',
			'amount' => '22.22',
			'activity_id' => $test_activity1->id
		));
		$calculation_equipment_activity1_5 = CalculationEquipment::create(array(
			'equipment_name' => 'regel e',
			'unit' => 'stuk',
			'rate' => '22.22',
			'amount' => '11.11',
			'activity_id' => $test_activity1->id
		));
		$this->command->info('CalculationMaterial created');
		$this->command->info('CalculationEquipment created');

		$calculation_labor_activity2 = CalculationLabor::create(array(
			'rate' => '35.00',
			'amount' => '13.50',
			'activity_id' => $test_activity2->id
		));
		$this->command->info('CalculationLabor created');

		$calculation_material_activity2_1 = CalculationMaterial::create(array(
			'material_name' => 'regel 1',
			'unit' => 'm',
			'rate' => '1.00',
			'amount' => '2.00',
			'activity_id' => $test_activity2->id
		));
		$calculation_equipment_activity2_1 = CalculationEquipment::create(array(
			'equipment_name' => 'regel a',
			'unit' => 'stuk',
			'rate' => '1.01',
			'amount' => '2.02',
			'activity_id' => $test_activity2->id
		));
		$calculation_material_activity1_2 = CalculationMaterial::create(array(
			'material_name' => 'regel 2',
			'unit' => 'm',
			'rate' => '2.00',
			'amount' => '1.00',
			'activity_id' => $test_activity2->id
		));
		$calculation_equipment_activity2_2 = CalculationEquipment::create(array(
			'equipment_name' => 'regel b',
			'unit' => 'stuk',
			'rate' => '2.01',
			'amount' => '1.01',
			'activity_id' => $test_activity2->id
		));
		$calculation_material_activity2_3 = CalculationMaterial::create(array(
			'material_name' => 'regel 3',
			'unit' => 'm',
			'rate' => '1.10',
			'amount' => '2.20',
			'activity_id' => $test_activity2->id
		));
		$calculation_equipment_activity2_3 = CalculationEquipment::create(array(
			'equipment_name' => 'regel c',
			'unit' => 'stuk',
			'rate' => '2.20',
			'amount' => '1.10',
			'activity_id' => $test_activity2->id
		));
		$calculation_material_activity2_4 = CalculationMaterial::create(array(
			'material_name' => 'regel 4',
			'unit' => 'm',
			'rate' => '1.11',
			'amount' => '2.22',
			'activity_id' => $test_activity2->id
		));
		$calculation_equipment_activity2_4 = CalculationEquipment::create(array(
			'equipment_name' => 'regel d',
			'unit' => 'stuk',
			'rate' => '2.22',
			'amount' => '1.11',
			'activity_id' => $test_activity2->id
		));
		$calculation_material_activity2_5 = CalculationMaterial::create(array(
			'material_name' => 'regel 5',
			'unit' => 'm',
			'rate' => '11.11',
			'amount' => '22.22',
			'activity_id' => $test_activity2->id
		));
		$calculation_equipment_activity2_5 = CalculationEquipment::create(array(
			'equipment_name' => 'regel e',
			'unit' => 'stuk',
			'rate' => '22.22',
			'amount' => '11.11',
			'activity_id' => $test_activity2->id
		));
		$this->command->info('CalculationMaterial created');
		$this->command->info('CalculationEquipment created');

		$calculation_labor_activity3 = CalculationLabor::create(array(
			'rate' => '35.00',
			'amount' => '14.50',
			'activity_id' => $test_activity3->id
		));
		$this->command->info('CalculationLabor created');

		$calculation_material_activity3_1 = CalculationMaterial::create(array(
			'material_name' => 'regel 1',
			'unit' => 'm',
			'rate' => '31.00',
			'amount' => '32.00',
			'activity_id' => $test_activity3->id
		));
		$calculation_equipment_activity3_1 = CalculationEquipment::create(array(
			'equipment_name' => 'regel a',
			'unit' => 'stuk',
			'rate' => '31.01',
			'amount' => '32.02',
			'activity_id' => $test_activity3->id
		));
		$calculation_material_activity3_2 = CalculationMaterial::create(array(
			'material_name' => 'regel 2',
			'unit' => 'm',
			'rate' => '32.00',
			'amount' => '31.00',
			'activity_id' => $test_activity3->id
		));
		$calculation_equipment_activity2_2 = CalculationEquipment::create(array(
			'equipment_name' => 'regel b',
			'unit' => 'stuk',
			'rate' => '32.01',
			'amount' => '31.01',
			'activity_id' => $test_activity3->id
		));
		$calculation_material_activity3_3 = CalculationMaterial::create(array(
			'material_name' => 'regel 3',
			'unit' => 'm',
			'rate' => '31.10',
			'amount' => '32.20',
			'activity_id' => $test_activity3->id
		));
		$calculation_equipment_activity3_3 = CalculationEquipment::create(array(
			'equipment_name' => 'regel c',
			'unit' => 'stuk',
			'rate' => '32.20',
			'amount' => '31.10',
			'activity_id' => $test_activity3->id
		));
		$calculation_material_activity3_4 = CalculationMaterial::create(array(
			'material_name' => 'regel 4',
			'unit' => 'm',
			'rate' => '31.11',
			'amount' => '32.22',
			'activity_id' => $test_activity3->id
		));
		$calculation_equipment_activity3_4 = CalculationEquipment::create(array(
			'equipment_name' => 'regel d',
			'unit' => 'stuk',
			'rate' => '32.22',
			'amount' => '31.11',
			'activity_id' => $test_activity3->id
		));
		$calculation_material_activity3_5 = CalculationMaterial::create(array(
			'material_name' => 'regel 5',
			'unit' => 'm',
			'rate' => '311.11',
			'amount' => '32.22',
			'activity_id' => $test_activity3->id
		));
		$calculation_equipment_activity3_5 = CalculationEquipment::create(array(
			'equipment_name' => 'regel e',
			'unit' => 'stuk',
			'rate' => '322.22',
			'amount' => '31.11',
			'activity_id' => $test_activity3->id
		));
		$this->command->info('CalculationMaterial created');
		$this->command->info('CalculationEquipment created');

		$calculation_labor_activity4 = CalculationLabor::create(array(
			'rate' => '35.00',
			'amount' => '15.50',
			'activity_id' => $test_activity4->id
		));
		$this->command->info('CalculationLabor created');

		$calculation_material_activity4_1 = CalculationMaterial::create(array(
			'material_name' => 'regel 1',
			'unit' => 'm',
			'rate' => '31.90',
			'amount' => '32.90',
			'activity_id' => $test_activity4->id
		));
		$calculation_equipment_activity4_1 = CalculationEquipment::create(array(
			'equipment_name' => 'regel a',
			'unit' => 'stuk',
			'rate' => '31.91',
			'amount' => '32.92',
			'activity_id' => $test_activity4->id
		));
		$calculation_material_activity4_2 = CalculationMaterial::create(array(
			'material_name' => 'regel 2',
			'unit' => 'm',
			'rate' => '32.90',
			'amount' => '31.90',
			'activity_id' => $test_activity4->id
		));
		$calculation_equipment_activity4_2 = CalculationEquipment::create(array(
			'equipment_name' => 'regel b',
			'unit' => 'stuk',
			'rate' => '32.91',
			'amount' => '31.91',
			'activity_id' => $test_activity4->id
		));
		$calculation_material_activity4_3 = CalculationMaterial::create(array(
			'material_name' => 'regel 3',
			'unit' => 'm',
			'rate' => '31.19',
			'amount' => '32.29',
			'activity_id' => $test_activity4->id
		));
		$calculation_equipment_activity4_3 = CalculationEquipment::create(array(
			'equipment_name' => 'regel c',
			'unit' => 'stuk',
			'rate' => '32.29',
			'amount' => '31.19',
			'activity_id' => $test_activity4->id
		));
		$calculation_material_activity4_4 = CalculationMaterial::create(array(
			'material_name' => 'regel 4',
			'unit' => 'm',
			'rate' => '31.19',
			'amount' => '32.29',
			'activity_id' => $test_activity4->id
		));
		$calculation_equipment_activity4_4 = CalculationEquipment::create(array(
			'equipment_name' => 'regel d',
			'unit' => 'stuk',
			'rate' => '32.29',
			'amount' => '31.19',
			'activity_id' => $test_activity4->id
		));
		$calculation_material_activity4_5 = CalculationMaterial::create(array(
			'material_name' => 'regel 5',
			'unit' => 'm',
			'rate' => '31.19',
			'amount' => '322.29',
			'activity_id' => $test_activity4->id
		));
		$calculation_equipment_activity4_5 = CalculationEquipment::create(array(
			'equipment_name' => 'regel e',
			'unit' => 'stuk',
			'rate' => '32.29',
			'amount' => '311.19',
			'activity_id' => $test_activity4->id
		));
		$this->command->info('CalculationMaterial created');
		$this->command->info('CalculationEquipment created');

		$calculation_labor_activity5 = CalculationLabor::create(array(
			'rate' => '35.00',
			'amount' => '16.50',
			'activity_id' => $test_activity5->id
		));
		$this->command->info('CalculationLabor created');

		$calculation_material_activity5_1 = CalculationMaterial::create(array(
			'material_name' => 'regel 1',
			'unit' => 'm',
			'rate' => '11.90',
			'amount' => '132.90',
			'activity_id' => $test_activity5->id
		));
		$calculation_equipment_activity5_1 = CalculationEquipment::create(array(
			'equipment_name' => 'regel a',
			'unit' => 'stuk',
			'rate' => '31.91',
			'amount' => '132.92',
			'activity_id' => $test_activity5->id
		));
		$calculation_material_activity5_2 = CalculationMaterial::create(array(
			'material_name' => 'regel 2',
			'unit' => 'm',
			'rate' => '32.90',
			'amount' => '131.90',
			'activity_id' => $test_activity5->id
		));
		$calculation_equipment_activity5_2 = CalculationEquipment::create(array(
			'equipment_name' => 'regel b',
			'unit' => 'stuk',
			'rate' => '31.91',
			'amount' => '311.91',
			'activity_id' => $test_activity5->id
		));
		$calculation_material_activity5_3 = CalculationMaterial::create(array(
			'material_name' => 'regel 3',
			'unit' => 'm',
			'rate' => '31.19',
			'amount' => '132.29',
			'activity_id' => $test_activity5->id
		));
		$calculation_equipment_activity5_3 = CalculationEquipment::create(array(
			'equipment_name' => 'regel c',
			'unit' => 'stuk',
			'rate' => '31.29',
			'amount' => '311.19',
			'activity_id' => $test_activity5->id
		));
		$calculation_material_activity5_4 = CalculationMaterial::create(array(
			'material_name' => 'regel 4',
			'unit' => 'm',
			'rate' => '11.19',
			'amount' => '132.29',
			'activity_id' => $test_activity5->id
		));
		$calculation_equipment_activity5_4 = CalculationEquipment::create(array(
			'equipment_name' => 'regel d',
			'unit' => 'stuk',
			'rate' => '32.29',
			'amount' => '311.19',
			'activity_id' => $test_activity5->id
		));
		$calculation_material_activity5_5 = CalculationMaterial::create(array(
			'material_name' => 'regel 5',
			'unit' => 'm',
			'rate' => '31.19',
			'amount' => '322.29',
			'activity_id' => $test_activity5->id
		));
		$calculation_equipment_activity5_5 = CalculationEquipment::create(array(
			'equipment_name' => 'regel e',
			'unit' => 'stuk',
			'rate' => '32.29',
			'amount' => '311.19',
			'activity_id' => $test_activity5->id
		));
		$this->command->info('CalculationMaterial created');
		$this->command->info('CalculationEquipment created');

		$calculation_labor_activity6 = CalculationLabor::create(array(
			'rate' => '35.00',
			'amount' => '17.50',
			'activity_id' => $test_activity6->id
		));
		$this->command->info('CalculationLabor created');

		$calculation_material_activity6_1 = CalculationMaterial::create(array(
			'material_name' => 'regel 1',
			'unit' => 'm',
			'rate' => '131.90',
			'amount' => '13.90',
			'activity_id' => $test_activity6->id
		));
		$calculation_equipment_activity6_1 = CalculationEquipment::create(array(
			'equipment_name' => 'regel a',
			'unit' => 'stuk',
			'rate' => '301.91',
			'amount' => '102.92',
			'activity_id' => $test_activity6->id
		));
		$calculation_material_activity6_2 = CalculationMaterial::create(array(
			'material_name' => 'regel 2',
			'unit' => 'm',
			'rate' => '102.90',
			'amount' => '131.90',
			'activity_id' => $test_activity6->id
		));
		$calculation_equipment_activity6_2 = CalculationEquipment::create(array(
			'equipment_name' => 'regel b',
			'unit' => 'stuk',
			'rate' => '32.91',
			'amount' => '311.91',
			'activity_id' => $test_activity6->id
		));
		$calculation_material_activity6_3 = CalculationMaterial::create(array(
			'material_name' => 'regel 3',
			'unit' => 'm',
			'rate' => '311.19',
			'amount' => '102.29',
			'activity_id' => $test_activity6->id
		));
		$calculation_equipment_activity6_3 = CalculationEquipment::create(array(
			'equipment_name' => 'regel c',
			'unit' => 'stuk',
			'rate' => '312.29',
			'amount' => '31.19',
			'activity_id' => $test_activity6->id
		));
		$calculation_material_activity6_4 = CalculationMaterial::create(array(
			'material_name' => 'regel 4',
			'unit' => 'm',
			'rate' => '101.19',
			'amount' => '12.29',
			'activity_id' => $test_activity6->id
		));
		$calculation_equipment_activity6_4 = CalculationEquipment::create(array(
			'equipment_name' => 'regel d',
			'unit' => 'stuk',
			'rate' => '312.29',
			'amount' => '31.19',
			'activity_id' => $test_activity6->id
		));
		$calculation_material_activity6_5 = CalculationMaterial::create(array(
			'material_name' => 'regel 5',
			'unit' => 'm',
			'rate' => '30.19',
			'amount' => '32.29',
			'activity_id' => $test_activity6->id
		));
		$calculation_equipment_activity6_5 = CalculationEquipment::create(array(
			'equipment_name' => 'regel e',
			'unit' => 'stuk',
			'rate' => '30.29',
			'amount' => '30.19',
			'activity_id' => $test_activity6->id
		));
		$this->command->info('CalculationMaterial created');
		$this->command->info('CalculationEquipment created');




		LessLabor::create(array(
			'amount' => '34.56',
			'activity_id' => $test_activity4->id,
			'original_id' => $calculation_labor_activity4->id,
		));
		$this->command->info('LessLabor created');

		LessMaterial::create(array(
			'rate' => '1.00',
			'amount' => '21.29',
			'activity_id' => $test_activity4->id,
			'original_id' => $calculation_material_activity4_1->id,
		));
		$this->command->info('LessMaterial created');

		LessEquipment::create(array(
			'rate' => '112.23',
			'amount' => '1101.25',
			'activity_id' => $test_activity4->id,
			'original_id' => $calculation_equipment_activity4_1->id,
		));
		$this->command->info('LessEquipment created');









		MoreLabor::create(array(
			'rate' => '102.33',
			'amount' => '999.56',
			'note' => 'omschrijving voor bij meerwerk',
			'activity_id' => $test_activity7->id,
			'hour_id' => $test_timesheet->id
		));
		$this->command->info('MoreLabor created');

		MoreMaterial::create(array(
			'material_name' => 'plankmeer',
			'unit' => 'm',
			'rate' => '160.24',
			'amount' => '1611.22',
			'activity_id' => $test_activity7->id
		));
		$this->command->info('MoreMaterial created');

		MoreEquipment::create(array(
			'equipment_name' => 'plankknippermeer',
			'unit' => 'stuk',
			'rate' => '1812.23',
			'amount' => '18101.25',
			'activity_id' => $test_activity7->id
		));
		$this->command->info('MoreEquipment created');

		EstimateLabor::create(array(
			'rate' => '102.33',
			'amount' => '3404.56',
			'set_rate' => '404.56',
			'set_amount' => '1404.56',
			'activity_id' => $test_activity8->id,
			'hour_id' => $test_timesheet->id
		));
		$this->command->info('EstimateLabor created');

		EstimateMaterial::create(array(
			'material_name' => 'plankmeerestimate',
			'unit' => 'm',
			'rate' => '50.24',
			'amount' => '511.22',
			'set_material_name' => 'plankje meer',
			'set_unit' => 'm2',
			'set_rate' => '604.56',
			'set_amount' => '604.56',
			'activity_id' => $test_activity8->id
		));
		$this->command->info('EstimateMaterial created');

		EstimateEquipment::create(array(
			'equipment_name' => 'plankknippermeer',
			'unit' => 'stuk',
			'rate' => '1212.23',
			'amount' => '12101.25',
			'set_equipment_name' => 'knipper meer',
			'set_unit' => 'stuk',
			'set_rate' => '64.56',
			'set_amount' => '6.56',
			'activity_id' => $test_activity8->id
		));
		$this->command->info('EstimateEquipment created');

		$test_user->productFavorite()->attach($test_product->id);
		$this->command->info('Project favorite created');
	}
 }
