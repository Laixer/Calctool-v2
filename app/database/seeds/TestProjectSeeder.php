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
		DB::table('project')->delete();
		DB::table('relation')->delete();
		DB::table('resource')->delete();
		DB::table('payment')->delete();
		DB::table('user_account')->delete();
		$this->command->info('Tables deleted');

		$test_user_type = UserType::where('user_type','=','system')->first();
		$test_province = Province::where('province_name','=','zuid-holland')->first();
		$test_country = Country::where('country_name','=','duitsland')->first();
		$test_projecttype = ProjectType::where('type_name','=','calculatie')->first();
		$test_contact_function = ContactFunction::where('function_name','=','voorzitter')->first();
		$test_type_name = RelationType::where('type_name','=','adviesbureau')->first();
		$test_kind_name = RelationKind::where('kind_name','=','zakelijk')->first();
		$test_timesheet_kind_name = TimesheetKind::where('kind_name','=','aanneming')->first();
		$test_purchase_kind_name = PurchaseKind::where('kind_name','=','aanneming')->first();

		$test_part_contract = Part::where('part_name','=','contracting')->first();
		$test_part_type_calc = PartType::where('type_name','=','calculation')->first();

		$test_part_subcontract = Part::where('part_name','=','subcontracting')->first();
		$test_part_type_est = PartType::where('type_name','=','estimate')->first();
		//Is test_detail voor les nog noodzakelijk?
		$test_detail_less = Detail::where('detail_name','=','less')->first();
		$test_detail_more = Detail::where('detail_name','=','more')->first();

		$test_deliver = DeliverTime::where('delivertime_name','=','3 weken')->first();
		$test_valid = Valid::where('valid_name','=','3 maanden')->first();
		$test_tax1 = Tax::where('tax_rate','=','21')->first();
		$test_tax2 = Tax::where('tax_rate','=','6')->first();
		$test_tax3 = Tax::where('tax_rate','=','0')->first();

		$test_user = User::create(array(
			'username' => 'system',
			'secret' => Hash::make('ABC@123'),
			'firstname' => 'SYSTEM',
			'api' => md5(mt_rand()),
			'token' => sha1(Hash::make('ABC@123')),
			'ip' => '::1',
			'active' => 'Y',
			'confirmed_mail' => date('Y-m-d'),
			'registration_date' => date('Y-m-d'),
			'expiration_date' => date('Y-m-d', strtotime("+100 year", time())),
			'referral_key' => md5(mt_rand()),
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
			'offer_counter' => '000',
			'invoice_counter' => '000',
			'administration_cost' => '12.50',
			'user_type' => $test_user_type->id,
			'province_id' => $test_province->id,
			'country_id' => $test_country->id,
			'self_id' => NULL
		));

		$this->command->info('User created');

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
			'user_id' => $test_user->id,
			'type_id' => $test_type_name->id,
			'kind_id' => $test_kind_name->id,
			'province_id' => $test_province->id,
			'country_id' => $test_country->id
		));

		$test_relation2 = Relation::create(array(
			'company_name' => 'Kaas B.V.',
			'address_street' => 'Melkweg',
			'address_number' => '4D',
			'address_postal' => '5724EB',
			'address_city' => 'Zwolle',
			'kvk' => '473264967312',
			'btw' => 'NL246963512B01',
			'debtor_code' => 'VRX75536',
			'phone' => '0640174372',
			'email' => 'info@kaas.nl',
			'note' => 'Betaald altijd',
			'website' => 'http://www.kaas.nl',
			'user_id' => $test_user->id,
			'type_id' => $test_type_name->id,
			'kind_id' => $test_kind_name->id,
			'province_id' => $test_province->id,
			'country_id' => $test_country->id
		));

		$test_relation3 = Relation::create(array(
			'company_name' => 'MyCorp',
			'address_street' => 'Dorpsweg',
			'address_number' => '1',
			'address_postal' => '2045ER',
			'address_city' => 'Breda',
			'kvk' => '473264967312',
			'btw' => 'NL246963512B01',
			'debtor_code' => 'JYFEGHD734',
			'phone' => '0640174372',
			'email' => 'info@mycorp.com',
			'note' => 'Dit ben ik zelf',
			'website' => 'http://www.mycorp.com',
			'user_id' => $test_user->id,
			'type_id' => $test_type_name->id,
			'kind_id' => $test_kind_name->id,
			'province_id' => $test_province->id,
			'country_id' => $test_country->id
		));

		$test_user->self_id = $test_relation3->id;
		$test_user->save();

		$this->command->info('Relation created');

		$test_project = Project::create(array(
			'project_name' => 'testproject',
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

		$test_contact_1=Contact::create(array(
			'firstname' => 'K',
			'lastname' => 'Aas',
			'email' => 'k@kaas.nl',
			'mobile' => '0612345678',
			'phone' => '0101234567',
			'note' => 'Eigenaar',
			'relation_id' => $test_relation2->id,
			'function_id' => $test_contact_function->id
		));

		$test_contact_2=Contact::create(array(
			'firstname' => 'H',
			'lastname' => 'Blub',
			'email' => 'h@mycorp.com',
			'mobile' => '0612345678',
			'phone' => '0101234567',
			'note' => 'Dit ben ik',
			'relation_id' => $test_relation3->id,
			'function_id' => $test_contact_function->id
		));

		$this->command->info('Contact created');

		Iban::create(array(
			'iban' => 'NL45RABO0123456789m',
			'iban_name' => 'system_iban',
			'user_id' => $test_user->id,
			'relation_id' => $test_relation->id,
		));

		Iban::create(array(
			'iban' => 'NL45RABO0123456789m',
			'iban_name' => 'system_iban',
			'user_id' => $test_user->id,
			'relation_id' => $test_relation2->id,
		));

		Iban::create(array(
			'iban' => 'NL76ANBA0145672396',
			'iban_name' => 'MyCorp',
			'user_id' => $test_user->id,
			'relation_id' => $test_relation3->id,
		));

		$this->command->info('Iban created');

		$test_chapter1 = Chapter::create(array(
			'chapter_name' => 'CHPTR1',
			'priority' => 1,
			'note' => 'Description_CHPTR1',
			'project_id' => $test_project->id
		));

		$this->command->info('Chapter 1 created');

		$test_activity1 = Activity::create(array(
			'activity_name' => 'CHPTR1_ACT1_CON',
			'priority' => 1,
			'note' => 'Description_CHPTR1_ACT1_CON',
			'chapter_id' => $test_chapter1->id,
			'part_id' => $test_part_contract->id,
			'part_type_id' => $test_part_type_calc->id,
			//'detail_id' => $test_detail->id,
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

		$this->command->info('Activity 1 created');

		$test_activity2 = Activity::create(array(
			'activity_name' => 'CHPTR1_ACT2_SUBCON',
			'priority' => 2,
			'note' => 'Description_CHPTR1_ACT2_SUBCON',
			'chapter_id' => $test_chapter1->id,
			'part_id' => $test_part_subcontract->id,
			'part_type_id' => $test_part_type_calc->id,
			//'detail_id' => $test_detail->id,
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

		$this->command->info('Activity 2 created');

		$test_chapter2 = Chapter::create(array(
			'chapter_name' => 'CHPTR2',
			'priority' => 2,
			'note' => 'Description_CHPTR2',
			'project_id' => $test_project->id
		));

		$this->command->info('Chapter 2 created');

		$test_activity3 = Activity::create(array(
			'activity_name' => 'CHPTR2_ACT3_CON',
			'priority' => 3,
			'note' => 'Description_CHPTR2_ACT3_CON',
			'chapter_id' => $test_chapter2->id,
			'part_id' => $test_part_contract->id,
			'part_type_id' => $test_part_type_calc->id,
			//'detail_id' => $test_detail->id,
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

		$this->command->info('Activity 3 created');

		$test_activity4 = Activity::create(array(
			'activity_name' => 'CHPTR2_ACT4_SUBCON',
			'priority' => 4,
			'note' => 'Description_CHPTR2_ACT4_SUBCON',
			'chapter_id' => $test_chapter2->id,
			'part_id' => $test_part_subcontract->id,
			'part_type_id' => $test_part_type_calc->id,
			//'detail_id' => $test_detail->id,
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

		$this->command->info('Activity 4 created');

		$test_chapter3 = Chapter::create(array(
			'chapter_name' => 'CHPTR3',
			'priority' => 3,
			'note' => 'Description_CHPTR3',
			'project_id' => $test_project->id
		));

		$this->command->info('Chapter 3 created');

		$test_activity5 = Activity::create(array(
			'activity_name' => 'CHPTR3_ACT5_CON',
			'priority' => 5,
			'note' => 'Description_CHPTR3_ACT5_CON',
			'chapter_id' => $test_chapter3->id,
			'part_id' => $test_part_contract->id,
			'part_type_id' => $test_part_type_calc->id,
			//'detail_id' => $test_detail->id,
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

		$this->command->info('Activity 5 created');

		$test_activity6 = Activity::create(array(
			'activity_name' => 'CHPTR3_ACT6_SUBCON',
			'priority' => 6,
			'note' => 'Description_CHPTR3_ACT6_SUBCON',
			'chapter_id' => $test_chapter3->id,
			'part_id' => $test_part_subcontract->id,
			'part_type_id' => $test_part_type_calc->id,
			//'detail_id' => $test_detail->id,
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

		$this->command->info('Activity 6 created');

		$test_activity7 = Activity::create(array(
			'activity_name' => 'CHPTR3_ACT7_CON_EST',
			'priority' => 7,
			'note' => 'Description_CHPTR3_ACT7_CON_EST',
			'chapter_id' => $test_chapter3->id,
			'part_id' => $test_part_contract->id,
			'part_type_id' => $test_part_type_est->id,
			//'detail_id' => $test_detail->id,
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

		$this->command->info('Activity 7 created');

		$test_activity8 = Activity::create(array(
			'activity_name' => 'CHPTR3_ACT8_SUBCON_EST',
			'priority' => '8',
			'note' => 'Description_CHPTR3_ACT8_SUBCON_EST',
			'chapter_id' => $test_chapter3->id,
			'part_id' => $test_part_subcontract->id,
			'part_type_id' => $test_part_type_est->id,
			//'detail_id' => $test_detail->id,
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

		$this->command->info('Activity 8 created');

		$test_chapter4 = Chapter::create(array(
			'chapter_name' => 'CHPTR4',
			'priority' => '4',
			'note' => 'Description_CHPTR4',
			'project_id' => $test_project->id
		));

		$this->command->info('Chapter 4 created');

		$test_activity9 = Activity::create(array(
			'activity_name' => 'CHPTR4_ACT9_CON_EST',
			'priority' => '9',
			'note' => 'Description_CHPTR4_ACT9_CON_EST',
			'chapter_id' => $test_chapter4->id,
			'part_id' => $test_part_contract->id,
			'part_type_id' => $test_part_type_est->id,
			//'detail_id' => $test_detail->id,
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

		$this->command->info('Activity 9 created');

		$test_activity10 = Activity::create(array(
			'activity_name' => 'CHPTR4_ACT10_SUBCON_EST',
			'priority' => '10',
			'note' => 'Description_CHPTR4_ACT10_SUBCON_EST',
			'chapter_id' => $test_chapter4->id,
			'part_id' => $test_part_subcontract->id,
			'part_type_id' => $test_part_type_est->id,
			//'detail_id' => $test_detail->id,
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

		$this->command->info('Activity 10 created');

		$test_activity11 = Activity::create(array(
			'activity_name' => 'CHPTR4_ACT11_CON_MORE',
			'priority' => 11,
			'note' => 'Description_CHPTR4_ACT11_CON_MORE',
			'chapter_id' => $test_chapter4->id,
			'part_id' => $test_part_contract->id,
			'part_type_id' => $test_part_type_calc ->id,
			'detail_id' => $test_detail_more->id,
			'tax_calc_labor_id' => $test_tax1->id,
			'tax_calc_material_id' => $test_tax1->id,
			'tax_calc_equipment_id' => $test_tax1->id,
			'tax_more_labor_id' => $test_tax1->id,
			'tax_more_material_id' => $test_tax2->id,
			'tax_more_equipment_id' => $test_tax3->id,
			'tax_estimate_labor_id' => $test_tax1->id,
			'tax_estimate_material_id' => $test_tax1->id,
			'tax_estimate_equipment_id' => $test_tax1->id
		));

		$this->command->info('Activity 11 created');

		$test_activity12 = Activity::create(array(
			'activity_name' => 'CHPTR4_ACT12_SUBCON_MORE',
			'priority' => '12',
			'note' => 'Description_CHPTR4_ACT12_SUBCON_MORE',
			'chapter_id' => $test_chapter4->id,
			'part_id' => $test_part_subcontract->id,
			'part_type_id' => $test_part_type_calc->id,
			'detail_id' => $test_detail_more->id,
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

		$this->command->info('Activity 12 created');

		$test_chapter5 = Chapter::create(array(
			'chapter_name' => 'CHPTR5',
			'priority' => '5',
			'note' => 'Description_CHPTR5',
			'project_id' => $test_project->id
		));

		$this->command->info('Chapter 5 created');

			$test_activity13 = Activity::create(array(
			'activity_name' => 'CHPTR5_ACT13_CON_MORE',
			'priority' => '13',
			'note' => 'Description_CHPTR4_ACT13_CON_MORE',
			'chapter_id' => $test_chapter5->id,
			'part_id' => $test_part_contract->id,
			'part_type_id' => $test_part_type_calc->id,
			'detail_id' => $test_detail_more->id,
			'tax_calc_labor_id' => $test_tax3->id,
			'tax_calc_material_id' => $test_tax3->id,
			'tax_calc_equipment_id' => $test_tax3->id,
			'tax_more_labor_id' => $test_tax3->id,
			'tax_more_material_id' => $test_tax3->id,
			'tax_more_equipment_id' => $test_tax3->id,
			'tax_estimate_labor_id' => $test_tax3->id,
			'tax_estimate_material_id' => $test_tax1->id,
			'tax_estimate_equipment_id' => $test_tax1->id
		));

		$this->command->info('Activity 13 created');

		$test_timesheet = Timesheet::create(array(
			'register_date' => '09-10-2014',
			'register_hour' => '24',
			'note' => 'omschrijving bij uren invoer',
			'timesheet_kind_id' => $test_timesheet_kind_name->id,
			'activity_id' => $test_activity10->id
		));

		$this->command->info('Timesheet created');

		Purchase::create(array(
			'amount' => '33',
			'note' => 'aankoop factuur omschrijving',
			'register_date' => '09-10-2014',
			'project_id' => $test_project->id,
			'kind_id' => $test_purchase_kind_name->id,
			'relation_id' => $test_relation->id
		));

		$this->command->info('Purchase created');

		$calculation_labor_activity1 = CalculationLabor::create(array(
			'rate' => '35.00',
			'amount' => '1',
 			'isless' => false,
			'activity_id' => $test_activity1->id
		));

		$this->command->info('CalculationLabor Activity 1 created');

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

		$this->command->info('CalculationMaterial Activity 1 created');
		$this->command->info('CalculationEquipment Activity 1 created');

		$calculation_labor_activity2 = CalculationLabor::create(array(
			'rate' => '35.00',
			'amount' => '2',
			'isless' => false,
			'activity_id' => $test_activity2->id
		));

		$this->command->info('CalculationLabor Activity 2 created');

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

		$this->command->info('CalculationMaterial Activity 2 created');
		$this->command->info('CalculationEquipment Activity 2 created');

		$calculation_labor_activity3 = CalculationLabor::create(array(
			'rate' => '35.00',
			'amount' => '3',
			'isless' => false,
			'activity_id' => $test_activity3->id
		));

		$this->command->info('CalculationLabor Activity 3 created');

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

		$this->command->info('CalculationMaterial Activity 3 created');
		$this->command->info('CalculationEquipment Activity 3 created');

		$calculation_labor_activity4 = CalculationLabor::create(array(
			'rate' => '35.00',
			'amount' => '4',
			'isless' => false,
			'activity_id' => $test_activity4->id
		));

		$this->command->info('CalculationLabor Activity 4 created');

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

		$this->command->info('CalculationMaterial Activity 4 created');
		$this->command->info('CalculationEquipment Activity 4 created');

		$calculation_labor_activity5 = CalculationLabor::create(array(
			'rate' => '35.00',
			'amount' => '5',
			'less_amount' =>'4',
			'isless' => true,
			'activity_id' => $test_activity5->id
		));

		$this->command->info('CalculationLabor Activity 5 created');
		$this->command->info('CalculationLabor Activity 5 LESS created');

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

		$this->command->info('CalculationMaterial Activity 5 created');
		$this->command->info('CalculationEquipment Activity 5 created');
		$this->command->info('CalculationMaterial Activity 5 LESS created');
		$this->command->info('CalculationEquipment Activity 5 LESS created');

		$calculation_labor_activity6 = CalculationLabor::create(array(
			'rate' => '35.00',
			'amount' => '6',
			'less_amount' => '5',
			'isless' => true,
			'activity_id' => $test_activity6->id
		));

		$this->command->info('CalculationLabor Activity 6 created');
		$this->command->info('CalculationLabor Activity 6 LESS created');

		$calculation_material_activity6_1 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR3_ACT6_SUBCON_1',
			'unit' => 'm',
			'rate' => '1',
			'amount' => '1.90',
			'less_rate' => '0.1',
			'less_amount' => '1.90',
			'isless' => true,
			'activity_id' => $test_activity6->id
		));
		$calculation_equipment_activity6_1 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR3_ACT6_SUBCON_a',
			'unit' => 'stuk',
			'rate' => '1',
			'amount' => '1.92',
			'less_rate' => '0.1',
			'less_amount' => '1.92',
			'isless' => true,
			'activity_id' => $test_activity6->id
		));
		$calculation_material_activity6_2 = CalculationMaterial::create(array(
			'material_name' => 'CHPTR3_ACT6_SUBCON_2',
			'unit' => 'm',
			'rate' => '2',
			'amount' => '1.90',
			'less_rate' => '0.2',
			'less_amount' => '1.90',
			'isless' => true,
			'activity_id' => $test_activity6->id
		));
		$calculation_equipment_activity6_2 = CalculationEquipment::create(array(
			'equipment_name' => 'CHPTR3_ACT6_SUBCON_b',
			'unit' => 'stuk',
			'rate' => '2',
			'amount' => '1.91',
			'less_rate' => '0.2',
			'less_amount' => '1.91',
			'isless' => true,
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

		$this->command->info('CalculationMaterial Activity 6 created');
		$this->command->info('CalculationEquipment Activity 6 created');
		$this->command->info('CalculationMaterial Activity 6 LESS created');
		$this->command->info('CalculationEquipment Activity 6 LESS created');

		$calculation_labor_activity7 = EstimateLabor::create(array(
			'rate' => '35.00',
			'amount' => '7',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity7->id
		));

		$this->command->info('CalculationLabor Activity 7 ESTIM created');

		$calculation_material_activity7_1 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR3_ACT7_SUBCON_1',
			'unit' => 'm',
			'rate' => '1',
			'amount' => '1.90',
			//'set_material_name' => 'CHPTR3_ACT7_SUBCON_1_SET',
			//'set_unit' => 'm1',
			//'set_rate' => '11',
			//'set_amount' => '19',
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

		$this->command->info('CalculationMaterial Activity 7 ESTIM created');
		$this->command->info('CalculationEquipment Activity 7 ESTIM created');

		$calculation_labor_activity8 = EstimateLabor::create(array(
			'rate' => '35.00',
			'amount' => '8',
			'set_amount' => '10',
			'original' => true,
			'isset' => true,
			'activity_id' => $test_activity8->id
		));

		$this->command->info('CalculationLabor ESTIM created');

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

		$this->command->info('CalculationMaterial Activity 8 ESTIM created');
		$this->command->info('CalculationEquipment Activity 8 ESTIM created');

		$calculation_labor_activity9 = EstimateLabor::create(array(
			'rate' => '35.00',
			'amount' => '7',
			'set_amount' => '10.10',
			'original' => true,
			'isset' => true,
			'activity_id' => $test_activity9->id
		));

		$this->command->info('CalculationLabor Activity 9 ESTIM created');

		$calculation_material_activity9_1 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR4_ACT9_CON_1',
			'unit' => 'm',
			'rate' => '1',
			'amount' => '1.90',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity9->id
		));
		$calculation_equipment_activity9_1 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR4_ACT9_CON_a',
			'unit' => 'stuk',
			'rate' => '1',
			'amount' => '1.92',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity9->id
		));
		$calculation_material_activity9_2 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR4_ACT9_CON_2',
			'unit' => 'm',
			'rate' => '2',
			'amount' => '2.90',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity9->id
		));
		$calculation_equipment_activity9_2 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR4_ACT9_CON_b',
			'unit' => 'stuk',
			'rate' => '2',
			'amount' => '3.91',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity9->id
		));
		$calculation_material_activity9_3 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR4_ACT9_CON_3',
			'unit' => 'm',
			'rate' => '3',
			'amount' => '1.29',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity9->id
		));
		$calculation_equipment_activity9_3 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR4_ACT9_CON_c',
			'unit' => 'stuk',
			'rate' => '3',
			'amount' => '3.19',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity9->id
		));
		$calculation_material_activity9_4 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR4_ACT9_CON_4',
			'unit' => 'm',
			'rate' => '4',
			'amount' => '1.29',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity9->id
		));
		$calculation_equipment_activity9_4 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR4_ACT9_CON_d',
			'unit' => 'stuk',
			'rate' => '4',
			'amount' => '3.19',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity9->id
		));
		$calculation_material_activity9_5 = EstimateMaterial::create(array(
			'material_name' => 'CHPTR4_ACT9_CON_5',
			'unit' => 'm',
			'rate' => '5',
			'amount' => '3.29',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity9->id
		));
		$calculation_equipment_activity9_5 = EstimateEquipment::create(array(
			'equipment_name' => 'CHPTR4_ACT9_CON_e',
			'unit' => 'stuk',
			'rate' => '5',
			'amount' => '3.19',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity9->id
		));

		$this->command->info('CalculationMaterial Activity 9 ESTIM created');
		$this->command->info('CalculationEquipment Activity 9 ESTIM created');

		$calculation_labor_activity10 = EstimateLabor::create(array(
			'rate' => '35.00',
			'amount' => '8',
			'original' => true,
			'isset' => false,
			'activity_id' => $test_activity10->id
		));

		$this->command->info('CalculationLabor Activity 10 ESTIM created');

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

		$this->command->info('CalculationMaterial Activity 10 ESTIM created');
		$this->command->info('CalculationEquipment Activity 10 ESTIM created');

		EstimateMaterial::create(array(
			'set_material_name' => 'CHPTR4_ACT8_SUBCON_6_SET',
			'set_unit' => 'm2',
			'set_rate' => '1',
			'set_amount' => '1.1',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity8->id
		));
		EstimateMaterial::create(array(
			'set_material_name' => 'CHPTR4_ACT8_SUBCON_7_SET',
			'set_unit' => 'm2',
			'set_rate' => '2',
			'set_amount' => '2.2',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity8->id
		));
		EstimateMaterial::create(array(
			'set_material_name' => 'CHPTR4_ACT8_SUBCON_8_SET',
			'set_unit' => 'm2',
			'set_rate' => '3',
			'set_amount' => '3.3',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity8->id
		));
		EstimateMaterial::create(array(
			'set_material_name' => 'CHPTR4_ACT8_SUBCON_9_SET',
			'set_unit' => 'm2',
			'set_rate' => '4',
			'set_amount' => '4.4',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity8->id
		));
		EstimateMaterial::create(array(
			'set_material_name' => 'CHPTR4_ACT8_SUBCON_10_SET',
			'set_unit' => 'm2',
			'set_rate' => '5',
			'set_amount' => '5.5',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity8->id
		));

		EstimateEquipment::create(array(
			'set_equipment_name' => 'CHPTR4_ACT8_SUBCON_f_SET',
			'set_unit' => 'stuk',
			'set_rate' => '6',
			'set_amount' => '6.6',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity8->id
		));
		EstimateEquipment::create(array(
			'set_equipment_name' => 'CHPTR4_ACT8_SUBCON_g_SET',
			'set_unit' => 'stuk',
			'set_rate' => '7',
			'set_amount' => '7.7',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity8->id
		));
		EstimateEquipment::create(array(
			'set_equipment_name' => 'CHPTR4_ACT8_SUBCON_h_SET',
			'set_unit' => 'stuk',
			'set_rate' => '8',
			'set_amount' => '8.8',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity8->id
		));
		EstimateEquipment::create(array(
			'set_equipment_name' => 'CHPTR4_ACT8_SUBCON_i_SET',
			'set_unit' => 'stuk',
			'set_rate' => '9',
			'set_amount' => '9.9',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity8->id
		));
		EstimateEquipment::create(array(
			'set_equipment_name' => 'CHPTR4_ACT8_SUBCON_j_SET',
			'set_unit' => 'stuk',
			'set_rate' => '10',
			'set_amount' => '10.10',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity8->id
		));
		$this->command->info('Estimate_Set_Equipment_Created');

		$this->command->info('EstimateMAterial SET Activity 8 added');
		$this->command->info('EstimateEquipment SET Activity 8 added');

		EstimateMaterial::create(array(
			'set_material_name' => 'CHPTR4_ACT9_CON_6_SET',
			'set_unit' => 'm2',
			'set_rate' => '1',
			'set_amount' => '0.1',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity9->id
		));
		EstimateMaterial::create(array(
			'set_material_name' => 'CHPTR4_ACT9_CON_7_SET',
			'set_unit' => 'm2',
			'set_rate' => '2',
			'set_amount' => '0.2',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity9->id
		));
		EstimateMaterial::create(array(
			'set_material_name' => 'CHPTR4_ACT9_CON_8_SET',
			'set_unit' => 'm2',
			'set_rate' => '3',
			'set_amount' => '0.3',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity9->id
		));
		EstimateMaterial::create(array(
			'set_material_name' => 'CHPTR4_ACT9_CON_9_SET',
			'set_unit' => 'm2',
			'set_rate' => '4',
			'set_amount' => '0.4',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity9->id
		));
		EstimateMaterial::create(array(
			'set_material_name' => 'CHPTR4_ACT9_CON_10_SET',
			'set_unit' => 'm2',
			'set_rate' => '5',
			'set_amount' => '0.5',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity9->id
		));

		EstimateEquipment::create(array(
			'set_equipment_name' => 'CHPTR4_ACT9_CON_f_SET',
			'set_unit' => 'stuk',
			'set_rate' => '6',
			'set_amount' => '0.6',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity9->id
		));
		EstimateEquipment::create(array(
			'set_equipment_name' => 'CHPTR4_ACT9_CON_g_SET',
			'set_unit' => 'stuk',
			'set_rate' => '7',
			'set_amount' => '0.7',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity9->id
		));
		EstimateEquipment::create(array(
			'set_equipment_name' => 'CHPTR4_ACT9_CON_h_SET',
			'set_unit' => 'stuk',
			'set_rate' => '8',
			'set_amount' => '0.8',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity9->id
		));
		EstimateEquipment::create(array(
			'set_equipment_name' => 'CHPTR4_ACT9_CON_i_SET',
			'set_unit' => 'stuk',
			'set_rate' => '9',
			'set_amount' => '0.9',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity9->id
		));
		EstimateEquipment::create(array(
			'set_equipment_name' => 'CHPTR4_ACT9_CON_j_SET',
			'set_unit' => 'stuk',
			'set_rate' => '10',
			'set_amount' => '1',
			'original' => false,
			'isset' => true,
			'activity_id' => $test_activity9->id
		));
		$this->command->info('EstimateMaterial SET Activity 9 added');
		$this->command->info('EstimateEquipment SET Activity 9 added');

		$calculation_labor_activity11 = MoreLabor::create(array(
			'rate' => '45',
			'amount' => '10.1',
			'note' => 'vrije text',
			'activity_id' => $test_activity11->id
		));
		$this->command->info('MoreLabor created');

		$calculation_material_activity11_1 = MoreMaterial::create(array(
			'material_name' => 'CHPTR4_ACT11_CON_1',
			'unit' => 'm',
			'rate' => '111',
			'amount' => '1',
			'activity_id' => $test_activity11->id
		));
		$calculation_equipment_activity11_1 = MoreEquipment::create(array(
			'equipment_name' => 'CHPTR4_ACT11_CON_a',
			'unit' => 'm',
			'rate' => '111',
			'amount' => '2',
			'activity_id' => $test_activity11->id
		));
		$calculation_material_activity11_2 = MoreMaterial::create(array(
			'material_name' => 'CHPTR4_ACT11_CON_2',
			'unit' => 'm',
			'rate' => '222',
			'amount' => '1',
			'activity_id' => $test_activity11->id
		));
		$calculation_equipment_activity11_1 = MoreEquipment::create(array(
			'equipment_name' => 'CHPTR4_ACT11_CON_b',
			'unit' => 'm',
			'rate' => '222',
			'amount' => '2',
			'activity_id' => $test_activity11->id
		));

		$this->command->info('MoreMaterial Activity 11 created');
		$this->command->info('MoreEquipment Activity 11 created');

			$calculation_labor_activity12 = MoreLabor::create(array(
			'rate' => '45',
			'amount' => '10.2',
			'note' => 'vrije tekst',
			'activity_id' => $test_activity12->id
		));
		$this->command->info('MoreLabor created');

		$calculation_material_activity12_1 = MoreMaterial::create(array(
			'material_name' => 'CHPTR4_ACT12_CON_1',
			'unit' => 'm',
			'rate' => '333',
			'amount' => '1',
			'activity_id' => $test_activity12->id
		));
		$calculation_equipment_activity12_1 = MoreEquipment::create(array(
			'equipment_name' => 'CHPTR4_ACT12_CON_a',
			'unit' => 'm',
			'rate' => '333',
			'amount' => '2',
			'activity_id' => $test_activity12->id
		));
		$calculation_material_activity12_2 = MoreMaterial::create(array(
			'material_name' => 'CHPTR4_ACT12_CON_2',
			'unit' => 'm',
			'rate' => '444',
			'amount' => '1',
			'activity_id' => $test_activity12->id
		));
		$calculation_equipment_activity12_1 = MoreEquipment::create(array(
			'equipment_name' => 'CHPTR4_ACT12_CON_b',
			'unit' => 'm',
			'rate' => '444',
			'amount' => '2',
			'activity_id' => $test_activity12->id
		));

		$this->command->info('MoreMaterial Activity 12 created');
		$this->command->info('MoreEquipment Activity 12 created');

		$calculation_labor_activity13 = MoreLabor::create(array(
			'rate' => '45',
			'amount' => '10.2',
			'note' => 'vrije tekst',
			'activity_id' => $test_activity13->id
		));
		$this->command->info('MoreLabor created');

		$calculation_material_activity13_1 = MoreMaterial::create(array(
			'material_name' => 'CHPTR4_ACT13_CON_1',
			'unit' => 'm',
			'rate' => '333',
			'amount' => '1',
			'activity_id' => $test_activity13->id
		));
		$calculation_equipment_activity13_1 = MoreEquipment::create(array(
			'equipment_name' => 'CHPTR4_ACT13_CON_a',
			'unit' => 'm',
			'rate' => '333',
			'amount' => '2',
			'activity_id' => $test_activity13->id
		));
		$calculation_material_activity13_2 = MoreMaterial::create(array(
			'material_name' => 'CHPTR4_ACT13_CON_2',
			'unit' => 'm',
			'rate' => '444',
			'amount' => '1',
			'activity_id' => $test_activity13->id
		));
		$calculation_equipment_activity13_1 = MoreEquipment::create(array(
			'equipment_name' => 'CHPTR4_ACT13_CON_b',
			'unit' => 'm',
			'rate' => '444',
			'amount' => '2',
			'activity_id' => $test_activity13->id
		));

		Offer::create(array(
			'description' => 'test',
			'closure' => 'test',
			'downpayment' => false,
			'downpayment_amount' => '100',
			'auto_email_reminder' => false,
			'offer_finish' => '2015-07-02',
			'deliver_id' => '1',
			'valid_id' => '1',
			'to_contact_id' => $test_contact_1->id,
			'from_contact_id' => $test_contact_2->id,
			'invoice_quantity' => '3',
			'project_id' => '1',
			'resource_id' => '1',
			'project_id' => $test_project->id,
		));

		Invoice::create(array(
			'invoice_close' => false,
			'isclose' => false,
			'priority' => '3',
			'description' => 'test1',
			'reference' => '1',
			'invoice_code' => 'test1',
			'book_code' => '0',
			'amount' => '1000',
			'payment_condition' => '1',
			'bill_date' => '2015-04-01',
			'payment_date' => '2015-04-01',
			'closure' => 'test1',
			'auto_email_reminder' => true,
			'offer_id' => '1',
		));

		Invoice::create(array(
			'invoice_close' => false,
			'isclose' => false,
			'priority' => '2',
			'description' => 'test2',
			'reference' => '2',
			'invoice_code' => 'test2',
			'book_code' => '0',
			'amount' => '100',
			'payment_condition' => '2',
			'bill_date' => '2015-05-01',
			'payment_date' => '2015-05-01',
			'closure' => 'test2',
			'auto_email_reminder' => true,
			'offer_id' => '1',
		));

		Invoice::create(array(
			'invoice_close' => false,
			'isclose' => true,
			'priority' => '1',
			'description' => 'test3',
			'reference' => '2',
			'invoice_code' => 'test3',
			'book_code' => '0',
			'amount' => '100',
			'payment_condition' => '3',
			'bill_date' => '2015-05-01',
			'payment_date' => '2015-05-01',
			'closure' => 'test3',
			'auto_email_reminder' => true,
			'offer_id' => '1',
		));

		$this->command->info('MoreMaterial Activity 13 created');
		$this->command->info('MoreEquipment Activity 13 created');
	}
 }
