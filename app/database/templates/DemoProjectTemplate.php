<?php

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
		$relation->company_name		= 'Demo bedrijf';
		$relation->address_street	= 'Demostraat';
		$relation->address_number	= '1';
		$relation->address_postal	= '1234DE';
		$relation->address_city		= 'Demostad';
		$relation->debtor_code 		= 'DEMO123';
		$relation->kvk		 		= '12345678';
		$relation->btw 				= 'NL1234567890B1';
		$relation->note 			= 'Dit is een demo relatie';
		$relation->email 			= 'demo@relatie.nl';
		$relation->phone 			= '0101111111';
		$relation->website 			= 'http://www.demobedrijf.nl';
		$relation->user_id 			= $userid;
		$relation->type_id 			= $relationtype->id;
		$relation->kind_id 			= $relationkind->id;
		$relation->province_id 		= $province->id;
		$relation->country_id 		= $country->id;

		$relation->save();

		$project = new Project;
		$project->project_name 		= 'Demo project';
		$project->address_street 	= 'Demolaan';
		$project->address_number 	= '2';
		$project->address_postal 	= '5678MO';
		$project->address_city 		= 'Demodorp';
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
		$contact->email 			= 'demo@eigenaar.nl';
		$contact->mobile 			= '0622222222';
		$contact->phone 			= '0102222222';
		$contact->note 				= 'Demo contactpersoon van relatie';
		$contact->relation_id 		= $relation->id;
		$contact->function_id 		= $contact_function->id;

		$contact->save();

		$iban = new Iban;
		$iban->iban					='NL45ING0111111111';
		$iban->iban_name			='Demo Eigenaar';
		$iban->user_id				= $userid;
		$iban->relation_id			= $relation->id;

		$iban->save();
	}
 }





		$part_contract = Part::where('part_name','=','contracting')->first();
		$part_type_calc = PartType::where('type_name','=','calculation')->first();
		$part_subcontract = Part::where('part_name','=','subcontracting')->first();
		$part_type_est = PartType::where('type_name','=','estimate')->first();
		$tax1 = Tax::where('tax_rate','=','21')->first();
		$tax2 = Tax::where('tax_rate','=','6')->first();
		$tax3 = Tax::where('tax_rate','=','0')->first();







 $calculation_labor_activity1 = CalculationLabor::create(array(
 'rate' => '35.00',
 'amount' => '10',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

 $calculation_material_activity1_1 = CalculationMaterial::create(array(
 'material_name' => 'Ondervloer',
 'unit' => 'm2',
 'rate' => '9.50',
 'amount' => '50',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$calculation_material_activity1_2 = CalculationMaterial::create(array(
 'material_name' => 'Laminaat',
 'unit' => 'm2',
 'rate' => '55',
 'amount' => '50',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

 $calculation_material_activity1_3 = CalculationMaterial::create(array(
 'material_name' => 'Plinten',
 'unit' => 'm1',
 'rate' => '2.50',
 'amount' => '90',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

 $activity2 = Activity::create(array(
 'activity_name' => 'Verlagen van het plafond',
 'priority' => '1',
 'note' => 'Het bestaande plafond wordt verlaagd met een regelwerk en gipsplaten. Het plafond wordt 10 cm verlaagd. In het nieuwe plafond komen 10 lichtspots. ',
 'chapter_id' => $chapter1->id,
 'tax_estimate_equipment_id' => $tax1->id
 ));

 $calculation_labor_activity2 = CalculationLabor::create(array(
 'rate' => '35.00',
 'amount' => '8',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

 $calculation_material_activity2_1 = CalculationMaterial::create(array(
 'material_name' => 'Vuren geschaafd 18x69 mm',
 'unit' => 'm1',
 'rate' => '0.78',
 'amount' => '50',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$calculation_material_activity2_2 = CalculationMaterial::create(array(
 'material_name' => 'Vuren geschaafd 44x69 mm',
 'unit' => 'm1',
 'rate' => '1.25',
 'amount' => '120',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$calculation_material_activity2_3 = CalculationMaterial::create(array(
 'material_name' => 'Stucplaten 60x200 cm',
 'unit' => 'stuk',
 'rate' => '2.00',
 'amount' => '40',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$calculation_material_activity2_4 = CalculationMaterial::create(array(
 'material_name' => 'Gipsplaatschroeven',
 'unit' => 'doos',
 'rate' => '10.00',
 'amount' => '1',
 'isless' => false,
 'activity_id' => $activity1->id
 ));



//ONDERAANNEMING
$chapter2 = Chapter::create(array(
 'chapter_name' => 'Badkamer',
 'priority' => '1',
 'note' => 'Description_CHPTR1',
 'project_id' => $project->id
 ));

$activity3 = Activity::create(array(
 'activity_name' => 'Tegelen van de wanden',
 'priority' => '1',
 'note' => 'De oude tegels blijven zitten en over deze tegels komen nieuwe tegels.',
 'chapter_id' => $chapter1->id,
'tax_estimate_equipment_id' => $tax1->id
 ));

 $calculation_labor_activity3 = CalculationLabor::create(array(
 'rate' => '35.00',
 'amount' => '16',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

 $calculation_material_activity3_1 = CalculationMaterial::create(array(
 'material_name' => 'Tegels Moza',
 'unit' => 'm2',
 'rate' => '16.00',
 'amount' => '25',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$calculation_material_activity3_2 = CalculationMaterial::create(array(
 'material_name' => 'Lijm',
 'unit' => 'zak',
 'rate' => '35.00',
 'amount' => '1',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$calculation_material_activity3_3 = CalculationMaterial::create(array(
 'material_name' => 'Voegsel',
 'unit' => 'doos',
 'rate' => '9.50',
 'amount' => '3',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$calculation_material_activity3_4 = CalculationMaterial::create(array(
 'material_name' => 'Tegelkruisjes',
 'unit' => 'zak',
 'rate' => '2',
 'amount' => '1',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$calculation_material_activity3_5 = CalculationMaterial::create(array(
 'material_name' => 'Primer',
 'unit' => 'emmer',
 'rate' => '16',
 'amount' => '1',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

//ONDERAANNEMING
$activity4 = Activity::create(array(
 'activity_name' => 'Tegelen van de vloeren',
 'priority' => '1',
 'note' => 'De oude vloer wordt door de bewoners gesloopt en hierop komt een nieuwe tegelvloer te liggen.',
 'chapter_id' => $chapter1->id,
'tax_estimate_equipment_id' => $tax1->id
 ));

 $calculation_labor_activity4 = CalculationLabor::create(array(
 'rate' => '35.00',
 'amount' => '4',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

 $calculation_material_activity4_1 = CalculationMaterial::create(array(
 'material_name' => 'Vloertegels',
 'unit' => 'm2',
 'rate' => '45.00',
 'amount' => '8',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$calculation_material_activity4_2 = CalculationMaterial::create(array(
 'material_name' => 'Voegsel',
 'unit' => 'doos',
 'rate' => '9.00',
 'amount' => '1',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$calculation_material_activity4_3 = CalculationMaterial::create(array(
 'material_name' => 'Sanitairkit',
 'unit' => 'koker',
 'rate' => '7.50',
 'amount' => '5',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$chapter3 = Chapter::create(array(
 'chapter_name' => 'Slaapkamer',
 'priority' => '1',
 'note' => 'Description_CHPTR1',
 'project_id' => $project->id
 ));

//ONDERAANNEMING
$activity5 = Activity::create(array(
 'activity_name' => 'Sauzen van de wanden',
 'priority' => '1',
 'note' => 'De wanden worden opnieuw gesausd in de kleur 9010.',
 'chapter_id' => $chapter1->id,
'tax_estimate_equipment_id' => $tax1->id
 ));

 $calculation_labor_activity5 = CalculationLabor::create(array(
 'rate' => '35.00',
 'amount' => '8',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

 $calculation_material_activity5_1 = CalculationMaterial::create(array(
 'material_name' => 'Kwasten e.d.',
 'unit' => 'post',
 'rate' => '15.00',
 'amount' => '1',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$calculation_material_activity5_2 = CalculationMaterial::create(array(
 'material_name' => 'Saus',
 'unit' => 'emmer',
 'rate' => '150.00',
 'amount' => '4',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$chapter4 = Chapter::create(array(
 'chapter_name' => 'Zolder',
 'priority' => '1',
 'note' => 'Description_CHPTR1',
 'project_id' => $project->id
 ));

$activity6 = Activity::create(array(
 'activity_name' => 'Isoleren van het dakbeschot',
 'priority' => '1',
 'note' => 'Het dakbeschot op zolder wordt geisoleerd met speciale polystyreen isolatieplaten. Hier onder wordt eerst een laag dampdoorlatende folie aangebracht',
 'chapter_id' => $chapter1->id,
'tax_estimate_equipment_id' => $tax1->id
 ));

 $calculation_labor_activity6 = CalculationLabor::create(array(
 'rate' => '35.00',
 'amount' => '16',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

 $calculation_material_activity6_1 = CalculationMaterial::create(array(
 'material_name' => 'Diverse bevestigingsmiddelen',
 'unit' => 'post',
 'rate' => '100.00',
 'amount' => '1',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$calculation_material_activity6_2 = CalculationMaterial::create(array(
 'material_name' => 'Folie',
 'unit' => 'rol',
 'rate' => '150.00',
 'amount' => '1',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$calculation_material_activity6_3 = CalculationMaterial::create(array(
 'material_name' => 'Isolatieplaten',
 'unit' => 'm2',
 'rate' => '25.00',
 'amount' => '50',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$activity7 = Activity::create(array(
 'activity_name' => 'Plaatsen van een tussenwand',
 'priority' => '1',
 'note' => 'De zolder wordt verdeeld in twee aparte kamers, hiervoor is het nodig om een tussenwand te plaatsen met een deur.',
 'chapter_id' => $chapter1->id,
'tax_estimate_equipment_id' => $tax1->id
 ));

 $calculation_labor_activity7 = CalculationLabor::create(array(
 'rate' => '35.00',
 'amount' => '32',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

 $calculation_material_activity7_1 = CalculationMaterial::create(array(
 'material_name' => 'Opdekdeur',
 'unit' => 'stuk',
 'rate' => '69.00',
 'amount' => '1',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$calculation_material_activity7_2 = CalculationMaterial::create(array(
 'material_name' => 'Diverse bevestigingsmiddelen',
 'unit' => 'post',
 'rate' => '25.00',
 'amount' => '1',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$calculation_material_activity7_3 = CalculationMaterial::create(array(
 'material_name' => 'Gipsplaten',
 'unit' => 'stuk',
 'rate' => '2.00',
 'amount' => '20',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$calculation_material_activity7_4 = CalculationMaterial::create(array(
 'material_name' => 'Binnendeurkozijn aluminium',
 'unit' => 'stuk',
 'rate' => '149.50',
 'amount' => '1',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$calculation_material_activity7_5 = CalculationMaterial::create(array(
 'material_name' => 'Vuren geschaafd 18x69 mm',
 'unit' => 'stuk',
 'rate' => '0.78',
 'amount' => '30',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$calculation_material_activity7_6 = CalculationMaterial::create(array(
 'material_name' => 'Vuren geschaafd 44x69 mm',
 'unit' => 'stuk',
 'rate' => '1.25',
 'amount' => '40',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$calculation_equipment_activity7_1 = CalculationEquipment::create(array(
 'equipment_name' => 'Kamersteiger',
 'unit' => 'stuk',
 'rate' => '75',
 'amount' => '1.01',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$chapter5 = Chapter::create(array(
 'chapter_name' => 'Garage',
 'priority' => '1',
 'note' => 'Description_CHPTR1',
 'project_id' => $project->id
 ));

//ONDERAANNEMING
$activity8 = Activity::create(array(
 'activity_name' => 'Egaliseren van de garagevloer',
 'priority' => '1',
 'note' => 'De garagevloer is niet egaal en dient geegaliseerd te worden met egaline.',
 'chapter_id' => $chapter1->id,
'tax_estimate_equipment_id' => $tax1->id
 ));

 $calculation_labor_activity8 = CalculationLabor::create(array(
 'rate' => '35.00',
 'amount' => '5',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

 $calculation_material_activity8_1 = CalculationMaterial::create(array(
 'material_name' => 'Primer',
 'unit' => 'emmer',
 'rate' => '25.00',
 'amount' => '1',
 'isless' => false,
 'activity_id' => $activity1->id
 ));

$calculation_material_activity8_2 = CalculationMaterial::create(array(
 'material_name' => 'Egaline',
 'unit' => 'zak',
 'rate' => '49.00',
 'amount' => '4',
 'isless' => false,
 'activity_id' => $activity1->id
 ));




 ?>


























