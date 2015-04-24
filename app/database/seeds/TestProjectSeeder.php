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
		//DB::table('less_equipment')->delete();
		//DB::table('less_material')->delete();
		//DB::table('less_labor')->delete();
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
		$test_tax1 = Tax::where('tax_rate','=','21')->first();
		$test_tax2 = Tax::where('tax_rate','=','6')->first();
		$test_tax3 = Tax::where('tax_rate','=','0')->first();

		//$test_product = Product::where('description','=','Hoge kast deur links 60cm met nis 450/485/880mm')->first();

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
			'address_number' => '1B',
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
			'pref_profit_calc_contr_mat' => '10',
			'pref_profit_calc_contr_equip' => '11',
			'pref_profit_calc_subcontr_mat' => '12',
			'pref_profit_calc_subcontr_equip' => '13',
			'pref_profit_more_contr_mat' => '14',
			'pref_profit_more_contr_equip' => '15',
			'pref_profit_more_subcontr_mat' => '16',
			'pref_profit_more_subcontr_equip' => '17',
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
			'payment_date' => '07-03-2015',
			'due_date' => '09-03-2015',
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
			'company_name' => 'Beun Haas B.V.',
			'address_street' => 'Afrikanenplein',
			'address_number' => '11',
			'address_postal' => '1234AB',
			'address_city' => 'RotterdamZuid',
			'kvk' => '174365278954',
			'btw' => 'NL214855241B01',
			'debtor_code' => 'UG83824',
			'phone' => '0612345678',
			'email' => 'info@website.nl',
			'note' => 'omschrijving relatie',
			'website' => 'http://www.beunhaas.nl',
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
			'hour_rate_more' => '45',
			'profit_calc_contr_mat' => '10',
			'profit_calc_contr_equip' => '11',
			'profit_calc_subcontr_mat' => '12',
			'profit_calc_subcontr_equip' => '13',
			'profit_more_contr_mat' => '14',
			'profit_more_contr_equip' => '15',
			'profit_more_subcontr_mat' => '16',
			'profit_more_subcontr_equip' => '17',
			'user_id' => $test_user->id,
			'province_id' => $test_province->id,
			'country_id' => $test_country->id,
			'type_id' => $test_projecttype->id,
			'client_id' => $test_relation->id
		));
		$this->command->info('Project created');

		ProjectStatus::create(array(
			'finish' => '09-03-2015',
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
			'firstname' => 'Beun',
			'lastname' => 'Haas',
			'email' => 'beun@haas.nl',
			'mobile' => '0612345678',
			'phone' => '0101234567',
			'note' => 'Eigenaar',
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
			'chapter_name' => 'CHPTR1',
			'priority' => '1',
			'note' => 'Description_CHPTR1',
			'project_id' => $test_project->id
		));
		$this->command->info('Chapter created');

		$test_activity1 = Activity::create(array(
			'activity_name' => 'CHPTR1_ACT1_CON',
			'priority' => '1',
			'note' => 'Description_CHPTR1_ACT1_CON',
			'chapter_id' => $test_chapter1->id,
			'part_id' => $test_part_contract->id,
			'part_type_id' => $test_part_type_calc->id,
			'detail_id' => $test_detail->id,
			'tax_calc_labor_id' => $test_tax1->id,
			'tax_calc_material_id' => $test_tax1->id,
			'tax_calc_equipment_id' => $test_tax1->id,
			'tax_more_labor_id' => $test_tax1->id,
			'tax_more_material_id' => $test_tax1->id,
			'tax_more_equipment_id' => $test_tax1->id,
			'tax_estimate_labor_id' => $test_tax1->id,
			'tax_estimate_material_id' => $test_tax1->id,
			'tax_estimate_equipment_id' => $test_tax1->id
		));
		$this->command->info('Activity created');

		$test_activity2 = Activity::create(array(
			'activity_name' => 'CHPTR1_ACT2_SUBCON',
			'priority' => '2',
			'note' => 'Description_CHPTR1_ACT2_SUBCON',
			'chapter_id' => $test_chapter1->id,
			'part_id' => $test_part_subcontract->id,
			'part_type_id' => $test_part_type_calc->id,
			'detail_id' => $test_detail->id,
			'tax_calc_labor_id' => $test_tax2->id,
			'tax_calc_material_id' => $test_tax2->id,
			'tax_calc_equipment_id' => $test_tax2->id,
			'tax_more_labor_id' => $test_tax2->id,
			'tax_more_material_id' => $test_tax2->id,
			'tax_more_equipment_id' => $test_tax2->id,
			'tax_estimate_labor_id' => $test_tax2->id,
			'tax_estimate_material_id' => $test_tax2->id,
			'tax_estimate_equipment_id' => $test_tax2->id
		));
		$this->command->info('Activity created');

		$test_chapter2 = Chapter::create(array(
			'chapter_name' => 'CHPTR2',
			'priority' => '2',
			'note' => 'Description_CHPTR2',
			'project_id' => $test_project->id
		));
		$this->command->info('Chapter created');

		$test_activity3 = Activity::create(array(
			'activity_name' => 'CHPTR2_ACT3_CON',
			'priority' => '3',
			'note' => 'Description_CHPTR2_ACT3_CON',
			'chapter_id' => $test_chapter2->id,
			'part_id' => $test_part_contract->id,
			'part_type_id' => $test_part_type_calc->id,
			'detail_id' => $test_detail->id,
			'tax_calc_labor_id' => $test_tax3->id,
			'tax_calc_material_id' => $test_tax3->id,
			'tax_calc_equipment_id' => $test_tax3->id,
			'tax_more_labor_id' => $test_tax3->id,
			'tax_more_material_id' => $test_tax3->id,
			'tax_more_equipment_id' => $test_tax3->id,
			'tax_estimate_labor_id' => $test_tax3->id,
			'tax_estimate_material_id' => $test_tax3->id,
			'tax_estimate_equipment_id' => $test_tax3->id
		));
		$this->command->info('Activity created');

		$test_activity4 = Activity::create(array(
			'activity_name' => 'CHPTR2_ACT4_SUBCON',
			'priority' => '4',
			'note' => 'Description_CHPTR2_ACT4_SUBCON',
			'chapter_id' => $test_chapter2->id,
			'part_id' => $test_part_subcontract->id,
			'part_type_id' => $test_part_type_calc->id,
			'detail_id' => $test_detail->id,
			'tax_calc_labor_id' => $test_tax1->id,
			'tax_calc_material_id' => $test_tax1->id,
			'tax_calc_equipment_id' => $test_tax1->id,
			'tax_more_labor_id' => $test_tax1->id,
			'tax_more_material_id' => $test_tax1->id,
			'tax_more_equipment_id' => $test_tax1->id,
			'tax_estimate_labor_id' => $test_tax1->id,
			'tax_estimate_material_id' => $test_tax1->id,
			'tax_estimate_equipment_id' => $test_tax1->id
		));
		$this->command->info('Activity created');

		$test_chapter3 = Chapter::create(array(
			'chapter_name' => 'CHPTR3',
			'priority' => '3',
			'note' => 'Description_CHPTR3',
			'project_id' => $test_project->id
		));
		$this->command->info('Chapter created');

		$test_activity5 = Activity::create(array(
			'activity_name' => 'CHPTR3_ACT5_CON',
			'priority' => '5',
			'note' => 'Description_CHPTR3_ACT5_CON',
			'chapter_id' => $test_chapter3->id,
			'part_id' => $test_part_contract->id,
			'part_type_id' => $test_part_type_calc->id,
			'detail_id' => $test_detail->id,
			'tax_calc_labor_id' => $test_tax2->id,
			'tax_calc_material_id' => $test_tax2->id,
			'tax_calc_equipment_id' => $test_tax2->id,
			'tax_more_labor_id' => $test_tax2->id,
			'tax_more_material_id' => $test_tax2->id,
			'tax_more_equipment_id' => $test_tax2->id,
			'tax_estimate_labor_id' => $test_tax2->id,
			'tax_estimate_material_id' => $test_tax2->id,
			'tax_estimate_equipment_id' => $test_tax2->id
		));
		$this->command->info('Activity created');

		$test_activity6 = Activity::create(array(
			'activity_name' => 'CHPTR3_ACT6_SUBCON',
			'priority' => '6',
			'note' => 'Description_CHPTR3_ACT6_SUBCON',
			'chapter_id' => $test_chapter3->id,
			'part_id' => $test_part_subcontract->id,
			'part_type_id' => $test_part_type_calc->id,
			'detail_id' => $test_detail->id,
			'tax_calc_labor_id' => $test_tax3->id,
			'tax_calc_material_id' => $test_tax3->id,
			'tax_calc_equipment_id' => $test_tax3->id,
			'tax_more_labor_id' => $test_tax3->id,
			'tax_more_material_id' => $test_tax3->id,
			'tax_more_equipment_id' => $test_tax3->id,
			'tax_estimate_labor_id' => $test_tax3->id,
			'tax_estimate_material_id' => $test_tax3->id,
			'tax_estimate_equipment_id' => $test_tax3->id
		));
		$this->command->info('Activity created');

		$test_activity7 = Activity::create(array(
			'activity_name' => 'CHPTR3_ACT7_CON_EST',
			'priority' => '7',
			'note' => 'Description_CHPTR3_ACT7_CON_EST',
			'chapter_id' => $test_chapter3->id,
			'part_id' => $test_part_contract->id,
			'part_type_id' => $test_part_type_est->id,
			'detail_id' => $test_detail->id,
			'tax_calc_labor_id' => $test_tax1->id,
			'tax_calc_material_id' => $test_tax1->id,
			'tax_calc_equipment_id' => $test_tax1->id,
			'tax_more_labor_id' => $test_tax1->id,
			'tax_more_material_id' => $test_tax1->id,
			'tax_more_equipment_id' => $test_tax1->id,
			'tax_estimate_labor_id' => $test_tax1->id,
			'tax_estimate_material_id' => $test_tax1->id,
			'tax_estimate_equipment_id' => $test_tax1->id
		));
		$this->command->info('Activity created');

		$test_activity8 = Activity::create(array(
			'activity_name' => 'CHPTR3_ACT8_SUBCON_EST',
			'priority' => '8',
			'note' => 'Description_CHPTR3_ACT8_SUBCON_EST',
			'chapter_id' => $test_chapter3->id,
			'part_id' => $test_part_subcontract->id,
			'part_type_id' => $test_part_type_est->id,
			'detail_id' => $test_detail->id,
			'tax_calc_labor_id' => $test_tax1->id,
			'tax_calc_material_id' => $test_tax1->id,
			'tax_calc_equipment_id' => $test_tax1->id,
			'tax_more_labor_id' => $test_tax1->id,
			'tax_more_material_id' => $test_tax1->id,
			'tax_more_equipment_id' => $test_tax1->id,
			'tax_estimate_labor_id' => $test_tax1->id,
			'tax_estimate_material_id' => $test_tax1->id,
			'tax_estimate_equipment_id' => $test_tax1->id
		));
		$this->command->info('Activity created');

		$test_chapter4 = Chapter::create(array(
			'chapter_name' => 'CHPTR4',
			'priority' => '4',
			'note' => 'Description_CHPTR4',
			'project_id' => $test_project->id
		));
		$this->command->info('Chapter created');

		$test_activity9 = Activity::create(array(
			'activity_name' => 'CHPTR4_ACT9_CON_EST',
			'priority' => '9',
			'note' => 'Description_CHPTR4_ACT9_CON_EST',
			'chapter_id' => $test_chapter4->id,
			'part_id' => $test_part_contract->id,
			'part_type_id' => $test_part_type_est->id,
			'detail_id' => $test_detail->id,
			'tax_calc_labor_id' => $test_tax2->id,
			'tax_calc_material_id' => $test_tax2->id,
			'tax_calc_equipment_id' => $test_tax2->id,
			'tax_more_labor_id' => $test_tax2->id,
			'tax_more_material_id' => $test_tax2->id,
			'tax_more_equipment_id' => $test_tax2->id,
			'tax_estimate_labor_id' => $test_tax2->id,
			'tax_estimate_material_id' => $test_tax2->id,
			'tax_estimate_equipment_id' => $test_tax2->id
		));
		$this->command->info('Activity created');

		$test_activity10 = Activity::create(array(
			'activity_name' => 'CHPTR4_ACT10_SUBCON_EST',
			'priority' => '10',
			'note' => 'Description_CHPTR4_ACT10_SUBCON_EST',
			'chapter_id' => $test_chapter4->id,
			'part_id' => $test_part_subcontract->id,
			'part_type_id' => $test_part_type_est->id,
			'detail_id' => $test_detail->id,
			'tax_calc_labor_id' => $test_tax3->id,
			'tax_calc_material_id' => $test_tax3->id,
			'tax_calc_equipment_id' => $test_tax3->id,
			'tax_more_labor_id' => $test_tax3->id,
			'tax_more_material_id' => $test_tax3->id,
			'tax_more_equipment_id' => $test_tax3->id,
			'tax_estimate_labor_id' => $test_tax3->id,
			'tax_estimate_material_id' => $test_tax3->id,
			'tax_estimate_equipment_id' => $test_tax3->id
		));
		$this->command->info('Activity created');

		$test_timesheet = Timesheet::create(array(
			'register_date' => '09-10-2014',
			'register_hour' => '24',
			'note' => 'omschrijving bij uren invoer',
			'activity_id' => $test_activity10->id
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
			'amount' => '1',
			'isless' => false,
			'activity_id' => $test_activity1->id
		));
		$this->command->info('CalculationLabor created');

		$calculation_material_activity1_1 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR1_ACT1_CON_1',
			'unit' => 'm',
			'rate' => '1',
			'amount' => '2.00',
			'isless' => false,
			'activity_id' => $test_activity1->id
		));
		$calculation_equipment_activity1_1 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR1_ACT1_CON_a',
			'unit' => 'stuk',
			'rate' => '1',
			'amount' => '2.02',
			'isless' => false,
			'activity_id' => $test_activity1->id
		));
		$calculation_material_activity1_2 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR1_ACT1_CON_2',
			'unit' => 'm',
			'rate' => '2',
			'amount' => '1.00',
			'isless' => false,
			'activity_id' => $test_activity1->id
		));
		$calculation_equipment_activity1_2 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR1_ACT1_CON_b',
			'unit' => 'stuk',
			'rate' => '2',
			'amount' => '1.01',
			'isless' => false,
			'activity_id' => $test_activity1->id
		));
		$calculation_material_activity1_3 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR1_ACT1_CON_3',
			'unit' => 'm',
			'rate' => '3',
			'amount' => '2.20',
			'isless' => false,
			'activity_id' => $test_activity1->id
		));
		$calculation_equipment_activity1_3 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR1_ACT1_CON_c',
			'unit' => 'stuk',
			'rate' => '3',
			'amount' => '1.10',
			'isless' => false,
			'activity_id' => $test_activity1->id
		));
		$calculation_material_activity1_4 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR1_ACT1_CON_4',
			'unit' => 'm',
			'rate' => '4',
			'amount' => '2.22',
			'isless' => false,
			'activity_id' => $test_activity1->id
		));
		$calculation_equipment_activity1_4 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR1_ACT1_CON_d',
			'unit' => 'stuk',
			'rate' => '4',
			'amount' => '1.11',
			'isless' => false,
			'activity_id' => $test_activity1->id
		));
		$calculation_material_activity1_5 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR1_ACT1_CON_5',
			'unit' => 'm',
			'rate' => '5',
			'amount' => '2.21',
			'isless' => false,
			'activity_id' => $test_activity1->id
		));
		$calculation_equipment_activity1_5 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR1_ACT1_CON_d',
			'unit' => 'stuk',
			'rate' => '5',
			'amount' => '1.12',
			'isless' => false,
			'activity_id' => $test_activity1->id
		));
		$this->command->info('CalculationMaterial created');
		$this->command->info('CalculationEquipment created');

		$calculation_labor_activity2 = CalculationLabor::create(array(
			'rate' => '35.00',
			'amount' => '2',
			'isless' => false,
			'activity_id' => $test_activity2->id
		));
		$this->command->info('CalculationLabor created');

		$calculation_material_activity2_1 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR1_ACT2_SUBCON_1',
			'unit' => 'm',
			'rate' => '1',
			'amount' => '2.00',
			'isless' => false,
			'activity_id' => $test_activity2->id
		));
		$calculation_equipment_activity2_1 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR1_ACT2_SUBCON_a',
			'unit' => 'stuk',
			'rate' => '1',
			'amount' => '2.02',
			'isless' => false,
			'activity_id' => $test_activity2->id
		));
		$calculation_material_activity1_2 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR1_ACT2_SUBCON_2',
			'unit' => 'm',
			'rate' => '2',
			'amount' => '1.00',
			'isless' => false,
			'activity_id' => $test_activity2->id
		));
		$calculation_equipment_activity2_2 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR1_ACT2_SUBCON_b',
			'unit' => 'stuk',
			'rate' => '2',
			'amount' => '1.01',
			'isless' => false,
			'activity_id' => $test_activity2->id
		));
		$calculation_material_activity2_3 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR1_ACT2_SUBCON_3',
			'unit' => 'm',
			'rate' => '3',
			'amount' => '2.20',
			'isless' => false,
			'activity_id' => $test_activity2->id
		));
		$calculation_equipment_activity2_3 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR1_ACT2_SUBCON_c',
			'unit' => 'stuk',
			'rate' => '3',
			'amount' => '1.10',
			'isless' => false,
			'activity_id' => $test_activity2->id
		));
		$calculation_material_activity2_4 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR1_ACT2_SUBCON_4',
			'unit' => 'm',
			'rate' => '4',
			'amount' => '2.22',
			'isless' => false,
			'activity_id' => $test_activity2->id
		));
		$calculation_equipment_activity2_4 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR1_ACT2_SUBCON_d',
			'unit' => 'stuk',
			'rate' => '4',
			'amount' => '1.11',
			'isless' => false,
			'activity_id' => $test_activity2->id
		));
		$calculation_material_activity2_5 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR1_ACT2_SUBCON_5',
			'unit' => 'm',
			'rate' => '5',
			'amount' => '2.21',
			'isless' => false,
			'activity_id' => $test_activity2->id
		));
		$calculation_equipment_activity2_5 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR1_ACT2_SUBCON_e',
			'unit' => 'stuk',
			'rate' => '5',
			'amount' => '1.12',
			'isless' => false,
			'activity_id' => $test_activity2->id
		));
		$this->command->info('CalculationMaterial created');
		$this->command->info('CalculationEquipment created');

		$calculation_labor_activity3 = CalculationLabor::create(array(
			'rate' => '35.00',
			'amount' => '3',
			'isless' => false,
			'activity_id' => $test_activity3->id
		));
		$this->command->info('CalculationLabor created');

		$calculation_material_activity3_1 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR2_ACT3_CON_1',
			'unit' => 'm',
			'rate' => '1',
			'amount' => '3.00',
			'isless' => false,
			'activity_id' => $test_activity3->id
		));
		$calculation_equipment_activity3_1 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR2_ACT3_CON_a',
			'unit' => 'stuk',
			'rate' => '1',
			'amount' => '3.02',
			'isless' => false,
			'activity_id' => $test_activity3->id
		));
		$calculation_material_activity3_2 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR2_ACT3_CON_2',
			'unit' => 'm',
			'rate' => '2',
			'amount' => '3.00',
			'isless' => false,
			'activity_id' => $test_activity3->id
		));
		$calculation_equipment_activity2_2 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR2_ACT3_CON_b',
			'unit' => 'stuk',
			'rate' => '2',
			'amount' => '3.01',
			'isless' => false,
			'activity_id' => $test_activity3->id
		));
		$calculation_material_activity3_3 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR2_ACT3_CON_3',
			'unit' => 'm',
			'rate' => '3',
			'amount' => '3.20',
			'isless' => false,
			'activity_id' => $test_activity3->id
		));
		$calculation_equipment_activity3_3 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR2_ACT3_CON_c',
			'unit' => 'stuk',
			'rate' => '3',
			'amount' => '3.10',
			'isless' => false,
			'activity_id' => $test_activity3->id
		));
		$calculation_material_activity3_4 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR2_ACT3_CON_4',
			'unit' => 'm',
			'rate' => '4',
			'amount' => '3.22',
			'isless' => false,
			'activity_id' => $test_activity3->id
		));
		$calculation_equipment_activity3_4 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR2_ACT3_CON_1d',
			'unit' => 'stuk',
			'rate' => '4',
			'amount' => '3.11',
			'isless' => false,
			'activity_id' => $test_activity3->id
		));
		$calculation_material_activity3_5 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR2_ACT3_CON_5',
			'unit' => 'm',
			'rate' => '5',
			'amount' => '3.22',
			'isless' => false,
			'activity_id' => $test_activity3->id
		));
		$calculation_equipment_activity3_5 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR2_ACT3_CON_e',
			'unit' => 'stuk',
			'rate' => '5',
			'amount' => '3.11',
			'isless' => false,
			'activity_id' => $test_activity3->id
		));
		$this->command->info('CalculationMaterial created');
		$this->command->info('CalculationEquipment created');

		$calculation_labor_activity4 = CalculationLabor::create(array(
			'rate' => '35.00',
			'amount' => '4',
			'isless' => false,
			'activity_id' => $test_activity4->id
		));
		$this->command->info('CalculationLabor created');

		$calculation_material_activity4_1 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR2_ACT4_SUBCON_1',
			'unit' => 'm',
			'rate' => '1',
			'amount' => '3.90',
			'isless' => false,
			'activity_id' => $test_activity4->id
		));
		$calculation_equipment_activity4_1 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR2_ACT4_SUBCON_a',
			'unit' => 'stuk',
			'rate' => '1',
			'amount' => '3.92',
			'isless' => false,
			'activity_id' => $test_activity4->id
		));
		$calculation_material_activity4_2 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR2_ACT4_SUBCON_2',
			'unit' => 'm',
			'rate' => '2',
			'amount' => '3.90',
			'isless' => false,
			'activity_id' => $test_activity4->id
		));
		$calculation_equipment_activity4_2 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR2_ACT4_SUBCON_b',
			'unit' => 'stuk',
			'rate' => '2',
			'amount' => '3.91',
			'isless' => false,
			'activity_id' => $test_activity4->id
		));
		$calculation_material_activity4_3 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR2_ACT4_SUBCON_3',
			'unit' => 'm',
			'rate' => '3',
			'amount' => '3.29',
			'isless' => false,
			'activity_id' => $test_activity4->id
		));
		$calculation_equipment_activity4_3 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR2_ACT4_SUBCON_c',
			'unit' => 'stuk',
			'rate' => '3',
			'amount' => '3.19',
			'isless' => false,
			'activity_id' => $test_activity4->id
		));
		$calculation_material_activity4_4 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR2_ACT4_SUBCON_4',
			'unit' => 'm',
			'rate' => '4',
			'amount' => '3.29',
			'isless' => false,
			'activity_id' => $test_activity4->id
		));
		$calculation_equipment_activity4_4 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR2_ACT4_SUBCON_d',
			'unit' => 'stuk',
			'rate' => '4',
			'amount' => '3.19',
			'isless' => false,
			'activity_id' => $test_activity4->id
		));
		$calculation_material_activity4_5 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR2_ACT4_SUBCON_5',
			'unit' => 'm',
			'rate' => '5',
			'amount' => '3.29',
			'isless' => false,
			'activity_id' => $test_activity4->id
		));
		$calculation_equipment_activity4_5 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR2_ACT4_SUBCON_e',
			'unit' => 'stuk',
			'rate' => '5',
			'amount' => '3.19',
			'isless' => false,
			'activity_id' => $test_activity4->id
		));
		$this->command->info('CalculationMaterial created');
		$this->command->info('CalculationEquipment created');

		$calculation_labor_activity5 = CalculationLabor::create(array(
			'rate' => '35.00',
			'amount' => '5',
			'less_amount' =>'4',
			'isless' => true,
			'activity_id' => $test_activity5->id
		));
		$this->command->info('CalculationLabor created');

		$calculation_material_activity5_1 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR3_ACT5_CON_1',
			'unit' => 'm',
			'rate' => '1',
			'amount' => '1.90',
			'isless' => false,
			'activity_id' => $test_activity5->id
		));
		$calculation_equipment_activity5_1 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR3_ACT5_CON_a',
			'unit' => 'stuk',
			'rate' => '1',
			'amount' => '1.92',
			'isless' => false,
			'activity_id' => $test_activity5->id
		));
		$calculation_material_activity5_2 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR3_ACT5_CON_2',
			'unit' => 'm',
			'rate' => '2',
			'amount' => '1.90',
			'isless' => false,
			'activity_id' => $test_activity5->id
		));
		$calculation_equipment_activity5_2 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR3_ACT5_CON_b',
			'unit' => 'stuk',
			'rate' => '2',
			'amount' => '3.91',
			'isless' => false,
			'activity_id' => $test_activity5->id
		));
		$calculation_material_activity5_3 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR3_ACT5_CON_3',
			'unit' => 'm',
			'rate' => '3',
			'amount' => '1.29',
			'isless' => false,
			'activity_id' => $test_activity5->id
		));
		$calculation_equipment_activity5_3 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR3_ACT5_CON_c',
			'unit' => 'stuk',
			'rate' => '3',
			'amount' => '3.19',
			'isless' => false,
			'activity_id' => $test_activity5->id
		));
		$calculation_material_activity5_4 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR3_ACT5_CON_4',
			'unit' => 'm',
			'rate' => '4',
			'amount' => '4.29',
			'less_rate' => '3',
			'less_amount' => '3.19',
			'isless' => true,
			'activity_id' => $test_activity5->id
		));
		$calculation_equipment_activity5_4 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR3_ACT5_CON_d',
			'unit' => 'stuk',
			'rate' => '4',
			'amount' => '2.19',
			'less_rate' => '3',
			'less_amount' => '1.19',
			'isless' => true,
			'activity_id' => $test_activity5->id
		));
		$calculation_material_activity5_5 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR3_ACT5_CON_5',
			'unit' => 'm',
			'rate' => '5',
			'amount' => '3.29',
			'less_rate' => '4',
			'less_amount' => '2.29',
			'isless' => true,
			'activity_id' => $test_activity5->id
		));
		$calculation_equipment_activity5_5 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR3_ACT5_CON_e',
			'unit' => 'stuk',
			'rate' => '5',
			'amount' => '3.19',
			'less_rate' => '4',
			'less_amount' => '2.19',
			'isless' => true,
			'activity_id' => $test_activity5->id
		));
		$this->command->info('CalculationMaterial created');
		$this->command->info('CalculationEquipment created');

		$calculation_labor_activity6 = CalculationLabor::create(array(
			'rate' => '35.00',
			'amount' => '6',
			'isless' => false,
			'activity_id' => $test_activity6->id
		));
		$this->command->info('CalculationLabor created');

		$calculation_material_activity6_1 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR3_ACT6_SUBCON_1',
			'unit' => 'm',
			'rate' => '1',
			'amount' => '1.90',
			'isless' => false,
			'activity_id' => $test_activity6->id
		));
		$calculation_equipment_activity6_1 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR3_ACT6_SUBCON_a',
			'unit' => 'stuk',
			'rate' => '1',
			'amount' => '1.92',
			'isless' => false,
			'activity_id' => $test_activity6->id
		));
		$calculation_material_activity6_2 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR3_ACT6_SUBCON_2',
			'unit' => 'm',
			'rate' => '2',
			'amount' => '1.90',
			'isless' => false,
			'activity_id' => $test_activity6->id
		));
		$calculation_equipment_activity6_2 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR3_ACT6_SUBCON_b',
			'unit' => 'stuk',
			'rate' => '2',
			'amount' => '1.91',
			'isless' => false,
			'activity_id' => $test_activity6->id
		));
		$calculation_material_activity6_3 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR3_ACT6_SUBCON_3',
			'unit' => 'm',
			'rate' => '3',
			'amount' => '1.29',
			'isless' => false,
			'activity_id' => $test_activity6->id
		));
		$calculation_equipment_activity6_3 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR3_ACT6_SUBCON_c',
			'unit' => 'stuk',
			'rate' => '3',
			'amount' => '1.19',
			'isless' => false,
			'activity_id' => $test_activity6->id
		));
		$calculation_material_activity6_4 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR3_ACT6_SUBCON_4',
			'unit' => 'm',
			'rate' => '4',
			'amount' => '1.29',
			'isless' => false,
			'activity_id' => $test_activity6->id
		));
		$calculation_equipment_activity6_4 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR3_ACT6_SUBCON_d',
			'unit' => 'stuk',
			'rate' => '4',
			'amount' => '1.19',
			'isless' => false,
			'activity_id' => $test_activity6->id
		));
		$calculation_material_activity6_5 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR3_ACT6_SUBCON_5',
			'unit' => 'm',
			'rate' => '5',
			'amount' => '2.29',
			'isless' => false,
			'activity_id' => $test_activity6->id
		));
		$calculation_equipment_activity6_5 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR3_ACT6_SUBCON_e',
			'unit' => 'stuk',
			'rate' => '5',
			'amount' => '3.19',
			'isless' => false,
			'activity_id' => $test_activity6->id
		));
		$this->command->info('CalculationMaterial created');
		$this->command->info('CalculationEquipment created');

		$calculation_labor_activity7 = EstimateLabor::create(array(
			'rate' => '35.00',
			'amount' => '7',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity7->id
		));
		$this->command->info('CalculationLabor created');

		$calculation_material_activity7_1 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR3_ACT7_SUBCON_1',
			'unit' => 'm',
			'rate' => '1',
			'amount' => '1.90',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity7->id
		));
		$calculation_equipment_activity7_1 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR3_ACT7_SUBCON_a',
			'unit' => 'stuk',
			'rate' => '1',
			'amount' => '1.92',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity7->id
		));
		$calculation_material_activity7_2 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR3_ACT7_SUBCON_2',
			'unit' => 'm',
			'rate' => '2',
			'amount' => '2.90',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity7->id
		));
		$calculation_equipment_activity7_2 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR3_ACT7_SUBCON_b',
			'unit' => 'stuk',
			'rate' => '2',
			'amount' => '3.91',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity7->id
		));
		$calculation_material_activity7_3 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR3_ACT7_SUBCON_3',
			'unit' => 'm',
			'rate' => '3',
			'amount' => '1.29',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity7->id
		));
		$calculation_equipment_activity7_3 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR3_ACT7_SUBCON_c',
			'unit' => 'stuk',
			'rate' => '3',
			'amount' => '3.19',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity7->id
		));
		$calculation_material_activity7_4 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR3_ACT7_SUBCON_4',
			'unit' => 'm',
			'rate' => '4',
			'amount' => '1.29',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity7->id
		));
		$calculation_equipment_activity7_4 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR3_ACT7_SUBCON_d',
			'unit' => 'stuk',
			'rate' => '4',
			'amount' => '3.19',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity7->id
		));
		$calculation_material_activity7_5 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR3_ACT7_SUBCON_5',
			'unit' => 'm',
			'rate' => '5',
			'amount' => '3.29',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity7->id
		));
		$calculation_equipment_activity7_5 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR3_ACT7_SUBCON_e',
			'unit' => 'stuk',
			'rate' => '5',
			'amount' => '3.19',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity7->id
		));
		$this->command->info('CalculationMaterial created');
		$this->command->info('CalculationEquipment created');

		$calculation_labor_activity8 = EstimateLabor::create(array(
			'rate' => '35.00',
			'amount' => '8',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity8->id
		));
		$this->command->info('CalculationLabor created');

		$calculation_material_activity8_1 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR3_ACT8_SUBCON_1',
			'unit' => 'm',
			'rate' => '1',
			'amount' => '1.90',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity8->id
		));
		$calculation_equipment_activity8_1 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR3_ACT8_SUBCON_a',
			'unit' => 'stuk',
			'rate' => '1',
			'amount' => '1.92',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity8->id
		));
		$calculation_material_activity8_2 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR3_ACT8_SUBCON_2',
			'unit' => 'm',
			'rate' => '2',
			'amount' => '1.90',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity8->id
		));
		$calculation_equipment_activity8_2 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR3_ACT8_SUBCON_b',
			'unit' => 'stuk',
			'rate' => '2',
			'amount' => '1.91',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity8->id
		));
		$calculation_material_activity8_3 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR3_ACT8_SUBCON_3',
			'unit' => 'm',
			'rate' => '3',
			'amount' => '1.29',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity8->id
		));
		$calculation_equipment_activity8_3 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR3_ACT8_SUBCON_c',
			'unit' => 'stuk',
			'rate' => '3',
			'amount' => '3.19',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity8->id
		));
		$calculation_material_activity8_4 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR3_ACT8_SUBCON_4',
			'unit' => 'm',
			'rate' => '4',
			'amount' => '1.29',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity8->id
		));
		$calculation_equipment_activity8_4 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR3_ACT8_SUBCON_d',
			'unit' => 'stuk',
			'rate' => '4',
			'amount' => '3.19',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity8->id
		));
		$calculation_material_activity8_5 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR3_ACT8_SUBCON_5',
			'unit' => 'm',
			'rate' => '5',
			'amount' => '3.29',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity8->id
		));
		$calculation_equipment_activity8_5 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR3_ACT8_SUBCON_e',
			'unit' => 'stuk',
			'rate' => '5',
			'amount' => '3.19',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity8->id
		));
		$this->command->info('CalculationMaterial created');
		$this->command->info('CalculationEquipment created');

		$calculation_labor_activity9 = EstimateLabor::create(array(
			'rate' => '35.00',
			'amount' => '7',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity9->id
		));
		$this->command->info('CalculationLabor created');

		$calculation_material_activity9_1 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR4_ACT9_SUBCON_1',
			'unit' => 'm',
			'rate' => '1',
			'amount' => '1.90',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity9->id
		));
		$calculation_equipment_activity9_1 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR4_ACT9_SUBCON_a',
			'unit' => 'stuk',
			'rate' => '1',
			'amount' => '1.92',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity9->id
		));
		$calculation_material_activity9_2 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR4_ACT9_SUBCON_2',
			'unit' => 'm',
			'rate' => '2',
			'amount' => '2.90',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity9->id
		));
		$calculation_equipment_activity9_2 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR4_ACT9_SUBCON_b',
			'unit' => 'stuk',
			'rate' => '2',
			'amount' => '3.91',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity9->id
		));
		$calculation_material_activity9_3 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR4_ACT9_SUBCON_3',
			'unit' => 'm',
			'rate' => '3',
			'amount' => '1.29',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity9->id
		));
		$calculation_equipment_activity9_3 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR4_ACT9_SUBCON_c',
			'unit' => 'stuk',
			'rate' => '3',
			'amount' => '3.19',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity9->id
		));
		$calculation_material_activity9_4 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR4_ACT9_SUBCON_4',
			'unit' => 'm',
			'rate' => '4',
			'amount' => '1.29',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity9->id
		));
		$calculation_equipment_activity9_4 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR4_ACT9_SUBCON_d',
			'unit' => 'stuk',
			'rate' => '4',
			'amount' => '3.19',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity9->id
		));
		$calculation_material_activity9_5 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR4_ACT9_SUBCON_5',
			'unit' => 'm',
			'rate' => '5',
			'amount' => '3.29',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity9->id
		));
		$calculation_equipment_activity9_5 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR4_ACT9_SUBCON_e',
			'unit' => 'stuk',
			'rate' => '5',
			'amount' => '3.19',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity9->id
		));
		$this->command->info('CalculationMaterial created');
		$this->command->info('CalculationEquipment created');

		$calculation_labor_activity10 = EstimateLabor::create(array(
			'rate' => '35.00',
			'amount' => '8',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity10->id
		));
		$this->command->info('CalculationLabor created');

		$calculation_material_activity10_1 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR4_ACT10_SUBCON_1',
			'unit' => 'm',
			'rate' => '1',
			'amount' => '1.90',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity10->id
		));
		$calculation_equipment_activity10_1 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR4_ACT10_SUBCON_a',
			'unit' => 'stuk',
			'rate' => '1',
			'amount' => '1.92',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity10->id
		));
		$calculation_material_activity10_2 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR4_ACT10_SUBCON_2',
			'unit' => 'm',
			'rate' => '2',
			'amount' => '1.90',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity10->id
		));
		$calculation_equipment_activity10_2 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR4_ACT10_SUBCON_b',
			'unit' => 'stuk',
			'rate' => '2',
			'amount' => '1.91',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity10->id
		));
		$calculation_material_activity10_3 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR4_ACT10_SUBCON_3',
			'unit' => 'm',
			'rate' => '3',
			'amount' => '1.29',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity10->id
		));
		$calculation_equipment_activity10_3 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR4_ACT10_SUBCON_c',
			'unit' => 'stuk',
			'rate' => '3',
			'amount' => '3.19',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity10->id
		));
		$calculation_material_activity10_4 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR4_ACT10_SUBCON_4',
			'unit' => 'm',
			'rate' => '4',
			'amount' => '1.29',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity10->id
		));
		$calculation_equipment_activity10_4 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR4_ACT10_SUBCON_d',
			'unit' => 'stuk',
			'rate' => '4',
			'amount' => '3.19',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity10->id
		));
		$calculation_material_activity10_5 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR4_ACT10_SUBCON_5',
			'unit' => 'm',
			'rate' => '5',
			'amount' => '3.29',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity10->id
		));
		$calculation_equipment_activity10_5 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR4_ACT10_SUBCON_e',
			'unit' => 'stuk',
			'rate' => '5',
			'amount' => '3.19',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity10->id
		));
		$this->command->info('CalculationMaterial created');
		$this->command->info('CalculationEquipment created');

