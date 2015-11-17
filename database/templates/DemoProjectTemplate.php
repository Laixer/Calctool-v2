<?php

use \Calctool\Models\Province;
use \Calctool\Models\Country;
use \Calctool\Models\ProjectType;
use \Calctool\Models\RelationType;
use \Calctool\Models\RelationKind;
use \Calctool\Models\ContactFunction;
use \Calctool\Models\Relation;
use \Calctool\Models\Project;
use \Calctool\Models\Contact;
use \Calctool\Models\Part;
use \Calctool\Models\PartType;
use \Calctool\Models\Tax;
use \Calctool\Models\Chapter;
use \Calctool\Models\Activity;
use \Calctool\Models\CalculationLabor;
use \Calctool\Models\CalculationMaterial;
use \Calctool\Models\CalculationEquipment;

/*
 * Static Models Only
 * Template for demo project
 */
class DemoProjectTemplate {

	public static function setup($userid)
	{
		$province = Province::where('province_name','=','zuid-holland')->first();
		$country = Country::where('country_name','=','nederland')->first();
		$projecttype = ProjectType::where('type_name','=','calculatie')->first();
		$relationtype = RelationType::where('type_name','=','adviesbureau')->first();
		$relationkind = RelationKind::where('kind_name','=','zakelijk')->first();
		$contact_function = ContactFunction::where('function_name','=','voorzitter')->first();

		$relation = new Relation;
		$relation->company_name		= 'Demo-relatie';
		$relation->address_street	= 'Demo-straat';
		$relation->address_number	= '1';
		$relation->address_postal	= '1234DE';
		$relation->address_city		= 'Demo-stad';
		$relation->debtor_code 		= 'DEMO123';
		$relation->kvk		 		= '12345678';
		$relation->btw 				= 'NL1234567890B1';
		$relation->note 			= 'Dit is een demo relatie';
		$relation->email 			= 'demo-relatie@calculatietool.com';
		$relation->phone 			= '0101111111';
		$relation->website 			= 'http://www.calculatietool.com';
		$relation->user_id 			= $userid;
		$relation->type_id 			= $relationtype->id;
		$relation->kind_id 			= $relationkind->id;
		$relation->province_id 		= $province->id;
		$relation->country_id 		= $country->id;
		//$iban->iban					='NL45ING0111111111';
		//$iban->iban_name			='Demo Eigenaar';
		$relation->save();

		$project = new Project;
		$project->project_name 		= 'Demo-project';
		$project->address_street 	= 'Demo-laan';
		$project->address_number 	= '2';
		$project->address_postal 	= '5678MO';
		$project->address_city 		= 'Demo-dorp';
		$project->note 				= 'Dit is een demo project';
		$project->hour_rate 		= 35;
		$project->hour_rate_more 	= 45;
		$project->user_id 			= $userid;
		$project->province_id 		= $province->id;
		$project->country_id 		= $country->id;
		$project->type_id 			= $projecttype->id;
		$project->client_id 		= $relation->id;
		$project->profit_calc_contr_mat			= 10;
		$project->profit_calc_contr_equip 		= 11;
		$project->profit_calc_subcontr_mat 		= 12;
		$project->profit_calc_subcontr_equip 	= 13;
		$project->profit_more_contr_mat 		= 14;
		$project->profit_more_contr_equip 		= 15;
		$project->profit_more_subcontr_mat 		= 16;
		$project->profit_more_subcontr_equip	= 17;
		$project->save();

		$contact = new Contact;
		$contact->firstname 		= 'Demo';
		$contact->lastname 			= 'Eigenaar';
		$contact->email 			= 'demo-eigenaar@calculatietool.com';
		$contact->mobile 			= '0622222222';
		$contact->phone 			= '0102222222';
		$contact->note 				= 'Demo contactpersoon van relatie';
		$contact->relation_id 		= $relation->id;
		$contact->function_id 		= $contact_function->id;
		$contact->save();

        $part_contract = Part::where('part_name','=','contracting')->first();
		$part_type_calc = PartType::where('type_name','=','calculation')->first();
		$part_subcontract = Part::where('part_name','=','subcontracting')->first();
		$part_type_est = PartType::where('type_name','=','estimate')->first();
		$tax1 = Tax::where('tax_rate','=','21')->first();
		$tax2 = Tax::where('tax_rate','=','6')->first();
		$tax3 = Tax::where('tax_rate','=','0')->first();

		$chapter1 = new Chapter;
		$chapter1->chapter_name = 'Woonkamer';
		$chapter1->priority = 1;
		$chapter1->project_id = $project->id;
		$chapter1->save();

       	$activity1 = new Activity;
       	$activity1->activity_name = 'Nieuwe vloer van laminaat leggen';
       	$activity1->priority = 1;
       	$activity1->note = 'In de woonkamer wordt kliklaminaat gelegd, incl. ondervloer. De oude vloer wordt door de bewoners verwijdert.';
       	$activity1->chapter_id = $chapter1->id;
       	$activity1->tax_labor_id = $tax1->id;
       	$activity1->tax_material_id = $tax1->id;
       	$activity1->tax_equipment_id = $tax1->id;
       	$activity1->part_id = 1;
       	$activity1->part_type_id = 1;
       	$activity1->save();

		$calculation_labor_activity1 = new CalculationLabor;
		$calculation_labor_activity1->rate = '35.00';
		$calculation_labor_activity1->amount = '10';
		$calculation_labor_activity1->isless = false;
		$calculation_labor_activity1->activity_id = $activity1->id;
		$calculation_labor_activity1->save();

		$calculation_material_activity1_1 = new CalculationMaterial;
		$calculation_material_activity1_1->material_name = 'Ondervloer';
		$calculation_material_activity1_1->unit = 'm2';
		$calculation_material_activity1_1->rate = '12.56';
		$calculation_material_activity1_1->amount = '15';
		$calculation_material_activity1_1->isless = false;
		$calculation_material_activity1_1->activity_id = $activity1->id;
		$calculation_material_activity1_1->save();
		
		$calculation_equipment_activity1_1 = new CalculationEquipment;
		$calculation_equipment_activity1_1->equipment_name = 'Ondervloer snijder';
		$calculation_equipment_activity1_1->unit = 'stuk';
		$calculation_equipment_activity1_1->rate = '1';
		$calculation_equipment_activity1_1->amount = '9.95';
		$calculation_equipment_activity1_1->isless = false;
		$calculation_equipment_activity1_1->activity_id = $activity1->id;
		$calculation_equipment_activity1_1->save();

		$calculation_material_activity1_2 = new CalculationMaterial;
		$calculation_material_activity1_2->material_name = 'laminaat';
		$calculation_material_activity1_2->unit = 'm2';
		$calculation_material_activity1_2->rate = '22.56';
		$calculation_material_activity1_2->amount = '15';
		$calculation_material_activity1_2->isless = false;
		$calculation_material_activity1_2->activity_id = $activity1->id;
		$calculation_material_activity1_2->save();
		
		$calculation_equipment_activity1_2 = new CalculationEquipment;
		$calculation_equipment_activity1_2->equipment_name = 'Laminaat knipper';
		$calculation_equipment_activity1_2->unit = 'stuk';
		$calculation_equipment_activity1_2->rate = '7.95';
		$calculation_equipment_activity1_2->amount = '1';
		$calculation_equipment_activity1_2->isless = false;
		$calculation_equipment_activity1_2->activity_id = $activity1->id;
		$calculation_equipment_activity1_2->save();

		$activity2 = new Activity;
       	$activity2->activity_name = 'Nieuwe vloer van laminaat leggen';
       	$activity2->priority = 1;
       	$activity2->note = 'In de woonkamer wordt kliklaminaat gelegd, incl. ondervloer. De oude vloer wordt door de bewoners verwijdert.';
       	$activity2->chapter_id = $chapter1->id;
       	$activity2->tax_labor_id = $tax1->id;
       	$activity2->tax_material_id = $tax1->id;
       	$activity2->tax_equipment_id = $tax1->id;
       	$activity2->part_id = 1;
       	$activity2->part_type_id = 1;
       	$activity2->save();

		$calculation_labor_activity2 = new CalculationLabor;
		$calculation_labor_activity2->rate = '35.00';
		$calculation_labor_activity2->amount = '10';
		$calculation_labor_activity2->isless = false;
		$calculation_labor_activity2->activity_id = $activity1->id;
		$calculation_labor_activity2->save();

		$calculation_material_activity2_1 = new CalculationMaterial;
		$calculation_material_activity2_1->material_name = 'Ondervloer';
		$calculation_material_activity2_1->unit = 'm2';
		$calculation_material_activity2_1->rate = '12.56';
		$calculation_material_activity2_1->amount = '15';
		$calculation_material_activity2_1->isless = false;
		$calculation_material_activity2_1->activity_id = $activity1->id;
		$calculation_material_activity2_1->save();
		
		$calculation_equipment_activity2_1 = new CalculationEquipment;
		$calculation_equipment_activity2_1->equipment_name = 'Ondervloer snijder';
		$calculation_equipment_activity2_1->unit = 'stuk';
		$calculation_equipment_activity2_1->rate = '1';
		$calculation_equipment_activity2_1->amount = '9.95';
		$calculation_equipment_activity2_1->isless = false;
		$calculation_equipment_activity2_1->activity_id = $activity1->id;
		$calculation_equipment_activity2_1->save();

		$calculation_material_activity2_2 = new CalculationMaterial;
		$calculation_material_activity2_2->material_name = 'laminaat';
		$calculation_material_activity2_2->unit = 'm2';
		$calculation_material_activity2_2->rate = '22.56';
		$calculation_material_activity2_2->amount = '15';
		$calculation_material_activity2_2->isless = false;
		$calculation_material_activity2_2->activity_id = $activity1->id;
		$calculation_material_activity2_2->save();
		
		$calculation_equipment_activity2_2 = new CalculationEquipment;
		$calculation_equipment_activity2_2->equipment_name = 'Laminaat knipper';
		$calculation_equipment_activity2_2->unit = 'stuk';
		$calculation_equipment_activity2_2->rate = '7.95';
		$calculation_equipment_activity2_2->amount = '1';
		$calculation_equipment_activity2_2->isless = false;
		$calculation_equipment_activity2_2->activity_id = $activity1->id;
		$calculation_equipment_activity2_2->save();
     }
  }

  ?>
