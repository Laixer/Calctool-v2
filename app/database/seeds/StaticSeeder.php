<?php

/*
 * Static Models Only
 * Test are performed on other seeds
 */
class StaticSeeder extends Seeder {

	public function run()
	{
		DB::table('relation_kind')->delete();
		DB::table('relation_type')->delete();
		DB::table('contact_function')->delete();
		DB::table('part_part_detail')->delete();
		DB::table('part_part_type')->delete();
		DB::table('part_type')->delete();
		DB::table('part')->delete();
		DB::table('project_type_project_step')->delete();
		DB::table('project_step')->delete();
		DB::table('project_type')->delete();
		DB::table('country')->delete();
		DB::table('provance')->delete();
		DB::table('user_type')->delete();
		$this->command->info('Tables deleted');
		
		UserType::create(array('user_type' => 'demo'));
		UserType::create(array('user_type' => 'guest'));
		UserType::create(array('user_type' => 'user'));
		UserType::create(array('user_type' => 'admin'));
		UserType::create(array('user_type' => 'system'));
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

		$ProjectType1->projectStep()->attach($ProjectStep2->id);
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

		$ProjectType4->projectStep()->attach($ProjectStep7->id);
		$this->command->info('ProjectType / ProjectStep attached');

		$Part1 = Part::create(array('part_name' => 'contracting'));
		$Part2 = Part::create(array('part_name' => 'subcontracting'));
		$this->command->info('Part created');

		$PartType1 = PartType::create(array('type_name' => 'calculation'));
		$PartType2 = PartType::create(array('type_name' => 'estimate'));
		$this->command->info('PartType created');

		$Part1->partType()->attach($PartType1->id);
		$Part1->partType()->attach($PartType2->id);

		$Part2->partType()->attach($PartType1->id);
		$Part2->partType()->attach($PartType2->id);
		$this->command->info('Part / PartType attached');

		$Detail1 = Detail::create(array('detail_name' => 'more'));
		$Detail2 = Detail::create(array('detail_name' => 'less'));
		$this->command->info('Detail created');

		$PartType1->detail()->attach($Detail1->id);
		$PartType1->detail()->attach($Detail2->id);
		$this->command->info('PartType / Detail attached');

		ContactFunction::create(array('function_name' => 'directeur'));
		ContactFunction::create(array('function_name' => 'werkvoorbereider'));
		ContactFunction::create(array('function_name' => 'calculator'));
		ContactFunction::create(array('function_name' => 'adviseur'));
		ContactFunction::create(array('function_name' => 'projectleider'));
		ContactFunction::create(array('function_name' => 'uitvoerder'));
		$this->command->info('ContactFunction created');

		RelationKind::create(array('kind_name' => 'zakelijk'));
		RelationKind::create(array('kind_name' => 'particulier'));
		$this->command->info('RelationKind created');
	}
 }