/*
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
*/
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

/*
		EstimateLabor::create(array(
			'rate' => '35.00',
			'amount' => '8',
			'set_rate' => '35.00',
			'set_amount' => '8',
			'activity_id' => $test_activity8->id,
			'hour_id' => $test_timesheet->id
		));
		$this->command->info('EstimateLabor created');
*/
		$this->command->info('Estimate_Set_Material_Creating....');
		EstimateMaterial::create(array(
			'set_material_name' => 'estim_set_mat_1',
			'set_unit' => 'm2',
			'set_rate' => '1',
			'set_amount' => '2',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity9->id
		));
		EstimateMaterial::create(array(
			'set_material_name' => 'estim_set_mat_2',
			'set_unit' => 'm2',
			'set_rate' => '2',
			'set_amount' => '3',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity9->id
		));
		EstimateMaterial::create(array(
			'set_material_name' => 'estim_set_mat_3',
			'set_unit' => 'm2',
			'set_rate' => '4',
			'set_amount' => '5',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity9->id
		));
		EstimateMaterial::create(array(
			'set_material_name' => 'estim_set_mat_4',
			'set_unit' => 'm2',
			'set_rate' => '6',
			'set_amount' => '7',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity9->id
		));
		EstimateMaterial::create(array(
			'set_material_name' => 'estim_set_mat_5',
			'set_unit' => 'm2',
			'set_rate' => '8',
			'set_amount' => '9',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity9->id
		));
		$this->command->info('Estimate_Set_Material_Created');

		$this->command->info('Estimate_Set_Equipment_Creating....');
		EstimateEquipment::create(array(
			'set_equipment_name' => 'estim_set_equip_1',
			'set_unit' => 'stuk',
			'set_rate' => '1.1',
			'set_amount' => '2.2',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity9->id
		));
		EstimateEquipment::create(array(
			'set_equipment_name' => 'estim_set_equip_2',
			'set_unit' => 'stuk',
			'set_rate' => '3.3',
			'set_amount' => '4.4',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity9->id
		));
		EstimateEquipment::create(array(
			'set_equipment_name' => 'estim_set_equip_3',
			'set_unit' => 'stuk',
			'set_rate' => '5.1',
			'set_amount' => '6.6',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity9->id
		));
		EstimateEquipment::create(array(
			'set_equipment_name' => 'estim_set_equip_4',
			'set_unit' => 'stuk',
			'set_rate' => '7.7',
			'set_amount' => '8.8',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity9->id
		));
		EstimateEquipment::create(array(
			'set_equipment_name' => 'estim_set_equip_5',
			'set_unit' => 'stuk',
			'set_rate' => '9.9',
			'set_amount' => '10.10',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity9->id
		));
		$this->command->info('Estimate_Set_Equipment_Created');

		//$test_user->productFavorite()->attach($test_product->id);
		//$this->command->info('Project favorite created');
	}
 }
