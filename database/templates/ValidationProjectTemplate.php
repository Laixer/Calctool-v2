<?php

use BynqIO\CalculatieTool\Models\Province;
use BynqIO\CalculatieTool\Models\Country;
use BynqIO\CalculatieTool\Models\ProjectType;
use BynqIO\CalculatieTool\Models\RelationType;
use BynqIO\CalculatieTool\Models\RelationKind;
use BynqIO\CalculatieTool\Models\ContactFunction;
use BynqIO\CalculatieTool\Models\Relation;
use BynqIO\CalculatieTool\Models\Project;
use BynqIO\CalculatieTool\Models\Contact;
use BynqIO\CalculatieTool\Models\Part;
use BynqIO\CalculatieTool\Models\PartType;
use BynqIO\CalculatieTool\Models\Tax;
use BynqIO\CalculatieTool\Models\Chapter;
use BynqIO\CalculatieTool\Models\Activity;
use BynqIO\CalculatieTool\Models\CalculationLabor;
use BynqIO\CalculatieTool\Models\CalculationMaterial;
use BynqIO\CalculatieTool\Models\CalculationEquipment;
use BynqIO\CalculatieTool\Models\EstimateLabor;
use BynqIO\CalculatieTool\Models\EstimateMaterial;
use BynqIO\CalculatieTool\Models\EstimateEquipment;
use BynqIO\CalculatieTool\Models\Detail;
use BynqIO\CalculatieTool\Models\MoreLabor;
use BynqIO\CalculatieTool\Models\MoreMaterial;
use BynqIO\CalculatieTool\Models\MoreEquipment;


/*
 * Static Models Only
 * Template for validation project
 */
class ValidationProjectTemplate {

	public static function setup($userid)
	{
		$province = Province::where('province_name','=','zuid-holland')->first();
		$country = Country::where('country_name','=','nederland')->first();
		$projecttype = ProjectType::where('type_name','=','calculatie')->first();
		$relationtype = RelationType::where('type_name','=','adviesbureau')->first();
		$relationkind = RelationKind::where('kind_name','=','zakelijk')->first();
		$contact_function = ContactFunction::where('function_name','=','voorzitter')->first();

		$relation = new Relation;
		$relation->company_name		= 'Validatierelatie';
		$relation->address_street	= 'Validatiestraat';
		$relation->address_number	= '1';
		$relation->address_postal	= '1234VA';
		$relation->address_city		= 'Validatiestad';
		$relation->debtor_code 		= 'VAL123';
		$relation->kvk		 		= '12345678';
		$relation->btw 				= 'NL1234567890B1';
		$relation->note 			= 'Dit is een vaidatierelatie';
		$relation->email 			= 'validatie@calculatietool.com';
		$relation->phone 			= '0101111111';
		$relation->website 			= 'http://www.calculatietool.com';
		$relation->user_id 			= $userid;
		$relation->type_id 			= $relationtype->id;
		$relation->kind_id 			= $relationkind->id;
		$relation->province_id 		= $province->id;
		$relation->country_id 		= $country->id;
		$relation->save();

		$project = new Project;
		$project->project_name 		= 'Validatieproject';
		$project->address_street 	= 'Validatielaan';
		$project->address_number 	= '2';
		$project->address_postal 	= '5678LI';
		$project->address_city 		= 'Validatiedorp';
		$project->note 				= 'Dit is een validatieproject';
		$project->hour_rate 		= 10;
		$project->hour_rate_more 	= 20;
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
		$project->tax_reverse		= 'N';
		$project->use_estimate		= 'Y';
		$project->use_more			= 'Y';
		$project->use_less			= 'Y';
		
		$project->save();

		$contact = new Contact;
		$contact->firstname 		= 'Vali';
		$contact->lastname 			= 'Datie';
		$contact->email 			= 'validatie@calculatietool.com';
		$contact->mobile 			= '0622222222';
		$contact->phone 			= '0102222222';
		$contact->note 				= 'Validatie contactpersoon van relatie';
		$contact->relation_id 		= $relation->id;
		$contact->function_id 		= $contact_function->id;
		$contact->gender	 		= 'M';
		$contact->save();

        $part_contract = Part::where('part_name','=','contracting')->first();
		$part_type_calc = PartType::where('type_name','=','calculation')->first();
		$part_subcontract = Part::where('part_name','=','subcontracting')->first();
		$part_type_est = PartType::where('type_name','=','estimate')->first();
		$detail = Detail::where('detail_name','=','more')->first();
		$tax1 = Tax::where('tax_rate','=','21')->first();
		$tax2 = Tax::where('tax_rate','=','6')->first();
		$tax3 = Tax::where('tax_rate','=','0')->first();

		$chapter1 = new Chapter;
		$chapter1->chapter_name = 'C-A-H1';
		$chapter1->priority = 1;
		$chapter1->project_id = $project->id;
		$chapter1->save();

	       	$activity1 = new Activity;
	       	$activity1->activity_name = 'C-A-H1W1-21/21/21';
	       	$activity1->priority = 1;
	       	$activity1->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity1->chapter_id = $chapter1->id;
	       	$activity1->tax_labor_id = $tax1->id;
	       	$activity1->tax_material_id = $tax1->id;
	       	$activity1->tax_equipment_id = $tax1->id;
	       	$activity1->part_id = 1;
	       	$activity1->part_type_id = 1;
	       	$activity1->save();

				$calculation_labor_activity1 = new CalculationLabor;
				$calculation_labor_activity1->rate = '10.00';
				$calculation_labor_activity1->amount = '10';
				$calculation_labor_activity1->isless = true;
				$calculation_labor_activity1->less_amount = '0.0';
				$calculation_labor_activity1->activity_id = $activity1->id;
				$calculation_labor_activity1->save();

					$calculation_material_activity1_1 = new CalculationMaterial;
					$calculation_material_activity1_1->material_name = 'C-A-H1W1-MAT1';
					$calculation_material_activity1_1->unit = '1';
					$calculation_material_activity1_1->rate = '1';
					$calculation_material_activity1_1->amount = '1';
					$calculation_material_activity1_1->isless = true;
					$calculation_material_activity1_1->less_rate = '1';
					$calculation_material_activity1_1->less_amount = '0.1';
					$calculation_material_activity1_1->activity_id = $activity1->id;
					$calculation_material_activity1_1->save();

					$calculation_material_activity1_2 = new CalculationMaterial;
					$calculation_material_activity1_2->material_name = 'C-A-H1W1-MAT2';
					$calculation_material_activity1_2->unit = '2';
					$calculation_material_activity1_2->rate = '1';
					$calculation_material_activity1_2->amount = '2';
					$calculation_material_activity1_2->isless = true;
					$calculation_material_activity1_2->less_rate = '1';
					$calculation_material_activity1_2->less_amount = '0.2';
					$calculation_material_activity1_2->activity_id = $activity1->id;
					$calculation_material_activity1_2->save();

					$calculation_material_activity1_3 = new CalculationMaterial;
					$calculation_material_activity1_3->material_name = 'C-A-H1W1-MAT3';
					$calculation_material_activity1_3->unit = '3';
					$calculation_material_activity1_3->rate = '1';
					$calculation_material_activity1_3->amount = '3';
					$calculation_material_activity1_3->isless = true;
					$calculation_material_activity1_3->less_rate = '1';
					$calculation_material_activity1_3->less_amount = '0.3';
					$calculation_material_activity1_3->activity_id = $activity1->id;
					$calculation_material_activity1_3->save();

					$calculation_material_activity1_4 = new CalculationMaterial;
					$calculation_material_activity1_4->material_name = 'C-A-H1W1-MAT4';
					$calculation_material_activity1_4->unit = '4';
					$calculation_material_activity1_4->rate = '1';
					$calculation_material_activity1_4->amount = '4';
					$calculation_material_activity1_4->isless = true;
					$calculation_material_activity1_4->less_rate = '1';
					$calculation_material_activity1_4->less_amount = '0.4';
					$calculation_material_activity1_4->activity_id = $activity1->id;
					$calculation_material_activity1_4->save();

					$calculation_material_activity1_5 = new CalculationMaterial;
					$calculation_material_activity1_5->material_name = 'C-A-H1W1-MAT5';
					$calculation_material_activity1_5->unit = '5';
					$calculation_material_activity1_5->rate = '1';
					$calculation_material_activity1_5->amount = '5';
					$calculation_material_activity1_5->isless = true;
					$calculation_material_activity1_5->less_rate = '1';
					$calculation_material_activity1_5->less_amount = '0.5';
					$calculation_material_activity1_5->activity_id = $activity1->id;
					$calculation_material_activity1_5->save();
				
						$calculation_equipment_activity1_1 = new CalculationEquipment;
						$calculation_equipment_activity1_1->equipment_name = 'C-A-H1W1-EQU1';
						$calculation_equipment_activity1_1->unit = '6';
						$calculation_equipment_activity1_1->rate = '2';
						$calculation_equipment_activity1_1->amount = '1';
						$calculation_equipment_activity1_1->isless = true;
						$calculation_equipment_activity1_1->less_rate = '2';
						$calculation_equipment_activity1_1->less_amount = '0.1';
						$calculation_equipment_activity1_1->activity_id = $activity1->id;
						$calculation_equipment_activity1_1->save();
						
						$calculation_equipment_activity1_2 = new CalculationEquipment;
						$calculation_equipment_activity1_2->equipment_name = 'C-A-H1W1-EQU2';
						$calculation_equipment_activity1_2->unit = '7';
						$calculation_equipment_activity1_2->rate = '2';
						$calculation_equipment_activity1_2->amount = '2';
						$calculation_equipment_activity1_2->isless = true;
						$calculation_equipment_activity1_2->less_rate = '2';
						$calculation_equipment_activity1_2->less_amount = '0.2';
						$calculation_equipment_activity1_2->activity_id = $activity1->id;
						$calculation_equipment_activity1_2->save();

						$calculation_equipment_activity1_3 = new CalculationEquipment;
						$calculation_equipment_activity1_3->equipment_name = 'C-A-H1W1-EQU3';
						$calculation_equipment_activity1_3->unit = '8';
						$calculation_equipment_activity1_3->rate = '2';
						$calculation_equipment_activity1_3->amount = '3';
						$calculation_equipment_activity1_3->isless = true;
						$calculation_equipment_activity1_3->less_rate = '2';
						$calculation_equipment_activity1_3->less_amount = '0.3';
						$calculation_equipment_activity1_3->activity_id = $activity1->id;
						$calculation_equipment_activity1_3->save();
						
						$calculation_equipment_activity1_4 = new CalculationEquipment;
						$calculation_equipment_activity1_4->equipment_name = 'C-A-H1W1-EQU4';
						$calculation_equipment_activity1_4->unit = '9';
						$calculation_equipment_activity1_4->rate = '2';
						$calculation_equipment_activity1_4->amount = '4';
						$calculation_equipment_activity1_4->isless = true;
						$calculation_equipment_activity1_4->less_rate = '2';
						$calculation_equipment_activity1_4->less_amount = '0.4';
						$calculation_equipment_activity1_4->activity_id = $activity1->id;
						$calculation_equipment_activity1_4->save();

						$calculation_equipment_activity1_5 = new CalculationEquipment;
						$calculation_equipment_activity1_5->equipment_name = 'C-A-H1W1-EQU5';
						$calculation_equipment_activity1_5->unit = '10';
						$calculation_equipment_activity1_5->rate = '2';
						$calculation_equipment_activity1_5->amount = '5';
						$calculation_equipment_activity1_5->isless = true;
						$calculation_equipment_activity1_5->less_rate = '2';
						$calculation_equipment_activity1_5->less_amount = '0.5';
						$calculation_equipment_activity1_5->activity_id = $activity1->id;
						$calculation_equipment_activity1_5->save();

			$activity2 = new Activity;
	       	$activity2->activity_name = 'C-A-H1W2-21/21/6';
	       	$activity2->priority = 2;
	       	$activity2->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity2->chapter_id = $chapter1->id;
	       	$activity2->tax_labor_id = $tax1->id;
	       	$activity2->tax_material_id = $tax1->id;
	       	$activity2->tax_equipment_id = $tax2->id;
	       	$activity2->part_id = 1;
	       	$activity2->part_type_id = 1;
	       	$activity2->save();

				$calculation_labor_activity2 = new CalculationLabor;
				$calculation_labor_activity2->rate = '10.00';
				$calculation_labor_activity2->amount = '11';
				$calculation_labor_activity2->isless = true;
				$calculation_labor_activity2->less_amount = '1.0';
				$calculation_labor_activity2->activity_id = $activity2->id;
				$calculation_labor_activity2->save();

					$calculation_material_activity2_1 = new CalculationMaterial;
					$calculation_material_activity2_1->material_name = 'C-A-H1W2-MAT1';
					$calculation_material_activity2_1->unit = '11';
					$calculation_material_activity2_1->rate = '3';
					$calculation_material_activity2_1->amount = '1';
					$calculation_material_activity2_1->isless = true;
					$calculation_material_activity2_1->less_rate = '3';
					$calculation_material_activity2_1->less_amount = '0.1';
					$calculation_material_activity2_1->activity_id = $activity2->id;
					$calculation_material_activity2_1->save();

					$calculation_material_activity2_2 = new CalculationMaterial;
					$calculation_material_activity2_2->material_name = 'C-A-H1W2-MAT2';
					$calculation_material_activity2_2->unit = '12';
					$calculation_material_activity2_2->rate = '3';
					$calculation_material_activity2_2->amount = '2';
					$calculation_material_activity2_2->isless = true;
					$calculation_material_activity2_2->less_rate = '3';
					$calculation_material_activity2_2->less_amount = '0.2';
					$calculation_material_activity2_2->activity_id = $activity2->id;
					$calculation_material_activity2_2->save();

					$calculation_material_activity2_3 = new CalculationMaterial;
					$calculation_material_activity2_3->material_name = 'C-A-H1W2-MAT3';
					$calculation_material_activity2_3->unit = '13';
					$calculation_material_activity2_3->rate = '3';
					$calculation_material_activity2_3->amount = '3';
					$calculation_material_activity2_3->isless = true;
					$calculation_material_activity2_3->less_rate = '3';
					$calculation_material_activity2_3->less_amount = '0.3';
					$calculation_material_activity2_3->activity_id = $activity2->id;
					$calculation_material_activity2_3->save();

					$calculation_material_activity2_4 = new CalculationMaterial;
					$calculation_material_activity2_4->material_name = 'C-A-H1W2-MAT4';
					$calculation_material_activity2_4->unit = '14';
					$calculation_material_activity2_4->rate = '3';
					$calculation_material_activity2_4->amount = '4';
					$calculation_material_activity2_4->isless = true;
					$calculation_material_activity2_4->less_rate = '3';
					$calculation_material_activity2_4->less_amount = '0.4';
					$calculation_material_activity2_4->activity_id = $activity2->id;
					$calculation_material_activity2_4->save();

					$calculation_material_activity2_5 = new CalculationMaterial;
					$calculation_material_activity2_5->material_name = 'C-A-H1W2-MAT5';
					$calculation_material_activity2_5->unit = '15';
					$calculation_material_activity2_5->rate = '3';
					$calculation_material_activity2_5->amount = '5';
					$calculation_material_activity2_5->isless = true;
					$calculation_material_activity2_5->less_rate = '3';
					$calculation_material_activity2_5->less_amount = '0.5';
					$calculation_material_activity2_5->activity_id = $activity2->id;
					$calculation_material_activity2_5->save();
				
						$calculation_equipment_activity2_1 = new CalculationEquipment;
						$calculation_equipment_activity2_1->equipment_name = 'C-A-H1W2-EQU1';
						$calculation_equipment_activity2_1->unit = '16';
						$calculation_equipment_activity2_1->rate = '4';
						$calculation_equipment_activity2_1->amount = '1';
						$calculation_equipment_activity2_1->isless = true;
						$calculation_equipment_activity2_1->less_rate = '4';
						$calculation_equipment_activity2_1->less_amount = '0.1';
						$calculation_equipment_activity2_1->activity_id = $activity2->id;
						$calculation_equipment_activity2_1->save();
						
						$calculation_equipment_activity2_2 = new CalculationEquipment;
						$calculation_equipment_activity2_2->equipment_name = 'C-A-H1W2-EQU2';
						$calculation_equipment_activity2_2->unit = '17';
						$calculation_equipment_activity2_2->rate = '4';
						$calculation_equipment_activity2_2->amount = '2';
						$calculation_equipment_activity2_2->isless = true;
						$calculation_equipment_activity2_2->less_rate = '4';
						$calculation_equipment_activity2_2->less_amount = '0.2';
						$calculation_equipment_activity2_2->activity_id = $activity2->id;
						$calculation_equipment_activity2_2->save();

						$calculation_equipment_activity2_3 = new CalculationEquipment;
						$calculation_equipment_activity2_3->equipment_name = 'C-A-H1W2-EQU3';
						$calculation_equipment_activity2_3->unit = '18';
						$calculation_equipment_activity2_3->rate = '4';
						$calculation_equipment_activity2_3->amount = '3';
						$calculation_equipment_activity2_3->isless = true;
						$calculation_equipment_activity2_3->less_rate = '4';
						$calculation_equipment_activity2_3->less_amount = '0.3';
						$calculation_equipment_activity2_3->activity_id = $activity2->id;
						$calculation_equipment_activity2_3->save();
						
						$calculation_equipment_activity2_4 = new CalculationEquipment;
						$calculation_equipment_activity2_4->equipment_name = 'C-A-H1W2-EQU4';
						$calculation_equipment_activity2_4->unit = '19';
						$calculation_equipment_activity2_4->rate = '4';
						$calculation_equipment_activity2_4->amount = '4';
						$calculation_equipment_activity2_4->isless = true;
						$calculation_equipment_activity2_4->less_rate = '4';
						$calculation_equipment_activity2_4->less_amount = '0.4';
						$calculation_equipment_activity2_4->activity_id = $activity2->id;
						$calculation_equipment_activity2_4->save();

						$calculation_equipment_activity2_5 = new CalculationEquipment;
						$calculation_equipment_activity2_5->equipment_name = 'C-A-H1W2-EQU5';
						$calculation_equipment_activity2_5->unit = '20';
						$calculation_equipment_activity2_5->rate = '4';
						$calculation_equipment_activity2_5->amount = '5';
						$calculation_equipment_activity2_5->isless = true;
						$calculation_equipment_activity2_5->less_rate = '4';
						$calculation_equipment_activity2_5->less_amount = '0.5';
						$calculation_equipment_activity2_5->activity_id = $activity2->id;
						$calculation_equipment_activity2_5->save();

			$activity3 = new Activity;
	       	$activity3->activity_name = 'C-A-H1W3-21/6/6';
	       	$activity3->priority = 3;
	       	$activity3->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity3->chapter_id = $chapter1->id;
	       	$activity3->tax_labor_id = $tax1->id;
	       	$activity3->tax_material_id = $tax2->id;
	       	$activity3->tax_equipment_id = $tax2->id;
	       	$activity3->part_id = 1;
	       	$activity3->part_type_id = 1;
	       	$activity3->save();

				$calculation_labor_activity3 = new CalculationLabor;
				$calculation_labor_activity3->rate = '10.00';
				$calculation_labor_activity3->amount = '12';
				$calculation_labor_activity3->isless = true;
				$calculation_labor_activity3->less_amount = '2.0';
				$calculation_labor_activity3->activity_id = $activity3->id;
				$calculation_labor_activity3->save();

					$calculation_material_activity3_1 = new CalculationMaterial;
					$calculation_material_activity3_1->material_name = 'C-A-H1W3-MAT1';
					$calculation_material_activity3_1->unit = '21';
					$calculation_material_activity3_1->rate = '5';
					$calculation_material_activity3_1->amount = '1';
					$calculation_material_activity3_1->isless = true;
					$calculation_material_activity3_1->less_rate = '5';
					$calculation_material_activity3_1->less_amount = '0.1';
					$calculation_material_activity3_1->activity_id = $activity3->id;
					$calculation_material_activity3_1->save();

					$calculation_material_activity3_2 = new CalculationMaterial;
					$calculation_material_activity3_2->material_name = 'C-A-H1W3-MAT2';
					$calculation_material_activity3_2->unit = '22';
					$calculation_material_activity3_2->rate = '5';
					$calculation_material_activity3_2->amount = '2';
					$calculation_material_activity3_2->isless = true;
					$calculation_material_activity3_2->less_rate = '5';
					$calculation_material_activity3_2->less_amount = '0.2';
					$calculation_material_activity3_2->activity_id = $activity3->id;
					$calculation_material_activity3_2->save();

					$calculation_material_activity3_3 = new CalculationMaterial;
					$calculation_material_activity3_3->material_name = 'C-A-H1W3-MAT3';
					$calculation_material_activity3_3->unit = '23';
					$calculation_material_activity3_3->rate = '5';
					$calculation_material_activity3_3->amount = '3';
					$calculation_material_activity3_3->isless = true;
					$calculation_material_activity3_3->less_rate = '5';
					$calculation_material_activity3_3->less_amount = '0.3';
					$calculation_material_activity3_3->activity_id = $activity3->id;
					$calculation_material_activity3_3->save();

					$calculation_material_activity3_4 = new CalculationMaterial;
					$calculation_material_activity3_4->material_name = 'C-A-H1W3-MAT4';
					$calculation_material_activity3_4->unit = '24';
					$calculation_material_activity3_4->rate = '5';
					$calculation_material_activity3_4->amount = '4';
					$calculation_material_activity3_4->isless = true;
					$calculation_material_activity3_4->less_rate = '5';
					$calculation_material_activity3_4->less_amount = '0.4';
					$calculation_material_activity3_4->activity_id = $activity3->id;
					$calculation_material_activity3_4->save();

					$calculation_material_activity3_5 = new CalculationMaterial;
					$calculation_material_activity3_5->material_name = 'C-A-H1W3-MAT5';
					$calculation_material_activity3_5->unit = '25';
					$calculation_material_activity3_5->rate = '5';
					$calculation_material_activity3_5->amount = '5';
					$calculation_material_activity3_5->isless = true;
					$calculation_material_activity3_5->less_rate = '5';
					$calculation_material_activity3_5->less_amount = '0.5';
					$calculation_material_activity3_5->activity_id = $activity3->id;
					$calculation_material_activity3_5->save();
				
						$calculation_equipment_activity3_1 = new CalculationEquipment;
						$calculation_equipment_activity3_1->equipment_name = 'C-A-H1W32-EQU1';
						$calculation_equipment_activity3_1->unit = '26';
						$calculation_equipment_activity3_1->rate = '6';
						$calculation_equipment_activity3_1->amount = '1';
						$calculation_equipment_activity3_1->isless = true;
						$calculation_equipment_activity3_1->less_rate = '6';
						$calculation_equipment_activity3_1->less_amount = '0.1';
						$calculation_equipment_activity3_1->activity_id = $activity3->id;
						$calculation_equipment_activity3_1->save();
						
						$calculation_equipment_activity3_2 = new CalculationEquipment;
						$calculation_equipment_activity3_2->equipment_name = 'C-A-H1W3-EQU2';
						$calculation_equipment_activity3_2->unit = '27';
						$calculation_equipment_activity3_2->rate = '6';
						$calculation_equipment_activity3_2->amount = '2';
						$calculation_equipment_activity3_2->isless = true;
						$calculation_equipment_activity3_2->less_rate = '6';
						$calculation_equipment_activity3_2->less_amount = '0.2';
						$calculation_equipment_activity3_2->activity_id = $activity3->id;
						$calculation_equipment_activity3_2->save();

						$calculation_equipment_activity3_3 = new CalculationEquipment;
						$calculation_equipment_activity3_3->equipment_name = 'C-A-H1W3-EQU3';
						$calculation_equipment_activity3_3->unit = '28';
						$calculation_equipment_activity3_3->rate = '6';
						$calculation_equipment_activity3_3->amount = '3';
						$calculation_equipment_activity3_3->isless = true;
						$calculation_equipment_activity3_3->less_rate = '6';
						$calculation_equipment_activity3_3->less_amount = '0.3';
						$calculation_equipment_activity3_3->activity_id = $activity3->id;
						$calculation_equipment_activity3_3->save();
						
						$calculation_equipment_activity3_4 = new CalculationEquipment;
						$calculation_equipment_activity3_4->equipment_name = 'C-A-H1W3-EQU4';
						$calculation_equipment_activity3_4->unit = '29';
						$calculation_equipment_activity3_4->rate = '6';
						$calculation_equipment_activity3_4->amount = '4';
						$calculation_equipment_activity3_4->isless = true;
						$calculation_equipment_activity3_4->less_rate = '6';
						$calculation_equipment_activity3_4->less_amount = '0.4';
						$calculation_equipment_activity3_4->activity_id = $activity3->id;
						$calculation_equipment_activity3_4->save();

						$calculation_equipment_activity3_5 = new CalculationEquipment;
						$calculation_equipment_activity3_5->equipment_name = 'C-A-H1W3-EQU5';
						$calculation_equipment_activity3_5->unit = '30';
						$calculation_equipment_activity3_5->rate = '6';
						$calculation_equipment_activity3_5->amount = '5';
						$calculation_equipment_activity3_5->isless = true;
						$calculation_equipment_activity3_5->less_rate = '6';
						$calculation_equipment_activity3_5->less_amount = '0.5';
						$calculation_equipment_activity3_5->activity_id = $activity3->id;
						$calculation_equipment_activity3_5->save();

			$activity4 = new Activity;
	       	$activity4->activity_name = 'C-A-H1W4-6/6/6';
	       	$activity4->priority = 4;
	       	$activity4->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity4->chapter_id = $chapter1->id;
	       	$activity4->tax_labor_id = $tax2->id;
	       	$activity4->tax_material_id = $tax2->id;
	       	$activity4->tax_equipment_id = $tax2->id;
	       	$activity4->part_id = 1;
	       	$activity4->part_type_id = 1;
	       	$activity4->save();

				$calculation_labor_activity4 = new CalculationLabor;
				$calculation_labor_activity4->rate = '10.00';
				$calculation_labor_activity4->amount = '13';
				$calculation_labor_activity4->isless = true;
				$calculation_labor_activity4->less_amount = '3.0';
				$calculation_labor_activity4->activity_id = $activity4->id;
				$calculation_labor_activity4->save();

					$calculation_material_activity4_1 = new CalculationMaterial;
					$calculation_material_activity4_1->material_name = 'C-A-H1W4-MAT1';
					$calculation_material_activity4_1->unit = '31';
					$calculation_material_activity4_1->rate = '7';
					$calculation_material_activity4_1->amount = '1';
					$calculation_material_activity4_1->isless = true;
					$calculation_material_activity4_1->less_rate = '7';
					$calculation_material_activity4_1->less_amount = '0.1';
					$calculation_material_activity4_1->activity_id = $activity4->id;
					$calculation_material_activity4_1->save();

					$calculation_material_activity4_2 = new CalculationMaterial;
					$calculation_material_activity4_2->material_name = 'C-A-H1W4-MAT2';
					$calculation_material_activity4_2->unit = '32';
					$calculation_material_activity4_2->rate = '7';
					$calculation_material_activity4_2->amount = '2';
					$calculation_material_activity4_2->isless = true;
					$calculation_material_activity4_2->less_rate = '7';
					$calculation_material_activity4_2->less_amount = '0.2';
					$calculation_material_activity4_2->activity_id = $activity4->id;
					$calculation_material_activity4_2->save();

					$calculation_material_activity4_3 = new CalculationMaterial;
					$calculation_material_activity4_3->material_name = 'C-A-H1W4-MAT3';
					$calculation_material_activity4_3->unit = '33';
					$calculation_material_activity4_3->rate = '7';
					$calculation_material_activity4_3->amount = '3';
					$calculation_material_activity4_3->isless = true;
					$calculation_material_activity4_3->less_rate = '7';
					$calculation_material_activity4_3->less_amount = '0.3';
					$calculation_material_activity4_3->activity_id = $activity4->id;
					$calculation_material_activity4_3->save();

					$calculation_material_activity4_4 = new CalculationMaterial;
					$calculation_material_activity4_4->material_name = 'C-A-H1W4-MAT4';
					$calculation_material_activity4_4->unit = '34';
					$calculation_material_activity4_4->rate = '7';
					$calculation_material_activity4_4->amount = '4';
					$calculation_material_activity4_4->isless = true;
					$calculation_material_activity4_4->less_rate = '7';
					$calculation_material_activity4_4->less_amount = '0.4';
					$calculation_material_activity4_4->activity_id = $activity4->id;
					$calculation_material_activity4_4->save();

					$calculation_material_activity4_5 = new CalculationMaterial;
					$calculation_material_activity4_5->material_name = 'C-A-H1W4-MAT5';
					$calculation_material_activity4_5->unit = '35';
					$calculation_material_activity4_5->rate = '7';
					$calculation_material_activity4_5->amount = '5';
					$calculation_material_activity4_5->isless = true;
					$calculation_material_activity4_5->less_rate = '7';
					$calculation_material_activity4_5->less_amount = '0.5';
					$calculation_material_activity4_5->activity_id = $activity4->id;
					$calculation_material_activity4_5->save();
				
						$calculation_equipment_activity4_1 = new CalculationEquipment;
						$calculation_equipment_activity4_1->equipment_name = 'C-A-H1W4-EQU1';
						$calculation_equipment_activity4_1->unit = '36';
						$calculation_equipment_activity4_1->rate = '8';
						$calculation_equipment_activity4_1->amount = '1';
						$calculation_equipment_activity4_1->isless = true;
						$calculation_equipment_activity4_1->less_rate = '8';
						$calculation_equipment_activity4_1->less_amount = '0.1';
						$calculation_equipment_activity4_1->activity_id = $activity4->id;
						$calculation_equipment_activity4_1->save();
						
						$calculation_equipment_activity4_2 = new CalculationEquipment;
						$calculation_equipment_activity4_2->equipment_name = 'C-A-H1W4-EQU2';
						$calculation_equipment_activity4_2->unit = '37';
						$calculation_equipment_activity4_2->rate = '8';
						$calculation_equipment_activity4_2->amount = '2';
						$calculation_equipment_activity4_2->isless = true;
						$calculation_equipment_activity4_2->less_rate = '8';
						$calculation_equipment_activity4_2->less_amount = '0.2';
						$calculation_equipment_activity4_2->activity_id = $activity4->id;
						$calculation_equipment_activity4_2->save();

						$calculation_equipment_activity4_3 = new CalculationEquipment;
						$calculation_equipment_activity4_3->equipment_name = 'C-A-H1W4-EQU3';
						$calculation_equipment_activity4_3->unit = '38';
						$calculation_equipment_activity4_3->rate = '8';
						$calculation_equipment_activity4_3->amount = '3';
						$calculation_equipment_activity4_3->isless = true;
						$calculation_equipment_activity4_3->less_rate = '8';
						$calculation_equipment_activity4_3->less_amount = '0.3';
						$calculation_equipment_activity4_3->activity_id = $activity4->id;
						$calculation_equipment_activity4_3->save();
						
						$calculation_equipment_activity4_4 = new CalculationEquipment;
						$calculation_equipment_activity4_4->equipment_name = 'C-A-H1W4-EQU4';
						$calculation_equipment_activity4_4->unit = '39';
						$calculation_equipment_activity4_4->rate = '8';
						$calculation_equipment_activity4_4->amount = '4';
						$calculation_equipment_activity4_4->isless = true;
						$calculation_equipment_activity4_4->less_rate = '8';
						$calculation_equipment_activity4_4->less_amount = '0.4';
						$calculation_equipment_activity4_4->activity_id = $activity4->id;
						$calculation_equipment_activity4_4->save();

						$calculation_equipment_activity4_5 = new CalculationEquipment;
						$calculation_equipment_activity4_5->equipment_name = 'C-A-H1W4-EQU5';
						$calculation_equipment_activity4_5->unit = '40';
						$calculation_equipment_activity4_5->rate = '8';
						$calculation_equipment_activity4_5->amount = '5';
						$calculation_equipment_activity4_5->isless = true;
						$calculation_equipment_activity4_5->less_rate = '8';
						$calculation_equipment_activity4_5->less_amount = '0.5';
						$calculation_equipment_activity4_5->activity_id = $activity4->id;
						$calculation_equipment_activity4_5->save();

		$chapter2 = new Chapter;
		$chapter2->chapter_name = 'C-O-H2';
		$chapter2->priority = 2;
		$chapter2->project_id = $project->id;
		$chapter2->save();

	       	$activity5 = new Activity;
	       	$activity5->activity_name = 'C-O-H2W5-21/21/21';
	       	$activity5->priority = 5;
	       	$activity5->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity5->chapter_id = $chapter2->id;
	       	$activity5->tax_labor_id = $tax1->id;
	       	$activity5->tax_material_id = $tax1->id;
	       	$activity5->tax_equipment_id = $tax1->id;
	       	$activity5->part_id = 2;
	       	$activity5->part_type_id = 1;
	       	$activity5->save();

				$calculation_labor_activity5 = new CalculationLabor;
				$calculation_labor_activity5->rate = '11.00';
				$calculation_labor_activity5->amount = '14';
				$calculation_labor_activity5->isless = true;
				$calculation_labor_activity5->less_amount = '4.0';
				$calculation_labor_activity5->activity_id = $activity5->id;
				$calculation_labor_activity5->save();

					$calculation_material_activity5_1 = new CalculationMaterial;
					$calculation_material_activity5_1->material_name = 'C-O-H2W5-MAT1';
					$calculation_material_activity5_1->unit = '41';
					$calculation_material_activity5_1->rate = '9';
					$calculation_material_activity5_1->amount = '1';
					$calculation_material_activity5_1->isless = true;
					$calculation_material_activity5_1->less_rate = '9';
					$calculation_material_activity5_1->less_amount = '0.1';
					$calculation_material_activity5_1->activity_id = $activity5->id;
					$calculation_material_activity5_1->save();

					$calculation_material_activity5_2 = new CalculationMaterial;
					$calculation_material_activity5_2->material_name = 'C-O-H2W5-MAT2';
					$calculation_material_activity5_2->unit = '42';
					$calculation_material_activity5_2->rate = '9';
					$calculation_material_activity5_2->amount = '2';
					$calculation_material_activity5_2->isless = true;
					$calculation_material_activity5_2->less_rate = '9';
					$calculation_material_activity5_2->less_amount = '0.2';
					$calculation_material_activity5_2->activity_id = $activity5->id;
					$calculation_material_activity5_2->save();

					$calculation_material_activity5_3 = new CalculationMaterial;
					$calculation_material_activity5_3->material_name = 'C-O-H2W5-MAT3';
					$calculation_material_activity5_3->unit = '43';
					$calculation_material_activity5_3->rate = '9';
					$calculation_material_activity5_3->amount = '3';
					$calculation_material_activity5_3->isless = true;
					$calculation_material_activity5_3->less_rate = '9';
					$calculation_material_activity5_3->less_amount = '0.3';
					$calculation_material_activity5_3->activity_id = $activity5->id;
					$calculation_material_activity5_3->save();

					$calculation_material_activity5_4 = new CalculationMaterial;
					$calculation_material_activity5_4->material_name = 'C-O-H2W5-MAT4';
					$calculation_material_activity5_4->unit = '44';
					$calculation_material_activity5_4->rate = '9';
					$calculation_material_activity5_4->amount = '4';
					$calculation_material_activity5_4->isless = true;
					$calculation_material_activity5_4->less_rate = '9';
					$calculation_material_activity5_4->less_amount = '0.4';
					$calculation_material_activity5_4->activity_id = $activity5->id;
					$calculation_material_activity5_4->save();

					$calculation_material_activity5_5 = new CalculationMaterial;
					$calculation_material_activity5_5->material_name = 'C-O-H2W5-MAT5';
					$calculation_material_activity5_5->unit = '45';
					$calculation_material_activity5_5->rate = '9';
					$calculation_material_activity5_5->amount = '5';
					$calculation_material_activity5_5->isless = true;
					$calculation_material_activity5_5->less_rate = '9';
					$calculation_material_activity5_5->less_amount = '0.5';
					$calculation_material_activity5_5->activity_id = $activity5->id;
					$calculation_material_activity5_5->save();
				
						$calculation_equipment_activity5_1 = new CalculationEquipment;
						$calculation_equipment_activity5_1->equipment_name = 'C-O-H2W5-EQU1';
						$calculation_equipment_activity5_1->unit = '46';
						$calculation_equipment_activity5_1->rate = '10';
						$calculation_equipment_activity5_1->amount = '1';
						$calculation_equipment_activity5_1->isless = true;
						$calculation_equipment_activity5_1->less_rate = '10';
						$calculation_equipment_activity5_1->less_amount = '0.1';
						$calculation_equipment_activity5_1->activity_id = $activity5->id;
						$calculation_equipment_activity5_1->save();
						
						$calculation_equipment_activity5_2 = new CalculationEquipment;
						$calculation_equipment_activity5_2->equipment_name = 'C-O-H2W5-EQU2';
						$calculation_equipment_activity5_2->unit = '47';
						$calculation_equipment_activity5_2->rate = '10';
						$calculation_equipment_activity5_2->amount = '2';
						$calculation_equipment_activity5_2->isless = true;
						$calculation_equipment_activity5_2->less_rate = '10';
						$calculation_equipment_activity5_2->less_amount = '0.2';
						$calculation_equipment_activity5_2->activity_id = $activity5->id;
						$calculation_equipment_activity5_2->save();

						$calculation_equipment_activity5_3 = new CalculationEquipment;
						$calculation_equipment_activity5_3->equipment_name = 'C-O-H2W5-EQU3';
						$calculation_equipment_activity5_3->unit = '48';
						$calculation_equipment_activity5_3->rate = '10';
						$calculation_equipment_activity5_3->amount = '3';
						$calculation_equipment_activity5_3->isless = true;
						$calculation_equipment_activity5_3->less_rate = '10';
						$calculation_equipment_activity5_3->less_amount = '0.3';
						$calculation_equipment_activity5_3->activity_id = $activity5->id;
						$calculation_equipment_activity5_3->save();
						
						$calculation_equipment_activity5_4 = new CalculationEquipment;
						$calculation_equipment_activity5_4->equipment_name = 'C-O-H2W5-EQU4';
						$calculation_equipment_activity5_4->unit = '49';
						$calculation_equipment_activity5_4->rate = '10';
						$calculation_equipment_activity5_4->amount = '4';
						$calculation_equipment_activity5_4->isless = true;
						$calculation_equipment_activity5_4->less_rate = '10';
						$calculation_equipment_activity5_4->less_amount = '0.4';
						$calculation_equipment_activity5_4->activity_id = $activity5->id;
						$calculation_equipment_activity5_4->save();

						$calculation_equipment_activity5_5 = new CalculationEquipment;
						$calculation_equipment_activity5_5->equipment_name = 'C-O-H2W5-EQU5';
						$calculation_equipment_activity5_5->unit = '50';
						$calculation_equipment_activity5_5->rate = '10';
						$calculation_equipment_activity5_5->amount = '5';
						$calculation_equipment_activity5_5->isless = true;
						$calculation_equipment_activity5_5->less_rate = '10';
						$calculation_equipment_activity5_5->less_amount = '0.5';
						$calculation_equipment_activity5_5->activity_id = $activity5->id;
						$calculation_equipment_activity5_5->save();

			$activity6 = new Activity;
	       	$activity6->activity_name = 'C-O-H2W6-21/21/6';
	       	$activity6->priority = 6;
	       	$activity6->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity6->chapter_id = $chapter2->id;
	       	$activity6->tax_labor_id = $tax1->id;
	       	$activity6->tax_material_id = $tax1->id;
	       	$activity6->tax_equipment_id = $tax2->id;
	       	$activity6->part_id = 2;
	       	$activity6->part_type_id = 1;
	       	$activity6->save();

				$calculation_labor_activity6 = new CalculationLabor;
				$calculation_labor_activity6->rate = '11.00';
				$calculation_labor_activity6->amount = '15';
				$calculation_labor_activity6->isless = true;
				$calculation_labor_activity6->less_amount = '5.0';
				$calculation_labor_activity6->activity_id = $activity6->id;
				$calculation_labor_activity6->save();

					$calculation_material_activity6_1 = new CalculationMaterial;
					$calculation_material_activity6_1->material_name = 'C-O-H2W6-MAT1';
					$calculation_material_activity6_1->unit = '51';
					$calculation_material_activity6_1->rate = '11';
					$calculation_material_activity6_1->amount = '1';
					$calculation_material_activity6_1->isless = true;
					$calculation_material_activity6_1->less_rate = '11';
					$calculation_material_activity6_1->less_amount= '0.1';
					$calculation_material_activity6_1->activity_id = $activity6->id;
					$calculation_material_activity6_1->save();

					$calculation_material_activity6_2 = new CalculationMaterial;
					$calculation_material_activity6_2->material_name = 'C-O-H2W6-MAT2';
					$calculation_material_activity6_2->unit = '52';
					$calculation_material_activity6_2->rate = '11';
					$calculation_material_activity6_2->amount = '2';
					$calculation_material_activity6_2->isless = true;
					$calculation_material_activity6_2->less_rate = '11';
					$calculation_material_activity6_2->less_amount= '0.2';
					$calculation_material_activity6_2->activity_id = $activity6->id;
					$calculation_material_activity6_2->save();

					$calculation_material_activity6_3 = new CalculationMaterial;
					$calculation_material_activity6_3->material_name = 'C-O-H2W6-MAT3';
					$calculation_material_activity6_3->unit = '53';
					$calculation_material_activity6_3->rate = '11';
					$calculation_material_activity6_3->amount = '3';
					$calculation_material_activity6_3->isless = true;
					$calculation_material_activity6_3->less_rate = '11';
					$calculation_material_activity6_3->less_amount= '0.3';
					$calculation_material_activity6_3->activity_id = $activity6->id;
					$calculation_material_activity6_3->save();

					$calculation_material_activity6_4 = new CalculationMaterial;
					$calculation_material_activity6_4->material_name = 'C-O-H2W6-MAT4';
					$calculation_material_activity6_4->unit = '54';
					$calculation_material_activity6_4->rate = '11';
					$calculation_material_activity6_4->amount = '4';
					$calculation_material_activity6_4->isless = true;
					$calculation_material_activity6_4->less_rate = '11';
					$calculation_material_activity6_4->less_amount= '0.4';
					$calculation_material_activity6_4->activity_id = $activity6->id;
					$calculation_material_activity6_4->save();

					$calculation_material_activity6_5 = new CalculationMaterial;
					$calculation_material_activity6_5->material_name = 'C-O-H2W6-MAT5';
					$calculation_material_activity6_5->unit = '55';
					$calculation_material_activity6_5->rate = '11';
					$calculation_material_activity6_5->amount = '5';
					$calculation_material_activity6_5->isless = true;
					$calculation_material_activity6_5->less_rate = '11';
					$calculation_material_activity6_5->less_amount= '0.5';
					$calculation_material_activity6_5->activity_id = $activity6->id;
					$calculation_material_activity6_5->save();
				
						$calculation_equipment_activity6_1 = new CalculationEquipment;
						$calculation_equipment_activity6_1->equipment_name = 'C-O-H2W6-EQU1';
						$calculation_equipment_activity6_1->unit = '56';
						$calculation_equipment_activity6_1->rate = '12';
						$calculation_equipment_activity6_1->amount = '1';
						$calculation_equipment_activity6_1->isless = true;
						$calculation_equipment_activity6_1->less_rate = '12';
						$calculation_equipment_activity6_1->less_amount= '0.1';
						$calculation_equipment_activity6_1->activity_id = $activity6->id;
						$calculation_equipment_activity6_1->save();
						
						$calculation_equipment_activity6_2 = new CalculationEquipment;
						$calculation_equipment_activity6_2->equipment_name = 'C-O-H2W6-EQU2';
						$calculation_equipment_activity6_2->unit = '57';
						$calculation_equipment_activity6_2->rate = '12';
						$calculation_equipment_activity6_2->amount = '2';
						$calculation_equipment_activity6_2->isless = true;
						$calculation_equipment_activity6_2->less_rate = '12';
						$calculation_equipment_activity6_2->less_amount= '0.2';
						$calculation_equipment_activity6_2->activity_id = $activity6->id;
						$calculation_equipment_activity6_2->save();

						$calculation_equipment_activity6_3 = new CalculationEquipment;
						$calculation_equipment_activity6_3->equipment_name = 'C-O-H2W6-EQU3';
						$calculation_equipment_activity6_3->unit = '58';
						$calculation_equipment_activity6_3->rate = '12';
						$calculation_equipment_activity6_3->amount = '3';
						$calculation_equipment_activity6_3->isless = true;
						$calculation_equipment_activity6_3->less_rate = '12';
						$calculation_equipment_activity6_3->less_amount= '0.3';
						$calculation_equipment_activity6_3->activity_id = $activity6->id;
						$calculation_equipment_activity6_3->save();
						
						$calculation_equipment_activity6_4 = new CalculationEquipment;
						$calculation_equipment_activity6_4->equipment_name = 'C-O-H2W6-EQU4';
						$calculation_equipment_activity6_4->unit = '59';
						$calculation_equipment_activity6_4->rate = '12';
						$calculation_equipment_activity6_4->amount = '4';
						$calculation_equipment_activity6_4->isless = true;
						$calculation_equipment_activity6_4->less_rate = '12';
						$calculation_equipment_activity6_4->less_amount= '0.4';
						$calculation_equipment_activity6_4->activity_id = $activity6->id;
						$calculation_equipment_activity6_4->save();

						$calculation_equipment_activity6_5 = new CalculationEquipment;
						$calculation_equipment_activity6_5->equipment_name = 'C-O-H2W6-EQU5';
						$calculation_equipment_activity6_5->unit = '60';
						$calculation_equipment_activity6_5->rate = '12';
						$calculation_equipment_activity6_5->amount = '5';
						$calculation_equipment_activity6_5->isless = true;
						$calculation_equipment_activity6_5->less_rate = '12';
						$calculation_equipment_activity6_5->less_amount= '0.5';
						$calculation_equipment_activity6_5->activity_id = $activity6->id;
						$calculation_equipment_activity6_5->save();

			$activity7 = new Activity;
	       	$activity7->activity_name = 'C-O-H2W7-21/6/6';
	       	$activity7->priority = 7;
	       	$activity7->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity7->chapter_id = $chapter2->id;
	       	$activity7->tax_labor_id = $tax1->id;
	       	$activity7->tax_material_id = $tax2->id;
	       	$activity7->tax_equipment_id = $tax2->id;
	       	$activity7->part_id = 2;
	       	$activity7->part_type_id = 1;
	       	$activity7->save();

				$calculation_labor_activity7 = new CalculationLabor;
				$calculation_labor_activity7->rate = '11.00';
				$calculation_labor_activity7->amount = '16';
				$calculation_labor_activity7->isless = true;
				$calculation_labor_activity7->less_amount = '6.0';
				$calculation_labor_activity7->activity_id = $activity7->id;
				$calculation_labor_activity7->save();

					$calculation_material_activity7_1 = new CalculationMaterial;
					$calculation_material_activity7_1->material_name = 'C-O-H2W7-MAT1';
					$calculation_material_activity7_1->unit = '61';
					$calculation_material_activity7_1->rate = '13';
					$calculation_material_activity7_1->amount = '1';
					$calculation_material_activity7_1->isless = true;
					$calculation_material_activity7_1->less_rate = '13';
					$calculation_material_activity7_1->less_amount= '0.1';
					$calculation_material_activity7_1->activity_id = $activity7->id;
					$calculation_material_activity7_1->save();

					$calculation_material_activity7_2 = new CalculationMaterial;
					$calculation_material_activity7_2->material_name = 'C-O-H2W7-MAT2';
					$calculation_material_activity7_2->unit = '62';
					$calculation_material_activity7_2->rate = '13';
					$calculation_material_activity7_2->amount = '2';
					$calculation_material_activity7_2->isless = true;
					$calculation_material_activity7_2->less_rate = '13';
					$calculation_material_activity7_2->less_amount= '0.2';
					$calculation_material_activity7_2->activity_id = $activity7->id;
					$calculation_material_activity7_2->save();

					$calculation_material_activity7_3 = new CalculationMaterial;
					$calculation_material_activity7_3->material_name = 'C-O-H2W7-MAT3';
					$calculation_material_activity7_3->unit = '63';
					$calculation_material_activity7_3->rate = '13';
					$calculation_material_activity7_3->amount = '3';
					$calculation_material_activity7_3->isless = true;
					$calculation_material_activity7_3->less_rate = '13';
					$calculation_material_activity7_3->less_amount= '0.3';
					$calculation_material_activity7_3->activity_id = $activity7->id;
					$calculation_material_activity7_3->save();

					$calculation_material_activity7_4 = new CalculationMaterial;
					$calculation_material_activity7_4->material_name = 'C-O-H2W7-MAT4';
					$calculation_material_activity7_4->unit = '64';
					$calculation_material_activity7_4->rate = '13';
					$calculation_material_activity7_4->amount = '4';
					$calculation_material_activity7_4->isless = true;
					$calculation_material_activity7_4->less_rate = '13';
					$calculation_material_activity7_4->less_amount= '0.4';
					$calculation_material_activity7_4->activity_id = $activity7->id;
					$calculation_material_activity7_4->save();

					$calculation_material_activity7_5 = new CalculationMaterial;
					$calculation_material_activity7_5->material_name = 'C-O-H2W7-MAT5';
					$calculation_material_activity7_5->unit = '65';
					$calculation_material_activity7_5->rate = '13';
					$calculation_material_activity7_5->amount = '5';
					$calculation_material_activity7_5->isless = true;
					$calculation_material_activity7_5->less_rate = '13';
					$calculation_material_activity7_5->less_amount= '0.5';
					$calculation_material_activity7_5->activity_id = $activity7->id;
					$calculation_material_activity7_5->save();
				
						$calculation_equipment_activity7_1 = new CalculationEquipment;
						$calculation_equipment_activity7_1->equipment_name = 'C-O-H2W7-EQU1';
						$calculation_equipment_activity7_1->unit = '66';
						$calculation_equipment_activity7_1->rate = '14';
						$calculation_equipment_activity7_1->amount = '1';
						$calculation_equipment_activity7_1->isless = true;
						$calculation_equipment_activity7_1->less_rate = '14';
						$calculation_equipment_activity7_1->less_amount = '0.1';
						$calculation_equipment_activity7_1->activity_id = $activity7->id;
						$calculation_equipment_activity7_1->save();
						
						$calculation_equipment_activity7_2 = new CalculationEquipment;
						$calculation_equipment_activity7_2->equipment_name = 'C-O-H2W7-EQU2';
						$calculation_equipment_activity7_2->unit = '67';
						$calculation_equipment_activity7_2->rate = '14';
						$calculation_equipment_activity7_2->amount = '2';
						$calculation_equipment_activity7_2->isless = true;
						$calculation_equipment_activity7_2->less_rate = '14';
						$calculation_equipment_activity7_2->less_amount = '0.2';
						$calculation_equipment_activity7_2->activity_id = $activity7->id;
						$calculation_equipment_activity7_2->save();

						$calculation_equipment_activity7_3 = new CalculationEquipment;
						$calculation_equipment_activity7_3->equipment_name = 'C-O-H2W7-EQU3';
						$calculation_equipment_activity7_3->unit = '68';
						$calculation_equipment_activity7_3->rate = '14';
						$calculation_equipment_activity7_3->amount = '3';
						$calculation_equipment_activity7_3->isless = true;
						$calculation_equipment_activity7_3->less_rate = '14';
						$calculation_equipment_activity7_3->less_amount = '0.3';
						$calculation_equipment_activity7_3->activity_id = $activity7->id;
						$calculation_equipment_activity7_3->save();
						
						$calculation_equipment_activity7_4 = new CalculationEquipment;
						$calculation_equipment_activity7_4->equipment_name = 'C-O-H2W7-EQU4';
						$calculation_equipment_activity7_4->unit = '69';
						$calculation_equipment_activity7_4->rate = '14';
						$calculation_equipment_activity7_4->amount = '4';
						$calculation_equipment_activity7_4->isless = true;
						$calculation_equipment_activity7_4->less_rate = '14';
						$calculation_equipment_activity7_4->less_amount = '0.4';
						$calculation_equipment_activity7_4->activity_id = $activity7->id;
						$calculation_equipment_activity7_4->save();

						$calculation_equipment_activity7_5 = new CalculationEquipment;
						$calculation_equipment_activity7_5->equipment_name = 'C-O-H2W7-EQU5';
						$calculation_equipment_activity7_5->unit = '70';
						$calculation_equipment_activity7_5->rate = '14';
						$calculation_equipment_activity7_5->amount = '5';
						$calculation_equipment_activity7_5->isless = true;
						$calculation_equipment_activity7_5->less_rate = '14';
						$calculation_equipment_activity7_5->less_amount = '0.5';
						$calculation_equipment_activity7_5->activity_id = $activity7->id;
						$calculation_equipment_activity7_5->save();

			$activity8= new Activity;
	       	$activity8->activity_name = 'C-O-H2W8-6/6/6';
	       	$activity8->priority = 8;
	       	$activity8->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity8->chapter_id = $chapter2->id;
	       	$activity8->tax_labor_id = $tax2->id;
	       	$activity8->tax_material_id = $tax2->id;
	       	$activity8->tax_equipment_id = $tax2->id;
	       	$activity8->part_id = 2;
	       	$activity8->part_type_id = 1;
	       	$activity8->save();

	  			$calculation_labor_activity8 = new CalculationLabor;
				$calculation_labor_activity8->rate = '11.00';
				$calculation_labor_activity8->amount = '17';
				$calculation_labor_activity8->isless = true;
				$calculation_labor_activity8->less_amount = '7.0';
				$calculation_labor_activity8->activity_id = $activity8->id;
				$calculation_labor_activity8->save();

					$calculation_material_activity8_1 = new CalculationMaterial;
					$calculation_material_activity8_1->material_name = 'C-O-H2W8-MAT1';
					$calculation_material_activity8_1->unit = '71';
					$calculation_material_activity8_1->rate = '15';
					$calculation_material_activity8_1->amount = '1';
					$calculation_material_activity8_1->isless = true;
					$calculation_material_activity8_1->less_rate = '15';
					$calculation_material_activity8_1->less_amount= '0.1';
					$calculation_material_activity8_1->activity_id = $activity8->id;
					$calculation_material_activity8_1->save();

					$calculation_material_activity8_2 = new CalculationMaterial;
					$calculation_material_activity8_2->material_name = 'C-O-H2W8-MAT2';
					$calculation_material_activity8_2->unit = '72';
					$calculation_material_activity8_2->rate = '15';
					$calculation_material_activity8_2->amount = '2';
					$calculation_material_activity8_2->isless = true;
					$calculation_material_activity8_2->less_rate = '15';
					$calculation_material_activity8_2->less_amount= '0.2';
					$calculation_material_activity8_2->activity_id = $activity8->id;
					$calculation_material_activity8_2->save();

					$calculation_material_activity8_3 = new CalculationMaterial;
					$calculation_material_activity8_3->material_name = 'C-O-H2W8-MAT3';
					$calculation_material_activity8_3->unit = '73';
					$calculation_material_activity8_3->rate = '15';
					$calculation_material_activity8_3->amount = '3';
					$calculation_material_activity8_3->isless = true;
					$calculation_material_activity8_3->less_rate = '15';
					$calculation_material_activity8_3->less_amount= '0.3';
					$calculation_material_activity8_3->activity_id = $activity8->id;
					$calculation_material_activity8_3->save();

					$calculation_material_activity8_4 = new CalculationMaterial;
					$calculation_material_activity8_4->material_name = 'C-O-H2W8-MAT4';
					$calculation_material_activity8_4->unit = '74';
					$calculation_material_activity8_4->rate = '15';
					$calculation_material_activity8_4->amount = '4';
					$calculation_material_activity8_4->isless = true;
					$calculation_material_activity8_4->less_rate = '15';
					$calculation_material_activity8_4->less_amount= '0.4';
					$calculation_material_activity8_4->activity_id = $activity8->id;
					$calculation_material_activity8_4->save();

					$calculation_material_activity8_5 = new CalculationMaterial;
					$calculation_material_activity8_5->material_name = 'C-O-H2W8-MAT5';
					$calculation_material_activity8_5->unit = '75';
					$calculation_material_activity8_5->rate = '15';
					$calculation_material_activity8_5->amount = '5';
					$calculation_material_activity8_5->isless = true;
					$calculation_material_activity8_5->less_rate = '15';
					$calculation_material_activity8_5->less_amount= '0.5';
					$calculation_material_activity8_5->activity_id = $activity8->id;
					$calculation_material_activity8_5->save();
				
						$calculation_equipment_activity8_1 = new CalculationEquipment;
						$calculation_equipment_activity8_1->equipment_name = 'C-O-H2W8-EQU1';
						$calculation_equipment_activity8_1->unit = '76';
						$calculation_equipment_activity8_1->rate = '16';
						$calculation_equipment_activity8_1->amount = '1';
						$calculation_equipment_activity8_1->isless = true;
						$calculation_equipment_activity8_1->less_rate = '16';
						$calculation_equipment_activity8_1->less_amount= '0.1';
						$calculation_equipment_activity8_1->activity_id = $activity8->id;
						$calculation_equipment_activity8_1->save();
						
						$calculation_equipment_activity8_2 = new CalculationEquipment;
						$calculation_equipment_activity8_2->equipment_name = 'C-O-H2W8-EQU2';
						$calculation_equipment_activity8_2->unit = '77';
						$calculation_equipment_activity8_2->rate = '16';
						$calculation_equipment_activity8_2->amount = '2';
						$calculation_equipment_activity8_2->isless = true;
						$calculation_equipment_activity8_2->less_rate = '16';
						$calculation_equipment_activity8_2->less_amount= '0.2';
						$calculation_equipment_activity8_2->activity_id = $activity8->id;
						$calculation_equipment_activity8_2->save();

						$calculation_equipment_activity8_3 = new CalculationEquipment;
						$calculation_equipment_activity8_3->equipment_name = 'C-O-H2W8-EQU3';
						$calculation_equipment_activity8_3->unit = '78';
						$calculation_equipment_activity8_3->rate = '16';
						$calculation_equipment_activity8_3->amount = '3';
						$calculation_equipment_activity8_3->isless = true;
						$calculation_equipment_activity8_3->less_rate = '16';
						$calculation_equipment_activity8_3->less_amount= '0.3';
						$calculation_equipment_activity8_3->activity_id = $activity8->id;
						$calculation_equipment_activity8_3->save();
						
						$calculation_equipment_activity8_4 = new CalculationEquipment;
						$calculation_equipment_activity8_4->equipment_name = 'C-O-H2W8-EQU4';
						$calculation_equipment_activity8_4->unit = '79';
						$calculation_equipment_activity8_4->rate = '16';
						$calculation_equipment_activity8_4->amount = '4';
						$calculation_equipment_activity8_4->isless = true;
						$calculation_equipment_activity8_4->less_rate = '16';
						$calculation_equipment_activity8_4->less_amount= '0.4';
						$calculation_equipment_activity8_4->activity_id = $activity8->id;
						$calculation_equipment_activity8_4->save();

						$calculation_equipment_activity8_5 = new CalculationEquipment;
						$calculation_equipment_activity8_5->equipment_name = 'C-O-H2W8-EQU5';
						$calculation_equipment_activity8_5->unit = '80';
						$calculation_equipment_activity8_5->rate = '16';
						$calculation_equipment_activity8_5->amount = '5';
						$calculation_equipment_activity8_5->isless = true;
						$calculation_equipment_activity8_5->less_rate = '16';
						$calculation_equipment_activity8_5->less_amount = '0.5';
						$calculation_equipment_activity8_5->activity_id = $activity8->id;
						$calculation_equipment_activity8_5->save();

		$chapter3 = new Chapter;
		$chapter3->chapter_name = 'S-A-H3';
		$chapter3->priority = 3;
		$chapter3->project_id = $project->id;
		$chapter3->save();

	       	$activity9 = new Activity;
	       	$activity9->activity_name = 'S-A-H3W9-21/21/21';
	       	$activity9->priority = 9;
	       	$activity9->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity9->chapter_id = $chapter3->id;
	       	$activity9->tax_labor_id = $tax1->id;
	       	$activity9->tax_material_id = $tax1->id;
	       	$activity9->tax_equipment_id = $tax1->id;
	       	$activity9->part_id = 1;
	       	$activity9->part_type_id = 2;
	       	$activity9->save();

				$Estimate_labor_activity9 = new EstimateLabor;
				$Estimate_labor_activity9->rate = '10.00';
				$Estimate_labor_activity9->amount = '18';
				$Estimate_labor_activity9->original = true;
				$Estimate_labor_activity9->isset = false;
				$Estimate_labor_activity9->activity_id = $activity9->id;
				$Estimate_labor_activity9->save();

					$Estimate_material_activity9_1 = new EstimateMaterial;
					$Estimate_material_activity9_1->material_name = 'S-A-H3W9-MAT1';
					$Estimate_material_activity9_1->unit = '81';
					$Estimate_material_activity9_1->rate = '1';
					$Estimate_material_activity9_1->amount = '1';
					$Estimate_material_activity9_1->original = true;
					$Estimate_material_activity9_1->isset = true;
					$Estimate_material_activity9_1->set_material_name = 'S-A-H3W9-MAT1-SET';
					$Estimate_material_activity9_1->set_unit = '81-SET';
					$Estimate_material_activity9_1->set_rate = '1';
					$Estimate_material_activity9_1->set_amount = '0.11';
					$Estimate_material_activity9_1->activity_id = $activity9->id;
					$Estimate_material_activity9_1->save();

					$Estimate_material_activity9_2 = new EstimateMaterial;
					$Estimate_material_activity9_2->material_name = 'S-A-H3W9-MAT2';
					$Estimate_material_activity9_2->unit = '82';
					$Estimate_material_activity9_2->rate = '1';
					$Estimate_material_activity9_2->amount = '2';
					$Estimate_material_activity9_2->original = true;
					$Estimate_material_activity9_2->isset = true;
					$Estimate_material_activity9_2->set_material_name = 'S-A-H3W9-MAT2-SET';
					$Estimate_material_activity9_2->set_unit = '82-SET';
					$Estimate_material_activity9_2->set_rate = '1';
					$Estimate_material_activity9_2->set_amount = '0.22';
					$Estimate_material_activity9_2->activity_id = $activity9->id;
					$Estimate_material_activity9_2->save();

					$Estimate_material_activity9_3 = new EstimateMaterial;
					$Estimate_material_activity9_3->material_name = 'S-A-H3W9-MAT3';
					$Estimate_material_activity9_3->unit = '83';
					$Estimate_material_activity9_3->rate = '1';
					$Estimate_material_activity9_3->amount = '3';
					$Estimate_material_activity9_3->original = true;
					$Estimate_material_activity9_3->isset = true;
					$Estimate_material_activity9_3->set_material_name = 'S-A-H3W9-MAT3-SET';
					$Estimate_material_activity9_3->set_unit = '83-SET';
					$Estimate_material_activity9_3->set_rate = '1';
					$Estimate_material_activity9_3->set_amount = '0.33';
					$Estimate_material_activity9_3->activity_id = $activity9->id;
					$Estimate_material_activity9_3->save();

					$Estimate_material_activity9_4 = new EstimateMaterial;
					$Estimate_material_activity9_4->material_name = 'S-A-H3W9-MAT4';
					$Estimate_material_activity9_4->unit = '84';
					$Estimate_material_activity9_4->rate = '1';
					$Estimate_material_activity9_4->amount = '4';
					$Estimate_material_activity9_4->original = true;
					$Estimate_material_activity9_4->isset = true;
					$Estimate_material_activity9_4->set_material_name = 'S-A-H3W9-MAT4-SET';
					$Estimate_material_activity9_4->set_unit = '84-SET';
					$Estimate_material_activity9_4->set_rate = '1';
					$Estimate_material_activity9_4->set_amount = '0.44';
					$Estimate_material_activity9_4->activity_id = $activity9->id;
					$Estimate_material_activity9_4->save();

					$Estimate_material_activity9_5 = new EstimateMaterial;
					$Estimate_material_activity9_5->material_name = 'S-A-H3W9-MAT5';
					$Estimate_material_activity9_5->unit = '85';
					$Estimate_material_activity9_5->rate = '1';
					$Estimate_material_activity9_5->amount = '5';
					$Estimate_material_activity9_5->original = true;
					$Estimate_material_activity9_5->isset = true;
					$Estimate_material_activity9_5->set_material_name = 'S-A-H3W9-MAT5-SET';
					$Estimate_material_activity9_5->set_unit = '85-SET';
					$Estimate_material_activity9_5->set_rate = '1';
					$Estimate_material_activity9_5->set_amount = '0.55';
					$Estimate_material_activity9_5->activity_id = $activity9->id;
					$Estimate_material_activity9_5->save();
				
						$Estimate_equipment_activity9_1 = new EstimateEquipment;
						$Estimate_equipment_activity9_1->equipment_name = 'S-A-H3W9-EQU1';
						$Estimate_equipment_activity9_1->unit = '86';
						$Estimate_equipment_activity9_1->rate = '1';
						$Estimate_equipment_activity9_1->amount = '2';
						$Estimate_equipment_activity9_1->original = true;
						$Estimate_equipment_activity9_1->isset = true;
						$Estimate_equipment_activity9_1->set_equipment_name = 'S-A-H3W9-EQU1-SET';
						$Estimate_equipment_activity9_1->set_unit = '86-SET';
						$Estimate_equipment_activity9_1->set_rate = '2';
						$Estimate_equipment_activity9_1->set_amount = '0.11';
						$Estimate_equipment_activity9_1->activity_id = $activity9->id;
						$Estimate_equipment_activity9_1->save();
						
						$Estimate_equipment_activity9_2 = new EstimateEquipment;
						$Estimate_equipment_activity9_2->equipment_name = 'S-A-H3W9-EQU2';
						$Estimate_equipment_activity9_2->unit = '87';
						$Estimate_equipment_activity9_2->rate = '2';
						$Estimate_equipment_activity9_2->amount = '2';
						$Estimate_equipment_activity9_2->original = true;
						$Estimate_equipment_activity9_2->isset = true;
						$Estimate_equipment_activity9_2->set_equipment_name = 'S-A-H3W9-EQU2-SET';
						$Estimate_equipment_activity9_2->set_unit = '87-SET';
						$Estimate_equipment_activity9_2->set_rate = '2';
						$Estimate_equipment_activity9_2->set_amount = '0.22';
						$Estimate_equipment_activity9_2->activity_id = $activity9->id;
						$Estimate_equipment_activity9_2->save();

						$Estimate_equipment_activity9_3 = new EstimateEquipment;
						$Estimate_equipment_activity9_3->equipment_name = 'S-A-H3W9-EQU3';
						$Estimate_equipment_activity9_3->unit = '88';
						$Estimate_equipment_activity9_3->rate = '2';
						$Estimate_equipment_activity9_3->amount = '3';
						$Estimate_equipment_activity9_3->original = true;
						$Estimate_equipment_activity9_3->isset = true;
						$Estimate_equipment_activity9_3->set_equipment_name = 'S-A-H3W9-EQU3-SET';
						$Estimate_equipment_activity9_3->set_unit = '88-SET';
						$Estimate_equipment_activity9_3->set_rate = '2';
						$Estimate_equipment_activity9_3->set_amount = '0.33';
						$Estimate_equipment_activity9_3->activity_id = $activity9->id;
						$Estimate_equipment_activity9_3->save();
						
						$Estimate_equipment_activity9_4 = new EstimateEquipment;
						$Estimate_equipment_activity9_4->equipment_name = 'S-A-H3W9-EQU4';
						$Estimate_equipment_activity9_4->unit = '89';
						$Estimate_equipment_activity9_4->rate = '2';
						$Estimate_equipment_activity9_4->amount = '4';
						$Estimate_equipment_activity9_4->original = true;
						$Estimate_equipment_activity9_4->isset = true;
						$Estimate_equipment_activity9_4->set_equipment_name = 'S-A-H3W9-EQU4-SET';
						$Estimate_equipment_activity9_4->set_unit = '89-SET';
						$Estimate_equipment_activity9_4->set_rate = '2';
						$Estimate_equipment_activity9_4->set_amount = '0.44';
						$Estimate_equipment_activity9_4->activity_id = $activity9->id;
						$Estimate_equipment_activity9_4->save();

						$Estimate_equipment_activity9_5 = new EstimateEquipment;
						$Estimate_equipment_activity9_5->equipment_name = 'S-A-H3W9-EQU5';
						$Estimate_equipment_activity9_5->unit = '90';
						$Estimate_equipment_activity9_5->rate = '2';
						$Estimate_equipment_activity9_5->amount = '5';
						$Estimate_equipment_activity9_5->original = true;
						$Estimate_equipment_activity9_5->isset = true;
						$Estimate_equipment_activity9_5->set_equipment_name = 'S-A-H3W9-EQU5-SET';
						$Estimate_equipment_activity9_5->set_unit = '90-SET';
						$Estimate_equipment_activity9_5->set_rate = '2';
						$Estimate_equipment_activity9_5->set_amount = '0.55';
						$Estimate_equipment_activity9_5->activity_id = $activity9->id;
						$Estimate_equipment_activity9_5->save();

			$activity10 = new Activity;
	       	$activity10->activity_name = 'S-A-H3W10-21/21/6';
	       	$activity10->priority = 10;
	       	$activity10->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity10->chapter_id = $chapter3->id;
	       	$activity10->tax_labor_id = $tax1->id;
	       	$activity10->tax_material_id = $tax1->id;
	       	$activity10->tax_equipment_id = $tax2->id;
	       	$activity10->part_id = 1;
	       	$activity10->part_type_id = 2;
	       	$activity10->save();

				$Estimate_labor_activity10 = new EstimateLabor;
				$Estimate_labor_activity10->rate = '10.00';
				$Estimate_labor_activity10->amount = '19';
				$Estimate_labor_activity10->original = true;
				$Estimate_labor_activity10->isset = false;
				$Estimate_labor_activity10->activity_id = $activity10->id;
				$Estimate_labor_activity10->save();

					$Estimate_material_activity10_1 = new EstimateMaterial;
					$Estimate_material_activity10_1->material_name = 'S-A-H3W10-MAT1';
					$Estimate_material_activity10_1->unit = '91';
					$Estimate_material_activity10_1->rate = '3';
					$Estimate_material_activity10_1->amount = '1';
					$Estimate_material_activity10_1->original = true;
					$Estimate_material_activity10_1->isset = true;
					$Estimate_material_activity10_1->set_material_name = 'S-A-H3W10-MAT1-SET';
					$Estimate_material_activity10_1->set_unit = '91-SET';
					$Estimate_material_activity10_1->set_rate = '3';
					$Estimate_material_activity10_1->set_amount = '0.11';
					$Estimate_material_activity10_1->activity_id = $activity10->id;
					$Estimate_material_activity10_1->save();

					$Estimate_material_activity10_2 = new EstimateMaterial;
					$Estimate_material_activity10_2->material_name = 'S-A-H3W10-MAT2';
					$Estimate_material_activity10_2->unit = '92';
					$Estimate_material_activity10_2->rate = '3';
					$Estimate_material_activity10_2->amount = '2';
					$Estimate_material_activity10_2->original = true;
					$Estimate_material_activity10_2->isset = true;
					$Estimate_material_activity10_2->set_material_name = 'S-A-H3W10-MAT2-SET';
					$Estimate_material_activity10_2->set_unit = '92-SET';
					$Estimate_material_activity10_2->set_rate = '3';
					$Estimate_material_activity10_2->set_amount = '0.22';
					$Estimate_material_activity10_2->activity_id = $activity10->id;
					$Estimate_material_activity10_2->save();

					$Estimate_material_activity10_3 = new EstimateMaterial;
					$Estimate_material_activity10_3->material_name = 'S-A-H3W10-MAT3';
					$Estimate_material_activity10_3->unit = '93';
					$Estimate_material_activity10_3->rate = '3';
					$Estimate_material_activity10_3->amount = '3';
					$Estimate_material_activity10_3->original = true;
					$Estimate_material_activity10_3->isset = true;
					$Estimate_material_activity10_3->set_material_name = 'S-A-H3W10-MAT3-SET';
					$Estimate_material_activity10_3->set_unit = '93-SET';
					$Estimate_material_activity10_3->set_rate = '3';
					$Estimate_material_activity10_3->set_amount = '0.33';
					$Estimate_material_activity10_3->activity_id = $activity10->id;
					$Estimate_material_activity10_3->save();

					$Estimate_material_activity10_4 = new EstimateMaterial;
					$Estimate_material_activity10_4->material_name = 'S-A-H3W10-MAT4';
					$Estimate_material_activity10_4->unit = '94';
					$Estimate_material_activity10_4->rate = '3';
					$Estimate_material_activity10_4->amount = '4';
					$Estimate_material_activity10_4->original = true;
					$Estimate_material_activity10_4->isset = true;
					$Estimate_material_activity10_4->set_material_name = 'S-A-H3W10-MAT4-SET';
					$Estimate_material_activity10_4->set_unit = '94-SET';
					$Estimate_material_activity10_4->set_rate = '3';
					$Estimate_material_activity10_4->set_amount = '0.44';
					$Estimate_material_activity10_4->activity_id = $activity10->id;
					$Estimate_material_activity10_4->save();

					$Estimate_material_activity10_5 = new EstimateMaterial;
					$Estimate_material_activity10_5->material_name = 'S-A-H3W10-MAT5';
					$Estimate_material_activity10_5->unit = '95';
					$Estimate_material_activity10_5->rate = '3';
					$Estimate_material_activity10_5->amount = '5';
					$Estimate_material_activity10_5->original = true;
					$Estimate_material_activity10_5->isset = true;
					$Estimate_material_activity10_5->set_material_name = 'S-A-H3W10-MAT5-SET';
					$Estimate_material_activity10_5->set_unit = '95-SET';
					$Estimate_material_activity10_5->set_rate = '3';
					$Estimate_material_activity10_5->set_amount = '0.55';
					$Estimate_material_activity10_5->activity_id = $activity10->id;
					$Estimate_material_activity10_5->save();
				
						$Estimate_equipment_activity10_1 = new EstimateEquipment;
						$Estimate_equipment_activity10_1->equipment_name = 'S-A-H3W10-EQU1';
						$Estimate_equipment_activity10_1->unit = '96';
						$Estimate_equipment_activity10_1->rate = '4';
						$Estimate_equipment_activity10_1->amount = '1';
						$Estimate_equipment_activity10_1->original = true;
						$Estimate_equipment_activity10_1->isset = true;
						$Estimate_equipment_activity10_1->set_equipment_name = 'S-A-H3W10-EQU1-SET';
						$Estimate_equipment_activity10_1->set_unit = '96-SET';
						$Estimate_equipment_activity10_1->set_rate = '4';
						$Estimate_equipment_activity10_1->set_amount = '0.11';
						$Estimate_equipment_activity10_1->activity_id = $activity10->id;
						$Estimate_equipment_activity10_1->save();
						
						$Estimate_equipment_activity10_2 = new EstimateEquipment;
						$Estimate_equipment_activity10_2->equipment_name = 'S-A-H3W10-EQU2';
						$Estimate_equipment_activity10_2->unit = '97';
						$Estimate_equipment_activity10_2->rate = '4';
						$Estimate_equipment_activity10_2->amount = '2';
						$Estimate_equipment_activity10_2->original = true;
						$Estimate_equipment_activity10_2->isset = true;
						$Estimate_equipment_activity10_2->set_equipment_name = 'S-A-H3W10-EQU2-SET';
						$Estimate_equipment_activity10_2->set_unit = '97-SET';
						$Estimate_equipment_activity10_2->set_rate = '4';
						$Estimate_equipment_activity10_2->set_amount = '0.22';
						$Estimate_equipment_activity10_2->activity_id = $activity10->id;
						$Estimate_equipment_activity10_2->save();

						$Estimate_equipment_activity10_3 = new EstimateEquipment;
						$Estimate_equipment_activity10_3->equipment_name = 'S-A-H3W10-EQU3';
						$Estimate_equipment_activity10_3->unit = '98';
						$Estimate_equipment_activity10_3->rate = '4';
						$Estimate_equipment_activity10_3->amount = '3';
						$Estimate_equipment_activity10_3->original = true;
						$Estimate_equipment_activity10_3->isset = true;
						$Estimate_equipment_activity10_3->set_equipment_name = 'S-A-H3W10-EQU3-SET';
						$Estimate_equipment_activity10_3->set_unit = '98-SET';
						$Estimate_equipment_activity10_3->set_rate = '4';
						$Estimate_equipment_activity10_3->set_amount = '0.33';
						$Estimate_equipment_activity10_3->activity_id = $activity10->id;
						$Estimate_equipment_activity10_3->save();
						
						$Estimate_equipment_activity10_4 = new EstimateEquipment;
						$Estimate_equipment_activity10_4->equipment_name = 'S-A-H3W10-EQU4';
						$Estimate_equipment_activity10_4->unit = '99';
						$Estimate_equipment_activity10_4->rate = '4';
						$Estimate_equipment_activity10_4->amount = '4';
						$Estimate_equipment_activity10_4->original = true;
						$Estimate_equipment_activity10_4->isset = true;
						$Estimate_equipment_activity10_4->set_equipment_name = 'S-A-H3W10-EQU4-SET';
						$Estimate_equipment_activity10_4->set_unit = '99-SET';
						$Estimate_equipment_activity10_4->set_rate = '4';
						$Estimate_equipment_activity10_4->set_amount = '0.44';
						$Estimate_equipment_activity10_4->activity_id = $activity10->id;
						$Estimate_equipment_activity10_4->save();

						$Estimate_equipment_activity10_5 = new EstimateEquipment;
						$Estimate_equipment_activity10_5->equipment_name = 'S-A-H3W10-EQU5';
						$Estimate_equipment_activity10_5->unit = '100';
						$Estimate_equipment_activity10_5->rate = '4';
						$Estimate_equipment_activity10_5->amount = '5';
						$Estimate_equipment_activity10_5->original = true;
						$Estimate_equipment_activity10_5->isset = true;
						$Estimate_equipment_activity10_5->set_equipment_name = 'S-A-H3W10-EQU5-SET';
						$Estimate_equipment_activity10_5->set_unit = '100-SET';
						$Estimate_equipment_activity10_5->set_rate = '4';
						$Estimate_equipment_activity10_5->set_amount = '0.55';
						$Estimate_equipment_activity10_5->activity_id = $activity10->id;
						$Estimate_equipment_activity10_5->save();

			$activity11 = new Activity;
	       	$activity11->activity_name = 'S-A-H3W11-21/6/6';
	       	$activity11->priority = 11;
	       	$activity11->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity11->chapter_id = $chapter3->id;
	       	$activity11->tax_labor_id = $tax1->id;
	       	$activity11->tax_material_id = $tax2->id;
	       	$activity11->tax_equipment_id = $tax2->id;
	       	$activity11->part_id = 1;
	       	$activity11->part_type_id = 2;
	       	$activity11->save();

				$Estimate_labor_activity11 = new EstimateLabor;
				$Estimate_labor_activity11->rate = '10.00';
				$Estimate_labor_activity11->amount = '20';
				$Estimate_labor_activity11->set_rate = '10.00';
				$Estimate_labor_activity11->set_amount = '0';
				$Estimate_labor_activity11->original = true;
				$Estimate_labor_activity11->isset = true;
				$Estimate_labor_activity11->activity_id = $activity11->id;
				$Estimate_labor_activity11->save();

					$Estimate_material_activity11_1 = new EstimateMaterial;
					$Estimate_material_activity11_1->material_name = 'S-A-H3W11-MAT1';
					$Estimate_material_activity11_1->unit = '101';
					$Estimate_material_activity11_1->rate = '5';
					$Estimate_material_activity11_1->amount = '1';
					$Estimate_material_activity11_1->original = true;
					$Estimate_material_activity11_1->isset = true;
					$Estimate_material_activity11_1->set_material_name = 'S-A-H3W11-MAT1-SET';
					$Estimate_material_activity11_1->set_unit = '101-SET';
					$Estimate_material_activity11_1->set_rate = '5';
					$Estimate_material_activity11_1->set_amount = '0.11';
					$Estimate_material_activity11_1->activity_id = $activity11->id;
					$Estimate_material_activity11_1->save();

					$Estimate_material_activity11_2 = new EstimateMaterial;
					$Estimate_material_activity11_2->material_name = 'S-A-H3W11-MAT2';
					$Estimate_material_activity11_2->unit = '102';
					$Estimate_material_activity11_2->rate = '5';
					$Estimate_material_activity11_2->amount = '2';
					$Estimate_material_activity11_2->original = true;
					$Estimate_material_activity11_2->isset = true;
					$Estimate_material_activity11_2->set_material_name = 'S-A-H3W11-MAT2-SET';
					$Estimate_material_activity11_2->set_unit = '102-SET';
					$Estimate_material_activity11_2->set_rate = '5';
					$Estimate_material_activity11_2->set_amount = '0.22';
					$Estimate_material_activity11_2->activity_id = $activity11->id;
					$Estimate_material_activity11_2->save();

					$Estimate_material_activity11_3 = new EstimateMaterial;
					$Estimate_material_activity11_3->material_name = 'S-A-H3W11-AT3';
					$Estimate_material_activity11_3->unit = '103';
					$Estimate_material_activity11_3->rate = '5';
					$Estimate_material_activity11_3->amount = '3';
					$Estimate_material_activity11_3->original = true;
					$Estimate_material_activity11_3->isset = true;
					$Estimate_material_activity11_3->set_material_name = 'S-A-H3W11-MAT3-SET';
					$Estimate_material_activity11_3->set_unit = '103-SET';
					$Estimate_material_activity11_3->set_rate = '5';
					$Estimate_material_activity11_3->set_amount = '0.33';
					$Estimate_material_activity11_3->activity_id = $activity11->id;
					$Estimate_material_activity11_3->save();

					$Estimate_material_activity11_4 = new EstimateMaterial;
					$Estimate_material_activity11_4->material_name = 'S-A-H3W11-MAT4';
					$Estimate_material_activity11_4->unit = '104';
					$Estimate_material_activity11_4->rate = '5';
					$Estimate_material_activity11_4->amount = '4';
					$Estimate_material_activity11_4->original = true;
					$Estimate_material_activity11_4->isset = true;
					$Estimate_material_activity11_4->set_material_name = 'S-A-H3W11-MAT4-SET';
					$Estimate_material_activity11_4->set_unit = '104-SET';
					$Estimate_material_activity11_4->set_rate = '5';
					$Estimate_material_activity11_4->set_amount = '0.44';
					$Estimate_material_activity11_4->activity_id = $activity11->id;
					$Estimate_material_activity11_4->save();

					$Estimate_material_activity11_5 = new EstimateMaterial;
					$Estimate_material_activity11_5->material_name = 'S-A-H3W11-MAT5';
					$Estimate_material_activity11_5->unit = '105';
					$Estimate_material_activity11_5->rate = '5';
					$Estimate_material_activity11_5->amount = '5';
					$Estimate_material_activity11_5->original = true;
					$Estimate_material_activity11_5->isset = true;
					$Estimate_material_activity11_5->set_material_name = 'S-A-H3W11-MAT5-SET';
					$Estimate_material_activity11_5->set_unit = '105-SET';
					$Estimate_material_activity11_5->set_rate = '5';
					$Estimate_material_activity11_5->set_amount = '0.55';
					$Estimate_material_activity11_5->activity_id = $activity11->id;
					$Estimate_material_activity11_5->save();
				
						$Estimate_equipment_activity11_1 = new EstimateEquipment;
						$Estimate_equipment_activity11_1->equipment_name = 'S-A-H3W11-EQU1';
						$Estimate_equipment_activity11_1->unit = '106';
						$Estimate_equipment_activity11_1->rate = '6';
						$Estimate_equipment_activity11_1->amount = '1';
						$Estimate_equipment_activity11_1->original = true;
						$Estimate_equipment_activity11_1->isset = true;
						$Estimate_equipment_activity11_1->set_equipment_name = 'S-A-H3W11-EQU1-SET';
						$Estimate_equipment_activity11_1->set_unit = '106-SET';
						$Estimate_equipment_activity11_1->set_rate = '6';
						$Estimate_equipment_activity11_1->set_amount = '0.11';
						$Estimate_equipment_activity11_1->activity_id = $activity11->id;
						$Estimate_equipment_activity11_1->save();
						
						$Estimate_equipment_activity11_2 = new EstimateEquipment;
						$Estimate_equipment_activity11_2->equipment_name = 'S-A-H3W11-EQU2';
						$Estimate_equipment_activity11_2->unit = '107';
						$Estimate_equipment_activity11_2->rate = '6';
						$Estimate_equipment_activity11_2->amount = '2';
						$Estimate_equipment_activity11_2->original = true;
						$Estimate_equipment_activity11_2->isset = true;
						$Estimate_equipment_activity11_2->set_equipment_name = 'S-A-H3W11-EQU2-SET';
						$Estimate_equipment_activity11_2->set_unit = '107-SET';
						$Estimate_equipment_activity11_2->set_rate = '6';
						$Estimate_equipment_activity11_2->set_amount = '0.22';
						$Estimate_equipment_activity11_2->activity_id = $activity11->id;
						$Estimate_equipment_activity11_2->save();

						$Estimate_equipment_activity11_3 = new EstimateEquipment;
						$Estimate_equipment_activity11_3->equipment_name = 'S-A-H3W11-EQU3';
						$Estimate_equipment_activity11_3->unit = '108';
						$Estimate_equipment_activity11_3->rate = '6';
						$Estimate_equipment_activity11_3->amount = '3';
						$Estimate_equipment_activity11_3->original = true;
						$Estimate_equipment_activity11_3->isset = true;
						$Estimate_equipment_activity11_3->set_equipment_name = 'S-A-H3W11-EQU3-SET';
						$Estimate_equipment_activity11_3->set_unit = '108-SET';
						$Estimate_equipment_activity11_3->set_rate = '6';
						$Estimate_equipment_activity11_3->set_amount = '0.33';
						$Estimate_equipment_activity11_3->activity_id = $activity11->id;
						$Estimate_equipment_activity11_3->save();
						
						$Estimate_equipment_activity11_4 = new EstimateEquipment;
						$Estimate_equipment_activity11_4->equipment_name = 'S-A-H3W11-EQU4';
						$Estimate_equipment_activity11_4->unit = '109';
						$Estimate_equipment_activity11_4->rate = '6';
						$Estimate_equipment_activity11_4->amount = '4';
						$Estimate_equipment_activity11_4->original = true;
						$Estimate_equipment_activity11_4->isset = true;
						$Estimate_equipment_activity11_4->set_equipment_name = 'S-A-H3W11-EQU4-SET';
						$Estimate_equipment_activity11_4->set_unit = '109-SET';
						$Estimate_equipment_activity11_4->set_rate = '6';
						$Estimate_equipment_activity11_4->set_amount = '0.44';
						$Estimate_equipment_activity11_4->activity_id = $activity11->id;
						$Estimate_equipment_activity11_4->save();

						$Estimate_equipment_activity11_5 = new EstimateEquipment;
						$Estimate_equipment_activity11_5->equipment_name = 'S-A-H3W11-EQU5';
						$Estimate_equipment_activity11_5->unit = '110';
						$Estimate_equipment_activity11_5->rate = '6';
						$Estimate_equipment_activity11_5->amount = '5';
						$Estimate_equipment_activity11_5->original = true;
						$Estimate_equipment_activity11_5->isset = true;
						$Estimate_equipment_activity11_5->set_equipment_name = 'S-A-H3W11-EQU5-SET';
						$Estimate_equipment_activity11_5->set_unit = '110-SET';
						$Estimate_equipment_activity11_5->set_rate = '6';
						$Estimate_equipment_activity11_5->set_amount = '0.55';
						$Estimate_equipment_activity11_5->activity_id = $activity11->id;
						$Estimate_equipment_activity11_5->save();

			$activity12 = new Activity;
	       	$activity12->activity_name = 'S-A-H3W12-6/6/6';
	       	$activity12->priority = 12;
	       	$activity12->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity12->chapter_id = $chapter3->id;
	       	$activity12->tax_labor_id = $tax2->id;
	       	$activity12->tax_material_id = $tax2->id;
	       	$activity12->tax_equipment_id = $tax2->id;
	       	$activity12->part_id = 1;
	       	$activity12->part_type_id = 2;
	       	$activity12->save();

				$Estimate_labor_activity12 = new EstimateLabor;
				$Estimate_labor_activity12->rate = '10.00';
				$Estimate_labor_activity12->amount = '21';
				$Estimate_labor_activity12->set_rate = '10.00';
				$Estimate_labor_activity12->set_amount = '0';
				$Estimate_labor_activity12->original = true;
				$Estimate_labor_activity12->isset = true;
				$Estimate_labor_activity12->activity_id = $activity12->id;
				$Estimate_labor_activity12->save();

					$Estimate_material_activity12_1 = new EstimateMaterial;
					$Estimate_material_activity12_1->material_name = 'S-A-H3W12-MAT1';
					$Estimate_material_activity12_1->unit = '111';
					$Estimate_material_activity12_1->rate = '7';
					$Estimate_material_activity12_1->amount = '1';
					$Estimate_material_activity12_1->original = true;
					$Estimate_material_activity12_1->isset = true;
					$Estimate_material_activity12_1->set_material_name = 'S-A-H3W12-MAT1-SET';
					$Estimate_material_activity12_1->set_unit = '111-SET';
					$Estimate_material_activity12_1->set_rate = '7';
					$Estimate_material_activity12_1->set_amount = '0.11';
					$Estimate_material_activity12_1->activity_id = $activity12->id;
					$Estimate_material_activity12_1->save();

					$Estimate_material_activity12_2 = new EstimateMaterial;
					$Estimate_material_activity12_2->material_name = 'S-A-H3W12-MAT2';
					$Estimate_material_activity12_2->unit = '112';
					$Estimate_material_activity12_2->rate = '7';
					$Estimate_material_activity12_2->amount = '2';
					$Estimate_material_activity12_2->original = true;
					$Estimate_material_activity12_2->isset = true;
					$Estimate_material_activity12_2->set_material_name = 'S-A-H3W12-MAT2-SET';
					$Estimate_material_activity12_2->set_unit = '112-SET';
					$Estimate_material_activity12_2->set_rate = '7';
					$Estimate_material_activity12_2->set_amount = '0.22';
					$Estimate_material_activity12_2->activity_id = $activity12->id;
					$Estimate_material_activity12_2->save();

					$Estimate_material_activity12_3 = new EstimateMaterial;
					$Estimate_material_activity12_3->material_name = 'S-A-H3W12-MAT3';
					$Estimate_material_activity12_3->unit = '113';
					$Estimate_material_activity12_3->rate = '7';
					$Estimate_material_activity12_3->amount = '3';
					$Estimate_material_activity12_3->original = true;
					$Estimate_material_activity12_3->isset = true;
					$Estimate_material_activity12_3->set_material_name = 'S-A-H3W12-MAT3-SET';
					$Estimate_material_activity12_3->set_unit = '113-SET';
					$Estimate_material_activity12_3->set_rate = '7';
					$Estimate_material_activity12_3->set_amount = '0.33';
					$Estimate_material_activity12_3->activity_id = $activity12->id;
					$Estimate_material_activity12_3->save();

					$Estimate_material_activity12_4 = new EstimateMaterial;
					$Estimate_material_activity12_4->material_name = 'S-A-H3W12-MAT4';
					$Estimate_material_activity12_4->unit = '114';
					$Estimate_material_activity12_4->rate = '7';
					$Estimate_material_activity12_4->amount = '4';
					$Estimate_material_activity12_4->original = true;
					$Estimate_material_activity12_4->isset = true;
					$Estimate_material_activity12_4->set_material_name = 'S-A-H3W12-MAT4-SET';
					$Estimate_material_activity12_4->set_unit = '114-SET';
					$Estimate_material_activity12_4->set_rate = '7';
					$Estimate_material_activity12_4->set_amount = '0.44';
					$Estimate_material_activity12_4->activity_id = $activity12->id;
					$Estimate_material_activity12_4->save();

					$Estimate_material_activity12_5 = new EstimateMaterial;
					$Estimate_material_activity12_5->material_name = 'S-A-H3W12-MAT5';
					$Estimate_material_activity12_5->unit = '115';
					$Estimate_material_activity12_5->rate = '7';
					$Estimate_material_activity12_5->amount = '5';
					$Estimate_material_activity12_5->original = true;
					$Estimate_material_activity12_5->isset = true;
					$Estimate_material_activity12_5->set_material_name = 'S-A-H3W12-MAT5-SET';
					$Estimate_material_activity12_5->set_unit = '115-SET';
					$Estimate_material_activity12_5->set_rate = '7';
					$Estimate_material_activity12_5->set_amount = '0.55';
					$Estimate_material_activity12_5->activity_id = $activity12->id;
					$Estimate_material_activity12_5->save();
				
						$Estimate_equipment_activity12_1 = new EstimateEquipment;
						$Estimate_equipment_activity12_1->equipment_name = 'S-A-H3W12-EQU1';
						$Estimate_equipment_activity12_1->unit = '116';
						$Estimate_equipment_activity12_1->rate = '8';
						$Estimate_equipment_activity12_1->amount = '1';
						$Estimate_equipment_activity12_1->original = true;
						$Estimate_equipment_activity12_1->isset = true;
						$Estimate_equipment_activity12_1->set_equipment_name = 'S-A-H3W12-EQU1-SET';
						$Estimate_equipment_activity12_1->set_unit = '116-SET';
						$Estimate_equipment_activity12_1->set_rate = '8';
						$Estimate_equipment_activity12_1->set_amount = '0.11';
						$Estimate_equipment_activity12_1->activity_id = $activity12->id;
						$Estimate_equipment_activity12_1->save();
						
						$Estimate_equipment_activity12_2 = new EstimateEquipment;
						$Estimate_equipment_activity12_2->equipment_name = 'S-A-H3W12-EQU2';
						$Estimate_equipment_activity12_2->unit = '117';
						$Estimate_equipment_activity12_2->rate = '8';
						$Estimate_equipment_activity12_2->amount = '2';
						$Estimate_equipment_activity12_2->original = true;
						$Estimate_equipment_activity12_2->isset = true;
						$Estimate_equipment_activity12_2->set_equipment_name = 'S-A-H3W12-EQU2-SET';
						$Estimate_equipment_activity12_2->set_unit = '117-SET';
						$Estimate_equipment_activity12_2->set_rate = '8';
						$Estimate_equipment_activity12_2->set_amount = '0.22';
						$Estimate_equipment_activity12_2->activity_id = $activity12->id;
						$Estimate_equipment_activity12_2->save();

						$Estimate_equipment_activity12_3 = new EstimateEquipment;
						$Estimate_equipment_activity12_3->equipment_name = 'S-A-H3W12-EQU3';
						$Estimate_equipment_activity12_3->unit = '118';
						$Estimate_equipment_activity12_3->rate = '8';
						$Estimate_equipment_activity12_3->amount = '3';
						$Estimate_equipment_activity12_3->original = true;
						$Estimate_equipment_activity12_3->isset = true;
						$Estimate_equipment_activity12_3->set_equipment_name = 'S-A-H3W12-EQU3-SET';
						$Estimate_equipment_activity12_3->set_unit = '118-SET';
						$Estimate_equipment_activity12_3->set_rate = '8';
						$Estimate_equipment_activity12_3->set_amount = '0.33';
						$Estimate_equipment_activity12_3->activity_id = $activity12->id;
						$Estimate_equipment_activity12_3->save();
						
						$Estimate_equipment_activity12_4 = new EstimateEquipment;
						$Estimate_equipment_activity12_4->equipment_name = 'S-A-H3W12-EQU4';
						$Estimate_equipment_activity12_4->unit = '119';
						$Estimate_equipment_activity12_4->rate = '8';
						$Estimate_equipment_activity12_4->amount = '4';
						$Estimate_equipment_activity12_4->original = true;
						$Estimate_equipment_activity12_4->isset = true;
						$Estimate_equipment_activity12_4->set_equipment_name = 'S-A-H3W12-EQU4-SET';
						$Estimate_equipment_activity12_4->set_unit = '119-SET';
						$Estimate_equipment_activity12_4->set_rate = '8';
						$Estimate_equipment_activity12_4->set_amount = '0.44';
						$Estimate_equipment_activity12_4->activity_id = $activity12->id;
						$Estimate_equipment_activity12_4->save();

						$Estimate_equipment_activity12_5 = new EstimateEquipment;
						$Estimate_equipment_activity12_5->equipment_name = 'S-A-H3W12-EQU5';
						$Estimate_equipment_activity12_5->unit = '120';
						$Estimate_equipment_activity12_5->rate = '8';
						$Estimate_equipment_activity12_5->amount = '5';
						$Estimate_equipment_activity12_5->original = true;
						$Estimate_equipment_activity12_5->isset = true;
						$Estimate_equipment_activity12_5->set_equipment_name = 'S-A-H3W12-EQU5-SET';
						$Estimate_equipment_activity12_5->set_unit = '120-SET';
						$Estimate_equipment_activity12_5->set_rate = '8';
						$Estimate_equipment_activity12_5->set_amount = '0.55';
						$Estimate_equipment_activity12_5->activity_id = $activity12->id;
						$Estimate_equipment_activity12_5->save();

		$chapter4 = new Chapter;
		$chapter4->chapter_name = 'S-O-H4';
		$chapter4->priority = 4;
		$chapter4->project_id = $project->id;
		$chapter4->save();

	       	$activity13 = new Activity;
	       	$activity13->activity_name = 'S-O-H4W13-21/21/21';
	       	$activity13->priority = 13;
	       	$activity13->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity13->chapter_id = $chapter4->id;
	       	$activity13->tax_labor_id = $tax1->id;
	       	$activity13->tax_material_id = $tax1->id;
	       	$activity13->tax_equipment_id = $tax1->id;
	       	$activity13->part_id = 2;
	       	$activity13->part_type_id = 2;
	       	$activity13->save();

				$Estimate_labor_activity13 = new EstimateLabor;
				$Estimate_labor_activity13->rate = '11.00';
				$Estimate_labor_activity13->amount = '22';
				$Estimate_labor_activity13->original = true;
				$Estimate_labor_activity13->isset = false;
				$Estimate_labor_activity13->activity_id = $activity13->id;
				$Estimate_labor_activity13->save();

					$Estimate_material_activity13_1 = new EstimateMaterial;
					$Estimate_material_activity13_1->material_name = 'S-O-H4W13-MAT1';
					$Estimate_material_activity13_1->unit = '121';
					$Estimate_material_activity13_1->rate = '9';
					$Estimate_material_activity13_1->amount = '1';
					$Estimate_material_activity13_1->original = true;
					$Estimate_material_activity13_1->isset = true;
					$Estimate_material_activity13_1->set_material_name = 'S-O-H4W13-MAT1-SET';
					$Estimate_material_activity13_1->set_unit = '121-SET';
					$Estimate_material_activity13_1->set_rate = '9';
					$Estimate_material_activity13_1->set_amount = '0.11';
					$Estimate_material_activity13_1->activity_id = $activity13->id;
					$Estimate_material_activity13_1->save();

					$Estimate_material_activity13_2 = new EstimateMaterial;
					$Estimate_material_activity13_2->material_name = 'S-O-H4W13-MAT2';
					$Estimate_material_activity13_2->unit = '122';
					$Estimate_material_activity13_2->rate = '9';
					$Estimate_material_activity13_2->amount = '2';
					$Estimate_material_activity13_2->original = true;
					$Estimate_material_activity13_2->isset = true;
					$Estimate_material_activity13_2->set_material_name = 'S-O-H4W13-MAT2-SET';
					$Estimate_material_activity13_2->set_unit = '122-SET';
					$Estimate_material_activity13_2->set_rate = '9';
					$Estimate_material_activity13_2->set_amount = '0.22';
					$Estimate_material_activity13_2->activity_id = $activity13->id;
					$Estimate_material_activity13_2->save();

					$Estimate_material_activity13_3 = new EstimateMaterial;
					$Estimate_material_activity13_3->material_name = 'S-O-H4W13-MAT3';
					$Estimate_material_activity13_3->unit = '123';
					$Estimate_material_activity13_3->rate = '9';
					$Estimate_material_activity13_3->amount = '3';
					$Estimate_material_activity13_3->original = true;
					$Estimate_material_activity13_3->isset = true;
					$Estimate_material_activity13_3->set_material_name = 'S-O-H4W13-MAT3-SET';
					$Estimate_material_activity13_3->set_unit = '123-SET';
					$Estimate_material_activity13_3->set_rate = '9';
					$Estimate_material_activity13_3->set_amount = '0.33';
					$Estimate_material_activity13_3->activity_id = $activity13->id;
					$Estimate_material_activity13_3->save();

					$Estimate_material_activity13_4 = new EstimateMaterial;
					$Estimate_material_activity13_4->material_name = 'S-O-H4W13-MAT4';
					$Estimate_material_activity13_4->unit = '124';
					$Estimate_material_activity13_4->rate = '9';
					$Estimate_material_activity13_4->amount = '4';
					$Estimate_material_activity13_4->original = true;
					$Estimate_material_activity13_4->isset = true;
					$Estimate_material_activity13_4->set_material_name = 'S-O-H4W13-MAT4-SET';
					$Estimate_material_activity13_4->set_unit = '124-SET';
					$Estimate_material_activity13_4->set_rate = '9';
					$Estimate_material_activity13_4->set_amount = '0.44';
					$Estimate_material_activity13_4->activity_id = $activity13->id;
					$Estimate_material_activity13_4->save();

					$Estimate_material_activity13_5 = new EstimateMaterial;
					$Estimate_material_activity13_5->material_name = 'S-O-H4W13-MAT5';
					$Estimate_material_activity13_5->unit = '125';
					$Estimate_material_activity13_5->rate = '9';
					$Estimate_material_activity13_5->amount = '5';
					$Estimate_material_activity13_5->original = true;
					$Estimate_material_activity13_5->isset = true;
					$Estimate_material_activity13_5->set_material_name = 'S-O-H4W13-MAT5-SET';
					$Estimate_material_activity13_5->set_unit = '125-SET';
					$Estimate_material_activity13_5->set_rate = '9';
					$Estimate_material_activity13_5->set_amount = '0.55';
					$Estimate_material_activity13_5->activity_id = $activity13->id;
					$Estimate_material_activity13_5->save();
				
						$Estimate_equipment_activity13_1 = new EstimateEquipment;
						$Estimate_equipment_activity13_1->equipment_name = 'S-O-H4W13-EQU1';
						$Estimate_equipment_activity13_1->unit = '126';
						$Estimate_equipment_activity13_1->rate = '10';
						$Estimate_equipment_activity13_1->amount = '1';
						$Estimate_equipment_activity13_1->original = true;
						$Estimate_equipment_activity13_1->isset = true;
						$Estimate_equipment_activity13_1->set_equipment_name = 'S-O-H4W13-EQU1-SET';
						$Estimate_equipment_activity13_1->set_unit = '126-SET';
						$Estimate_equipment_activity13_1->set_rate = '10';
						$Estimate_equipment_activity13_1->set_amount = '0.11';
						$Estimate_equipment_activity13_1->activity_id = $activity13->id;
						$Estimate_equipment_activity13_1->save();
						
						$Estimate_equipment_activity13_2 = new EstimateEquipment;
						$Estimate_equipment_activity13_2->equipment_name = 'S-O-H4W13-EQU2';
						$Estimate_equipment_activity13_2->unit = '127';
						$Estimate_equipment_activity13_2->rate = '10';
						$Estimate_equipment_activity13_2->amount = '2';
						$Estimate_equipment_activity13_2->original = true;
						$Estimate_equipment_activity13_2->isset = true;
						$Estimate_equipment_activity13_2->set_equipment_name = 'S-O-H4W13-EQU2-SET';
						$Estimate_equipment_activity13_2->set_unit = '127-SET';
						$Estimate_equipment_activity13_2->set_rate = '10';
						$Estimate_equipment_activity13_2->set_amount = '0.22';
						$Estimate_equipment_activity13_2->activity_id = $activity13->id;
						$Estimate_equipment_activity13_2->save();

						$Estimate_equipment_activity13_3 = new EstimateEquipment;
						$Estimate_equipment_activity13_3->equipment_name = 'S-O-H4W13-EQU3';
						$Estimate_equipment_activity13_3->unit = '128';
						$Estimate_equipment_activity13_3->rate = '10';
						$Estimate_equipment_activity13_3->amount = '3';
						$Estimate_equipment_activity13_3->original = true;
						$Estimate_equipment_activity13_3->isset = true;
						$Estimate_equipment_activity13_3->set_equipment_name = 'S-O-H4W13-EQU3-SET';
						$Estimate_equipment_activity13_3->set_unit = '128-SET';
						$Estimate_equipment_activity13_3->set_rate = '10';
						$Estimate_equipment_activity13_3->set_amount = '0.33';
						$Estimate_equipment_activity13_3->activity_id = $activity13->id;
						$Estimate_equipment_activity13_3->save();
						
						$Estimate_equipment_activity13_4 = new EstimateEquipment;
						$Estimate_equipment_activity13_4->equipment_name = 'S-O-H4W13-EQU4';
						$Estimate_equipment_activity13_4->unit = '129';
						$Estimate_equipment_activity13_4->rate = '10';
						$Estimate_equipment_activity13_4->amount = '4';
						$Estimate_equipment_activity13_4->original = true;
						$Estimate_equipment_activity13_4->isset = true;
						$Estimate_equipment_activity13_4->set_equipment_name = 'S-O-H4W13-EQU4-SET';
						$Estimate_equipment_activity13_4->set_unit = '129-SET';
						$Estimate_equipment_activity13_4->set_rate = '10';
						$Estimate_equipment_activity13_4->set_amount = '0.44';
						$Estimate_equipment_activity13_4->activity_id = $activity13->id;
						$Estimate_equipment_activity13_4->save();

						$Estimate_equipment_activity13_5 = new EstimateEquipment;
						$Estimate_equipment_activity13_5->equipment_name = 'S-O-H4W13-EQU5';
						$Estimate_equipment_activity13_5->unit = '130';
						$Estimate_equipment_activity13_5->rate = '10';
						$Estimate_equipment_activity13_5->amount = '5';
						$Estimate_equipment_activity13_5->original = true;
						$Estimate_equipment_activity13_5->isset = true;
						$Estimate_equipment_activity13_5->set_equipment_name = 'S-O-H4W13-EQU5-SET';
						$Estimate_equipment_activity13_5->set_unit = '130-SET';
						$Estimate_equipment_activity13_5->set_rate = '10';
						$Estimate_equipment_activity13_5->set_amount = '0.55';
						$Estimate_equipment_activity13_5->activity_id = $activity13->id;
						$Estimate_equipment_activity13_5->save();

			$activity14 = new Activity;
	       	$activity14->activity_name = 'S-O-H4W14-21/21/6';
	       	$activity14->priority = 14;
	       	$activity14->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity14->chapter_id = $chapter4->id;
	       	$activity14->tax_labor_id = $tax1->id;
	       	$activity14->tax_material_id = $tax1->id;
	       	$activity14->tax_equipment_id = $tax2->id;
	       	$activity14->part_id = 2;
	       	$activity14->part_type_id = 2;
	       	$activity14->save();

				$Estimate_labor_activity14 = new EstimateLabor;
				$Estimate_labor_activity14->rate = '11.00';
				$Estimate_labor_activity14->amount = '23';
				$Estimate_labor_activity14->original = true;
				$Estimate_labor_activity14->isset = false;
				$Estimate_labor_activity14->activity_id = $activity14->id;
				$Estimate_labor_activity14->save();

					$Estimate_material_activity14_1 = new EstimateMaterial;
					$Estimate_material_activity14_1->material_name = 'S-O-H4W14-MAT1';
					$Estimate_material_activity14_1->unit = '131';
					$Estimate_material_activity14_1->rate = '11';
					$Estimate_material_activity14_1->amount = '1';
					$Estimate_material_activity14_1->original = true;
					$Estimate_material_activity14_1->isset = true;
					$Estimate_material_activity14_1->set_material_name = 'S-O-H4W14-MAT1-SET';
					$Estimate_material_activity14_1->set_unit = '131-SET';
					$Estimate_material_activity14_1->set_rate = '11';
					$Estimate_material_activity14_1->set_amount = '0.11';
					$Estimate_material_activity14_1->activity_id = $activity14->id;
					$Estimate_material_activity14_1->save();

					$Estimate_material_activity14_2 = new EstimateMaterial;
					$Estimate_material_activity14_2->material_name = 'S-O-H4W14-MAT2';
					$Estimate_material_activity14_2->unit = '132';
					$Estimate_material_activity14_2->rate = '11';
					$Estimate_material_activity14_2->amount = '2';
					$Estimate_material_activity14_2->original = true;
					$Estimate_material_activity14_2->isset = true;
					$Estimate_material_activity14_2->set_material_name = 'S-O-H4W14-MAT2-SET';
					$Estimate_material_activity14_2->set_unit = '132-SET';
					$Estimate_material_activity14_2->set_rate = '11';
					$Estimate_material_activity14_2->set_amount = '0.22';
					$Estimate_material_activity14_2->activity_id = $activity14->id;
					$Estimate_material_activity14_2->save();

					$Estimate_material_activity14_3 = new EstimateMaterial;
					$Estimate_material_activity14_3->material_name = 'S-O-H4W14-MAT3';
					$Estimate_material_activity14_3->unit = '133';
					$Estimate_material_activity14_3->rate = '11';
					$Estimate_material_activity14_3->amount = '3';
					$Estimate_material_activity14_3->original = true;
					$Estimate_material_activity14_3->isset = true;
					$Estimate_material_activity14_3->set_material_name = 'S-O-H4W14-MAT3-SET';
					$Estimate_material_activity14_3->set_unit = '133-SET';
					$Estimate_material_activity14_3->set_rate = '11';
					$Estimate_material_activity14_3->set_amount = '0.33';
					$Estimate_material_activity14_3->activity_id = $activity14->id;
					$Estimate_material_activity14_3->save();

					$Estimate_material_activity14_4 = new EstimateMaterial;
					$Estimate_material_activity14_4->material_name = 'S-O-H4W14-MAT4';
					$Estimate_material_activity14_4->unit = '134';
					$Estimate_material_activity14_4->rate = '11';
					$Estimate_material_activity14_4->amount = '4';
					$Estimate_material_activity14_4->original = true;
					$Estimate_material_activity14_4->isset = true;
					$Estimate_material_activity14_4->set_material_name = 'S-O-H4W14-MAT4-SET';
					$Estimate_material_activity14_4->set_unit = '134-SET';
					$Estimate_material_activity14_4->set_rate = '11';
					$Estimate_material_activity14_4->set_amount = '0.44';
					$Estimate_material_activity14_4->activity_id = $activity14->id;
					$Estimate_material_activity14_4->save();

					$Estimate_material_activity14_5 = new EstimateMaterial;
					$Estimate_material_activity14_5->material_name = 'S-O-H4W14-MAT5';
					$Estimate_material_activity14_5->unit = '135';
					$Estimate_material_activity14_5->rate = '11';
					$Estimate_material_activity14_5->amount = '5';
					$Estimate_material_activity14_5->original = true;
					$Estimate_material_activity14_5->isset = true;
					$Estimate_material_activity14_5->set_material_name = 'S-O-H4W14-MAT5-SET';
					$Estimate_material_activity14_5->set_unit = '135-SET';
					$Estimate_material_activity14_5->set_rate = '11';
					$Estimate_material_activity14_5->set_amount = '0.55';
					$Estimate_material_activity14_5->activity_id = $activity14->id;
					$Estimate_material_activity14_5->save();
				
						$Estimate_equipment_activity14_1 = new EstimateEquipment;
						$Estimate_equipment_activity14_1->equipment_name = 'S-O-H4W14-EQU1';
						$Estimate_equipment_activity14_1->unit = '136';
						$Estimate_equipment_activity14_1->rate = '12';
						$Estimate_equipment_activity14_1->amount = '1';
						$Estimate_equipment_activity14_1->original = true;
						$Estimate_equipment_activity14_1->isset = true;
						$Estimate_equipment_activity14_1->set_equipment_name = 'S-O-H4W14-EQU1-SET';
						$Estimate_equipment_activity14_1->set_unit = '136-SET';
						$Estimate_equipment_activity14_1->set_rate = '12';
						$Estimate_equipment_activity14_1->set_amount = '0.11';
						$Estimate_equipment_activity14_1->activity_id = $activity14->id;
						$Estimate_equipment_activity14_1->save();
						
						$Estimate_equipment_activity14_2 = new EstimateEquipment;
						$Estimate_equipment_activity14_2->equipment_name = 'S-O-H4W14-EQU2';
						$Estimate_equipment_activity14_2->unit = '137';
						$Estimate_equipment_activity14_2->rate = '12';
						$Estimate_equipment_activity14_2->amount = '2';
						$Estimate_equipment_activity14_2->original = true;
						$Estimate_equipment_activity14_2->isset = true;
						$Estimate_equipment_activity14_2->set_equipment_name = 'S-O-H4W14-EQU2-SET';
						$Estimate_equipment_activity14_2->set_unit = '137-SET';
						$Estimate_equipment_activity14_2->set_rate = '12';
						$Estimate_equipment_activity14_2->set_amount = '0.22';
						$Estimate_equipment_activity14_2->activity_id = $activity14->id;
						$Estimate_equipment_activity14_2->save();

						$Estimate_equipment_activity14_3 = new EstimateEquipment;
						$Estimate_equipment_activity14_3->equipment_name = 'S-O-H4W14-EQU3';
						$Estimate_equipment_activity14_3->unit = '138';
						$Estimate_equipment_activity14_3->rate = '12';
						$Estimate_equipment_activity14_3->amount = '3';
						$Estimate_equipment_activity14_3->original = true;
						$Estimate_equipment_activity14_3->isset = true;
						$Estimate_equipment_activity14_3->set_equipment_name = 'S-O-H4W14-EQU3-SET';
						$Estimate_equipment_activity14_3->set_unit = '138-SET';
						$Estimate_equipment_activity14_3->set_rate = '12';
						$Estimate_equipment_activity14_3->set_amount = '0.33';
						$Estimate_equipment_activity14_3->activity_id = $activity14->id;
						$Estimate_equipment_activity14_3->save();
						
						$Estimate_equipment_activity14_4 = new EstimateEquipment;
						$Estimate_equipment_activity14_4->equipment_name = 'S-O-H4W14-EQU4';
						$Estimate_equipment_activity14_4->unit = '139';
						$Estimate_equipment_activity14_4->rate = '12';
						$Estimate_equipment_activity14_4->amount = '4';
						$Estimate_equipment_activity14_4->original = true;
						$Estimate_equipment_activity14_4->isset = true;
						$Estimate_equipment_activity14_4->set_equipment_name = 'S-O-H4W14-EQU4-SET';
						$Estimate_equipment_activity14_4->set_unit = '139-SET';
						$Estimate_equipment_activity14_4->set_rate = '12';
						$Estimate_equipment_activity14_4->set_amount = '0.44';
						$Estimate_equipment_activity14_4->activity_id = $activity14->id;
						$Estimate_equipment_activity14_4->save();

						$Estimate_equipment_activity14_5 = new EstimateEquipment;
						$Estimate_equipment_activity14_5->equipment_name = 'S-O-H4W14-EQU5';
						$Estimate_equipment_activity14_5->unit = '140';
						$Estimate_equipment_activity14_5->rate = '12';
						$Estimate_equipment_activity14_5->amount = '5';
						$Estimate_equipment_activity14_5->original = true;
						$Estimate_equipment_activity14_5->isset = true;
						$Estimate_equipment_activity14_5->set_equipment_name = 'S-O-H4W14-EQU5-SET';
						$Estimate_equipment_activity14_5->set_unit = '140-SET';
						$Estimate_equipment_activity14_5->set_rate = '12';
						$Estimate_equipment_activity14_5->set_amount = '0.55';
						$Estimate_equipment_activity14_5->activity_id = $activity14->id;
						$Estimate_equipment_activity14_5->save();

			$activity15 = new Activity;
	       	$activity15->activity_name = 'S-O-H4W15-21/6/6';
	       	$activity15->priority = 15;
	       	$activity15->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity15->chapter_id = $chapter4->id;
	       	$activity15->tax_labor_id = $tax1->id;
	       	$activity15->tax_material_id = $tax2->id;
	       	$activity15->tax_equipment_id = $tax2->id;
	       	$activity15->part_id = 2;
	       	$activity15->part_type_id = 2;
	       	$activity15->save();

				$Estimate_labor_activity15 = new EstimateLabor;
				$Estimate_labor_activity15->rate = '11.00';
				$Estimate_labor_activity15->amount = '24';
				$Estimate_labor_activity15->set_rate = '11.00';
				$Estimate_labor_activity15->set_amount = '0';
				$Estimate_labor_activity15->original = true;
				$Estimate_labor_activity15->isset = true;
				$Estimate_labor_activity15->activity_id = $activity15->id;
				$Estimate_labor_activity15->save();

					$Estimate_material_activity15_1 = new EstimateMaterial;
					$Estimate_material_activity15_1->material_name = 'S-O-H4W15-MAT1';
					$Estimate_material_activity15_1->unit = '141';
					$Estimate_material_activity15_1->rate = '13';
					$Estimate_material_activity15_1->amount = '1';
					$Estimate_material_activity15_1->original = true;
					$Estimate_material_activity15_1->isset = true;
					$Estimate_material_activity15_1->set_material_name = 'S-O-H4W15-MAT1-SET';
					$Estimate_material_activity15_1->set_unit = '141-SET';
					$Estimate_material_activity15_1->set_rate = '13';
					$Estimate_material_activity15_1->set_amount = '0.11';
					$Estimate_material_activity15_1->activity_id = $activity15->id;
					$Estimate_material_activity15_1->save();

					$Estimate_material_activity15_2 = new EstimateMaterial;
					$Estimate_material_activity15_2->material_name = 'S-O-H4W15-MAT2';
					$Estimate_material_activity15_2->unit = '142';
					$Estimate_material_activity15_2->rate = '13';
					$Estimate_material_activity15_2->amount = '2';
					$Estimate_material_activity15_2->original = true;
					$Estimate_material_activity15_2->isset = true;
					$Estimate_material_activity15_2->set_material_name = 'S-O-H4W15-MAT2-SET';
					$Estimate_material_activity15_2->set_unit = '142-SET';
					$Estimate_material_activity15_2->set_rate = '13';
					$Estimate_material_activity15_2->set_amount = '0.22';
					$Estimate_material_activity15_2->activity_id = $activity15->id;
					$Estimate_material_activity15_2->save();

					$Estimate_material_activity15_3 = new EstimateMaterial;
					$Estimate_material_activity15_3->material_name = 'S-O-H4W15-MAT3';
					$Estimate_material_activity15_3->unit = '143';
					$Estimate_material_activity15_3->rate = '13';
					$Estimate_material_activity15_3->amount = '3';
					$Estimate_material_activity15_3->original = true;
					$Estimate_material_activity15_3->isset = true;
					$Estimate_material_activity15_3->set_material_name = 'S-O-H4W15-MAT3-SET';
					$Estimate_material_activity15_3->set_unit = '143-SET';
					$Estimate_material_activity15_3->set_rate = '13';
					$Estimate_material_activity15_3->set_amount = '0.33';
					$Estimate_material_activity15_3->activity_id = $activity15->id;
					$Estimate_material_activity15_3->save();

					$Estimate_material_activity15_4 = new EstimateMaterial;
					$Estimate_material_activity15_4->material_name = 'S-O-H4W15-MAT4';
					$Estimate_material_activity15_4->unit = '144';
					$Estimate_material_activity15_4->rate = '13';
					$Estimate_material_activity15_4->amount = '4';
					$Estimate_material_activity15_4->original = true;
					$Estimate_material_activity15_4->isset = true;
					$Estimate_material_activity15_4->set_material_name = 'S-O-H4W15-MAT4-SET';
					$Estimate_material_activity15_4->set_unit = '144-SET';
					$Estimate_material_activity15_4->set_rate = '13';
					$Estimate_material_activity15_4->set_amount = '0.44';
					$Estimate_material_activity15_4->activity_id = $activity15->id;
					$Estimate_material_activity15_4->save();

					$Estimate_material_activity15_5 = new EstimateMaterial;
					$Estimate_material_activity15_5->material_name = 'S-O-H4W15-MAT5';
					$Estimate_material_activity15_5->unit = '145';
					$Estimate_material_activity15_5->rate = '13';
					$Estimate_material_activity15_5->amount = '5';
					$Estimate_material_activity15_5->original = true;
					$Estimate_material_activity15_5->isset = true;
					$Estimate_material_activity15_5->set_material_name = 'S-O-H4W15-MAT5-SET';
					$Estimate_material_activity15_5->set_unit = '145-SET';
					$Estimate_material_activity15_5->set_rate = '13';
					$Estimate_material_activity15_5->set_amount = '0.55';
					$Estimate_material_activity15_5->activity_id = $activity15->id;
					$Estimate_material_activity15_5->save();
				
						$Estimate_equipment_activity15_1 = new EstimateEquipment;
						$Estimate_equipment_activity15_1->equipment_name = 'S-O-H4W15-EQU1';
						$Estimate_equipment_activity15_1->unit = '146';
						$Estimate_equipment_activity15_1->rate = '14';
						$Estimate_equipment_activity15_1->amount = '1';
						$Estimate_equipment_activity15_1->original = true;
						$Estimate_equipment_activity15_1->isset = true;
						$Estimate_equipment_activity15_1->set_equipment_name = 'S-O-H4W15-EQU1-SET';
						$Estimate_equipment_activity15_1->set_unit = '146-SET';
						$Estimate_equipment_activity15_1->set_rate = '14';
						$Estimate_equipment_activity15_1->set_amount = '0.11';
						$Estimate_equipment_activity15_1->activity_id = $activity15->id;
						$Estimate_equipment_activity15_1->save();
						
						$Estimate_equipment_activity15_2 = new EstimateEquipment;
						$Estimate_equipment_activity15_2->equipment_name = 'S-O-H4W15-EQU2';
						$Estimate_equipment_activity15_2->unit = '147';
						$Estimate_equipment_activity15_2->rate = '14';
						$Estimate_equipment_activity15_2->amount = '2';
						$Estimate_equipment_activity15_2->original = true;
						$Estimate_equipment_activity15_2->isset = true;
						$Estimate_equipment_activity15_2->set_equipment_name = 'S-O-H4W15-EQU2-SET';
						$Estimate_equipment_activity15_2->set_unit = '147-SET';
						$Estimate_equipment_activity15_2->set_rate = '14';
						$Estimate_equipment_activity15_2->set_amount = '0.22';
						$Estimate_equipment_activity15_2->activity_id = $activity15->id;
						$Estimate_equipment_activity15_2->save();

						$Estimate_equipment_activity15_3 = new EstimateEquipment;
						$Estimate_equipment_activity15_3->equipment_name = 'S-O-H4W15-EQU3';
						$Estimate_equipment_activity15_3->unit = '148';
						$Estimate_equipment_activity15_3->rate = '14';
						$Estimate_equipment_activity15_3->amount = '3';
						$Estimate_equipment_activity15_3->original = true;
						$Estimate_equipment_activity15_3->isset = true;
						$Estimate_equipment_activity15_3->set_equipment_name = 'S-O-H4W15-EQU3-SET';
						$Estimate_equipment_activity15_3->set_unit = '148-SET';
						$Estimate_equipment_activity15_3->set_rate = '14';
						$Estimate_equipment_activity15_3->set_amount = '0.33';
						$Estimate_equipment_activity15_3->activity_id = $activity15->id;
						$Estimate_equipment_activity15_3->save();
						
						$Estimate_equipment_activity15_4 = new EstimateEquipment;
						$Estimate_equipment_activity15_4->equipment_name = 'S-O-H4W15-EQU4';
						$Estimate_equipment_activity15_4->unit = '149';
						$Estimate_equipment_activity15_4->rate = '14';
						$Estimate_equipment_activity15_4->amount = '4';
						$Estimate_equipment_activity15_4->original = true;
						$Estimate_equipment_activity15_4->isset = true;
						$Estimate_equipment_activity15_4->set_equipment_name = 'S-O-H4W15-EQU4-SET';
						$Estimate_equipment_activity15_4->set_unit = '149-SET';
						$Estimate_equipment_activity15_4->set_rate = '14';
						$Estimate_equipment_activity15_4->set_amount = '0.44';
						$Estimate_equipment_activity15_4->activity_id = $activity15->id;
						$Estimate_equipment_activity15_4->save();

						$Estimate_equipment_activity15_5 = new EstimateEquipment;
						$Estimate_equipment_activity15_5->equipment_name = 'S-O-H4W15-EQU5';
						$Estimate_equipment_activity15_5->unit = '150';
						$Estimate_equipment_activity15_5->rate = '14';
						$Estimate_equipment_activity15_5->amount = '5';
						$Estimate_equipment_activity15_5->original = true;
						$Estimate_equipment_activity15_5->isset = true;
						$Estimate_equipment_activity15_5->set_equipment_name = 'S-O-H4W15-EQU5-SET';
						$Estimate_equipment_activity15_5->set_unit = '150-SET';
						$Estimate_equipment_activity15_5->set_rate = '14';
						$Estimate_equipment_activity15_5->set_amount = '0.55';
						$Estimate_equipment_activity15_5->activity_id = $activity15->id;
						$Estimate_equipment_activity15_5->save();

			$activity16= new Activity;
	       	$activity16->activity_name = 'S-O-H4W16-6/6/6';
	       	$activity16->priority = 16;
	       	$activity16->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity16->chapter_id = $chapter4->id;
	       	$activity16->tax_labor_id = $tax2->id;
	       	$activity16->tax_material_id = $tax2->id;
	       	$activity16->tax_equipment_id = $tax2->id;
	       	$activity16->part_id = 2;
	       	$activity16->part_type_id = 2;
	       	$activity16->save();

	  			$Estimate_labor_activity16 = new EstimateLabor;
				$Estimate_labor_activity16->rate = '11.00';
				$Estimate_labor_activity16->amount = '25';
				$Estimate_labor_activity16->set_rate = '11.00';
				$Estimate_labor_activity16->set_amount = '0';
				$Estimate_labor_activity16->original = true;
				$Estimate_labor_activity16->isset = true;
				$Estimate_labor_activity16->activity_id = $activity16->id;
				$Estimate_labor_activity16->save();

					$Estimate_material_activity16_1 = new EstimateMaterial;
					$Estimate_material_activity16_1->material_name = 'S-O-H4W16-MAT1';
					$Estimate_material_activity16_1->unit = '151';
					$Estimate_material_activity16_1->rate = '15';
					$Estimate_material_activity16_1->amount = '1';
					$Estimate_material_activity16_1->original = true;
					$Estimate_material_activity16_1->isset = false;
					$Estimate_material_activity16_1->activity_id = $activity16->id;
					$Estimate_material_activity16_1->save();

					$Estimate_material_activity16_2 = new EstimateMaterial;
					$Estimate_material_activity16_2->material_name = 'S-O-H4W16-MAT2';
					$Estimate_material_activity16_2->unit = '152';
					$Estimate_material_activity16_2->rate = '15';
					$Estimate_material_activity16_2->amount = '2';
					$Estimate_material_activity16_2->original = true;
					$Estimate_material_activity16_2->isset = false;
					$Estimate_material_activity16_2->activity_id = $activity16->id;
					$Estimate_material_activity16_2->save();

					$Estimate_material_activity16_3 = new EstimateMaterial;
					$Estimate_material_activity16_3->material_name = 'S-O-H4W16-MAT3';
					$Estimate_material_activity16_3->unit = '153';
					$Estimate_material_activity16_3->rate = '15';
					$Estimate_material_activity16_3->amount = '3';
					$Estimate_material_activity16_3->original = true;
					$Estimate_material_activity16_3->isset = false;
					$Estimate_material_activity16_3->activity_id = $activity16->id;
					$Estimate_material_activity16_3->save();

					$Estimate_material_activity16_4 = new EstimateMaterial;
					$Estimate_material_activity16_4->material_name = 'S-O-H4W16-MAT4';
					$Estimate_material_activity16_4->unit = '154';
					$Estimate_material_activity16_4->rate = '15';
					$Estimate_material_activity16_4->amount = '4';
					$Estimate_material_activity16_4->original = true;
					$Estimate_material_activity16_4->isset = false;
					$Estimate_material_activity16_4->activity_id = $activity16->id;
					$Estimate_material_activity16_4->save();

					$Estimate_material_activity16_5 = new EstimateMaterial;
					$Estimate_material_activity16_5->material_name = 'S-O-H4W16-MAT5';
					$Estimate_material_activity16_5->unit = '155';
					$Estimate_material_activity16_5->rate = '15';
					$Estimate_material_activity16_5->amount = '5';
					$Estimate_material_activity16_5->original = true;
					$Estimate_material_activity16_5->isset = false;
					$Estimate_material_activity16_5->activity_id = $activity16->id;
					$Estimate_material_activity16_5->save();
				
						$Estimate_equipment_activity16_1 = new EstimateEquipment;
						$Estimate_equipment_activity16_1->equipment_name = 'S-O-H4W16-EQU1';
						$Estimate_equipment_activity16_1->unit = '156';
						$Estimate_equipment_activity16_1->rate = '16';
						$Estimate_equipment_activity16_1->amount = '1';
						$Estimate_equipment_activity16_1->original = true;
						$Estimate_equipment_activity16_1->isset = false;
						$Estimate_equipment_activity16_1->activity_id = $activity16->id;
						$Estimate_equipment_activity16_1->save();
						
						$Estimate_equipment_activity16_2 = new EstimateEquipment;
						$Estimate_equipment_activity16_2->equipment_name = 'S-O-H4W16-EQU2';
						$Estimate_equipment_activity16_2->unit = '157';
						$Estimate_equipment_activity16_2->rate = '16';
						$Estimate_equipment_activity16_2->amount = '2';
						$Estimate_equipment_activity16_2->original = true;
						$Estimate_equipment_activity16_2->isset = false;
						$Estimate_equipment_activity16_2->activity_id = $activity16->id;
						$Estimate_equipment_activity16_2->save();

						$Estimate_equipment_activity16_3 = new EstimateEquipment;
						$Estimate_equipment_activity16_3->equipment_name = 'S-O-H4W16-EQU3';
						$Estimate_equipment_activity16_3->unit = '158';
						$Estimate_equipment_activity16_3->rate = '16';
						$Estimate_equipment_activity16_3->amount = '3';
						$Estimate_equipment_activity16_3->original = true;
						$Estimate_equipment_activity16_3->isset = false;
						$Estimate_equipment_activity16_3->activity_id = $activity16->id;
						$Estimate_equipment_activity16_3->save();
						
						$Estimate_equipment_activity16_4 = new EstimateEquipment;
						$Estimate_equipment_activity16_4->equipment_name = 'S-O-H4W16-EQU4';
						$Estimate_equipment_activity16_4->unit = '159';
						$Estimate_equipment_activity16_4->rate = '16';
						$Estimate_equipment_activity16_4->amount = '4';
						$Estimate_equipment_activity16_4->original = true;
						$Estimate_equipment_activity16_4->isset = false;
						$Estimate_equipment_activity16_4->activity_id = $activity16->id;
						$Estimate_equipment_activity16_4->save();

						$Estimate_equipment_activity16_5 = new EstimateEquipment;
						$Estimate_equipment_activity16_5->equipment_name = 'S-O-H4W16-EQU5';
						$Estimate_equipment_activity16_5->unit = '160';
						$Estimate_equipment_activity16_5->rate = '16';
						$Estimate_equipment_activity16_5->amount = '5';
						$Estimate_equipment_activity16_5->original = true;
						$Estimate_equipment_activity16_5->isset = false;
						$Estimate_equipment_activity16_5->activity_id = $activity16->id;
						$Estimate_equipment_activity16_5->save();

		



















		$chapter5 = new Chapter;
		$chapter5->chapter_name = 'MW-A-H5';
		$chapter5->priority = 5;
		$chapter5->project_id = $project->id;
		$chapter5->save();

	       	$activity17 = new Activity;
	       	$activity17->activity_name = 'MW-A-H5W17-21/21/21';
	       	$activity17->priority = 17;
	       	$activity17->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity17->chapter_id = $chapter5->id;
	       	$activity17->tax_labor_id = $tax1->id;
	       	$activity17->tax_material_id = $tax1->id;
	       	$activity17->tax_equipment_id = $tax1->id;
	       	$activity17->part_id = 1;
	       	$activity17->part_type_id = 1;
	       	$activity17->detail_id = 1;
	       	$activity17->save();

				$calculation_labor_activity17 = new MoreLabor;
				$calculation_labor_activity17->rate = '20.00';
				$calculation_labor_activity17->amount = '10';
				$calculation_labor_activity17->activity_id = $activity17->id;
				$calculation_labor_activity17->save();

					$calculation_material_activity17_1 = new MoreMaterial;
					$calculation_material_activity17_1->material_name = 'MW-A-H5W17-MAT1';
					$calculation_material_activity17_1->unit = '161';
					$calculation_material_activity17_1->rate = '1';
					$calculation_material_activity17_1->amount = '1';
					$calculation_material_activity17_1->activity_id = $activity17->id;
					$calculation_material_activity17_1->save();

					$calculation_material_activity17_2 = new MoreMaterial;
					$calculation_material_activity17_2->material_name = 'MW-A-H5W17-MAT2';
					$calculation_material_activity17_2->unit = '162';
					$calculation_material_activity17_2->rate = '1';
					$calculation_material_activity17_2->amount = '2';
					$calculation_material_activity17_2->activity_id = $activity17->id;
					$calculation_material_activity17_2->save();

					$calculation_material_activity17_3 = new MoreMaterial;
					$calculation_material_activity17_3->material_name = 'MW-A-H5W17-MAT2';
					$calculation_material_activity17_3->unit = '163';
					$calculation_material_activity17_3->rate = '1';
					$calculation_material_activity17_3->amount = '3';
					$calculation_material_activity17_3->activity_id = $activity17->id;
					$calculation_material_activity17_3->save();

					$calculation_material_activity17_4 = new MoreMaterial;
					$calculation_material_activity17_4->material_name = 'MW-A-H5W17-MAT3';
					$calculation_material_activity17_4->unit = '164';
					$calculation_material_activity17_4->rate = '1';
					$calculation_material_activity17_4->amount = '4';
					$calculation_material_activity17_4->activity_id = $activity17->id;
					$calculation_material_activity17_4->save();

					$calculation_material_activity17_5 = new MoreMaterial;
					$calculation_material_activity17_5->material_name = 'MW-A-H5W17-MAT5';
					$calculation_material_activity17_5->unit = '165';
					$calculation_material_activity17_5->rate = '1';
					$calculation_material_activity17_5->amount = '5';
					$calculation_material_activity17_5->activity_id = $activity17->id;
					$calculation_material_activity17_5->save();
				
						$calculation_equipment_activity17_1 = new MoreEquipment;
						$calculation_equipment_activity17_1->equipment_name = 'MW-A-H5W17-EQU1';
						$calculation_equipment_activity17_1->unit = '166';
						$calculation_equipment_activity17_1->rate = '2';
						$calculation_equipment_activity17_1->amount = '1';
						$calculation_equipment_activity17_1->activity_id = $activity17->id;
						$calculation_equipment_activity17_1->save();
						
						$calculation_equipment_activity17_2 = new MoreEquipment;
						$calculation_equipment_activity17_2->equipment_name = 'MW-A-H5W17-EQU2';
						$calculation_equipment_activity17_2->unit = '167';
						$calculation_equipment_activity17_2->rate = '2';
						$calculation_equipment_activity17_2->amount = '2';
						$calculation_equipment_activity17_2->activity_id = $activity17->id;
						$calculation_equipment_activity17_2->save();

						$calculation_equipment_activity17_3 = new MoreEquipment;
						$calculation_equipment_activity17_3->equipment_name = 'MW-A-H5W17-EQU3';
						$calculation_equipment_activity17_3->unit = '168';
						$calculation_equipment_activity17_3->rate = '2';
						$calculation_equipment_activity17_3->amount = '3';
						$calculation_equipment_activity17_3->activity_id = $activity17->id;
						$calculation_equipment_activity17_3->save();
						
						$calculation_equipment_activity17_4 = new MoreEquipment;
						$calculation_equipment_activity17_4->equipment_name = 'MW-A-H5W17-EQU4';
						$calculation_equipment_activity17_4->unit = '169';
						$calculation_equipment_activity17_4->rate = '2';
						$calculation_equipment_activity17_4->amount = '4';
						$calculation_equipment_activity17_4->activity_id = $activity17->id;
						$calculation_equipment_activity17_4->save();

						$calculation_equipment_activity17_5 = new MoreEquipment;
						$calculation_equipment_activity17_5->equipment_name = 'MW-A-H5W17-EQU5';
						$calculation_equipment_activity17_5->unit = '170';
						$calculation_equipment_activity17_5->rate = '2';
						$calculation_equipment_activity17_5->amount = '5';
						$calculation_equipment_activity17_5->activity_id = $activity17->id;
						$calculation_equipment_activity17_5->save();

			$activity18 = new Activity;
	       	$activity18->activity_name = 'MW-A-H5W18-21/21/6';
	       	$activity18->priority = 18;
	       	$activity18->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity18->chapter_id = $chapter5->id;
	       	$activity18->tax_labor_id = $tax1->id;
	       	$activity18->tax_material_id = $tax1->id;
	       	$activity18->tax_equipment_id = $tax2->id;
	       	$activity18->part_id = 1;
	       	$activity18->part_type_id = 1;
	      	$activity18->detail_id = 1;
	       	$activity18->save();

				$calculation_labor_activity18 = new MoreLabor;
				$calculation_labor_activity18->rate = '20.00';
				$calculation_labor_activity18->amount = '11';
				$calculation_labor_activity18->activity_id = $activity18->id;
				$calculation_labor_activity18->save();

					$calculation_material_activity18_1 = new MoreMaterial;
					$calculation_material_activity18_1->material_name = 'MW-A-H5W18-MAT1';
					$calculation_material_activity18_1->unit = '171';
					$calculation_material_activity18_1->rate = '3';
					$calculation_material_activity18_1->amount = '1';
					$calculation_material_activity18_1->activity_id = $activity18->id;
					$calculation_material_activity18_1->save();

					$calculation_material_activity18_2 = new MoreMaterial;
					$calculation_material_activity18_2->material_name = 'MW-A-H5W18-MAT2';
					$calculation_material_activity18_2->unit = '172';
					$calculation_material_activity18_2->rate = '3';
					$calculation_material_activity18_2->amount = '2';
					$calculation_material_activity18_2->activity_id = $activity18->id;
					$calculation_material_activity18_2->save();

					$calculation_material_activity18_3 = new MoreMaterial;
					$calculation_material_activity18_3->material_name = 'MW-A-H5W18-MAT3';
					$calculation_material_activity18_3->unit = '173';
					$calculation_material_activity18_3->rate = '3';
					$calculation_material_activity18_3->amount = '3';
					$calculation_material_activity18_3->activity_id = $activity18->id;
					$calculation_material_activity18_3->save();

					$calculation_material_activity18_4 = new MoreMaterial;
					$calculation_material_activity18_4->material_name = 'MW-A-H5W18-MAT4';
					$calculation_material_activity18_4->unit = '174';
					$calculation_material_activity18_4->rate = '3';
					$calculation_material_activity18_4->amount = '4';
					$calculation_material_activity18_4->activity_id = $activity18->id;
					$calculation_material_activity18_4->save();

					$calculation_material_activity18_5 = new MoreMaterial;
					$calculation_material_activity18_5->material_name = 'MW-A-H5W18-MAT5';
					$calculation_material_activity18_5->unit = '175';
					$calculation_material_activity18_5->rate = '3';
					$calculation_material_activity18_5->amount = '5';
					$calculation_material_activity18_5->activity_id = $activity18->id;
					$calculation_material_activity18_5->save();
				
						$calculation_equipment_activity18_1 = new MoreEquipment;
						$calculation_equipment_activity18_1->equipment_name = 'MW-A-H5W18-EQU1';
						$calculation_equipment_activity18_1->unit = '176';
						$calculation_equipment_activity18_1->rate = '4';
						$calculation_equipment_activity18_1->amount = '1';
						$calculation_equipment_activity18_1->activity_id = $activity18->id;
						$calculation_equipment_activity18_1->save();
						
						$calculation_equipment_activity18_2 = new MoreEquipment;
						$calculation_equipment_activity18_2->equipment_name = 'MW-A-H5W18-EQU2';
						$calculation_equipment_activity18_2->unit = '177';
						$calculation_equipment_activity18_2->rate = '4';
						$calculation_equipment_activity18_2->amount = '2';
						$calculation_equipment_activity18_2->activity_id = $activity18->id;
						$calculation_equipment_activity18_2->save();

						$calculation_equipment_activity18_3 = new MoreEquipment;
						$calculation_equipment_activity18_3->equipment_name = 'MW-A-H5W18-EQU3';
						$calculation_equipment_activity18_3->unit = '178';
						$calculation_equipment_activity18_3->rate = '4';
						$calculation_equipment_activity18_3->amount = '3';
						$calculation_equipment_activity18_3->activity_id = $activity18->id;
						$calculation_equipment_activity18_3->save();
						
						$calculation_equipment_activity18_4 = new MoreEquipment;
						$calculation_equipment_activity18_4->equipment_name = 'MW-A-H5W18-EQU4';
						$calculation_equipment_activity18_4->unit = '179';
						$calculation_equipment_activity18_4->rate = '4';
						$calculation_equipment_activity18_4->amount = '4';
						$calculation_equipment_activity18_4->activity_id = $activity18->id;
						$calculation_equipment_activity18_4->save();

						$calculation_equipment_activity18_5 = new MoreEquipment;
						$calculation_equipment_activity18_5->equipment_name = 'MW-A-H5W18-EQU5';
						$calculation_equipment_activity18_5->unit = '180';
						$calculation_equipment_activity18_5->rate = '4';
						$calculation_equipment_activity18_5->amount = '5';
						$calculation_equipment_activity18_5->activity_id = $activity18->id;
						$calculation_equipment_activity18_5->save();

			$activity19 = new Activity;
	       	$activity19->activity_name = 'MW-A-H5W19-21/6/6';
	       	$activity19->priority = 19;
	       	$activity19->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity19->chapter_id = $chapter5->id;
	       	$activity19->tax_labor_id = $tax1->id;
	       	$activity19->tax_material_id = $tax2->id;
	       	$activity19->tax_equipment_id = $tax2->id;
	       	$activity19->part_id = 1;
	       	$activity19->part_type_id = 1;
	       	$activity19->detail_id = 1;
	       	$activity19->save();

				$calculation_labor_activity19 = new MoreLabor;
				$calculation_labor_activity19->rate = '20.00';
				$calculation_labor_activity19->amount = '12';
				$calculation_labor_activity19->activity_id = $activity19->id;
				$calculation_labor_activity19->save();

					$calculation_material_activity19_1 = new MoreMaterial;
					$calculation_material_activity19_1->material_name = 'MW-A-H5W19-MAT1';
					$calculation_material_activity19_1->unit = '181';
					$calculation_material_activity19_1->rate = '5';
					$calculation_material_activity19_1->amount = '1';
					$calculation_material_activity19_1->activity_id = $activity19->id;
					$calculation_material_activity19_1->save();

					$calculation_material_activity19_2 = new MoreMaterial;
					$calculation_material_activity19_2->material_name = 'MW-A-H5W19-MAT2';
					$calculation_material_activity19_2->unit = '182';
					$calculation_material_activity19_2->rate = '5';
					$calculation_material_activity19_2->amount = '2';
					$calculation_material_activity19_2->activity_id = $activity19->id;
					$calculation_material_activity19_2->save();

					$calculation_material_activity19_3 = new MoreMaterial;
					$calculation_material_activity19_3->material_name = 'MW-A-H5W19-MAT3';
					$calculation_material_activity19_3->unit = '183';
					$calculation_material_activity19_3->rate = '5';
					$calculation_material_activity19_3->amount = '3';
					$calculation_material_activity19_3->activity_id = $activity19->id;
					$calculation_material_activity19_3->save();

					$calculation_material_activity19_4 = new MoreMaterial;
					$calculation_material_activity19_4->material_name = 'MW-A-H5W19-MAT4';
					$calculation_material_activity19_4->unit = '184';
					$calculation_material_activity19_4->rate = '5';
					$calculation_material_activity19_4->amount = '4';
					$calculation_material_activity19_4->activity_id = $activity19->id;
					$calculation_material_activity19_4->save();

					$calculation_material_activity19_5 = new MoreMaterial;
					$calculation_material_activity19_5->material_name = 'MW-A-H5W19-MAT5';
					$calculation_material_activity19_5->unit = '185';
					$calculation_material_activity19_5->rate = '5';
					$calculation_material_activity19_5->amount = '5';
					$calculation_material_activity19_5->activity_id = $activity19->id;
					$calculation_material_activity19_5->save();
				
						$calculation_equipment_activity19_1 = new MoreEquipment;
						$calculation_equipment_activity19_1->equipment_name = 'MW-A-H5W19-EQU1';
						$calculation_equipment_activity19_1->unit = '186';
						$calculation_equipment_activity19_1->rate = '6';
						$calculation_equipment_activity19_1->amount = '1';
						$calculation_equipment_activity19_1->activity_id = $activity19->id;
						$calculation_equipment_activity19_1->save();
						
						$calculation_equipment_activity19_2 = new MoreEquipment;
						$calculation_equipment_activity19_2->equipment_name = 'MW-A-H5W19-EQU2';
						$calculation_equipment_activity19_2->unit = '187';
						$calculation_equipment_activity19_2->rate = '6';
						$calculation_equipment_activity19_2->amount = '2';
						$calculation_equipment_activity19_2->activity_id = $activity19->id;
						$calculation_equipment_activity19_2->save();

						$calculation_equipment_activity19_3 = new MoreEquipment;
						$calculation_equipment_activity19_3->equipment_name = 'MW-A-H5W19-EQU3';
						$calculation_equipment_activity19_3->unit = '188';
						$calculation_equipment_activity19_3->rate = '6';
						$calculation_equipment_activity19_3->amount = '3';
						$calculation_equipment_activity19_3->activity_id = $activity19->id;
						$calculation_equipment_activity19_3->save();
						
						$calculation_equipment_activity19_4 = new MoreEquipment;
						$calculation_equipment_activity19_4->equipment_name = 'MW-A-H5W19-EQU4';
						$calculation_equipment_activity19_4->unit = '189';
						$calculation_equipment_activity19_4->rate = '6';
						$calculation_equipment_activity19_4->amount = '4';
						$calculation_equipment_activity19_4->activity_id = $activity19->id;
						$calculation_equipment_activity19_4->save();

						$calculation_equipment_activity19_5 = new MoreEquipment;
						$calculation_equipment_activity19_5->equipment_name = 'MW-A-H5W19-EQU5';
						$calculation_equipment_activity19_5->unit = '190';
						$calculation_equipment_activity19_5->rate = '6';
						$calculation_equipment_activity19_5->amount = '5';
						$calculation_equipment_activity19_5->activity_id = $activity19->id;
						$calculation_equipment_activity19_5->save();

			$activity20 = new Activity;
	       	$activity20->activity_name = 'MW-A-H5W20-6/6/6';
	       	$activity20->priority = 20;
	       	$activity20->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity20->chapter_id = $chapter5->id;
	       	$activity20->tax_labor_id = $tax2->id;
	       	$activity20->tax_material_id = $tax2->id;
	       	$activity20->tax_equipment_id = $tax2->id;
	       	$activity20->part_id = 1;
	       	$activity20->part_type_id = 1;
	       	$activity20->detail_id = 1;
	       	$activity20->save();

				$calculation_labor_activity20 = new MoreLabor;
				$calculation_labor_activity20->rate = '20.00';
				$calculation_labor_activity20->amount = '13';
				$calculation_labor_activity20->activity_id = $activity20->id;
				$calculation_labor_activity20->save();

					$calculation_material_activity20_1 = new MoreMaterial;
					$calculation_material_activity20_1->material_name = 'MW-A-H5W20-MAT1';
					$calculation_material_activity20_1->unit = '191';
					$calculation_material_activity20_1->rate = '7';
					$calculation_material_activity20_1->amount = '1';
					$calculation_material_activity20_1->activity_id = $activity20->id;
					$calculation_material_activity20_1->save();

					$calculation_material_activity20_2 = new MoreMaterial;
					$calculation_material_activity20_2->material_name = 'MW-A-H5W20-MAT2';
					$calculation_material_activity20_2->unit = '192';
					$calculation_material_activity20_2->rate = '7';
					$calculation_material_activity20_2->amount = '2';
					$calculation_material_activity20_2->activity_id = $activity20->id;
					$calculation_material_activity20_2->save();

					$calculation_material_activity20_3 = new MoreMaterial;
					$calculation_material_activity20_3->material_name = 'MW-A-H5W20-MAT3';
					$calculation_material_activity20_3->unit = '193';
					$calculation_material_activity20_3->rate = '7';
					$calculation_material_activity20_3->amount = '3';
					$calculation_material_activity20_3->activity_id = $activity20->id;
					$calculation_material_activity20_3->save();

					$calculation_material_activity20_4 = new MoreMaterial;
					$calculation_material_activity20_4->material_name = 'MW-A-H5W20-MAT4';
					$calculation_material_activity20_4->unit = '194';
					$calculation_material_activity20_4->rate = '7';
					$calculation_material_activity20_4->amount = '4';
					$calculation_material_activity20_4->activity_id = $activity20->id;
					$calculation_material_activity20_4->save();

					$calculation_material_activity20_5 = new MoreMaterial;
					$calculation_material_activity20_5->material_name = 'MW-A-H5W20-MAT5';
					$calculation_material_activity20_5->unit = '195';
					$calculation_material_activity20_5->rate = '7';
					$calculation_material_activity20_5->amount = '5';
					$calculation_material_activity20_5->activity_id = $activity20->id;
					$calculation_material_activity20_5->save();
				
						$calculation_equipment_activity20_1 = new MoreEquipment;
						$calculation_equipment_activity20_1->equipment_name = 'MW-A-H5W20-EQU1';
						$calculation_equipment_activity20_1->unit = '196';
						$calculation_equipment_activity20_1->rate = '8';
						$calculation_equipment_activity20_1->amount = '1';
						$calculation_equipment_activity20_1->activity_id = $activity20->id;
						$calculation_equipment_activity20_1->save();
						
						$calculation_equipment_activity20_2 = new MoreEquipment;
						$calculation_equipment_activity20_2->equipment_name = 'MW-A-H5W20-EQU2';
						$calculation_equipment_activity20_2->unit = '197';
						$calculation_equipment_activity20_2->rate = '8';
						$calculation_equipment_activity20_2->amount = '2';
						$calculation_equipment_activity20_2->activity_id = $activity20->id;
						$calculation_equipment_activity20_2->save();

						$calculation_equipment_activity20_3 = new MoreEquipment;
						$calculation_equipment_activity20_3->equipment_name = 'MW-A-H5W20-EQU3';
						$calculation_equipment_activity20_3->unit = '198';
						$calculation_equipment_activity20_3->rate = '8';
						$calculation_equipment_activity20_3->amount = '3';
						$calculation_equipment_activity20_3->activity_id = $activity20->id;
						$calculation_equipment_activity20_3->save();
						
						$calculation_equipment_activity20_4 = new MoreEquipment;
						$calculation_equipment_activity20_4->equipment_name = 'MW-A-H5W20-EQU4';
						$calculation_equipment_activity20_4->unit = '199';
						$calculation_equipment_activity20_4->rate = '8';
						$calculation_equipment_activity20_4->amount = '4';
						$calculation_equipment_activity20_4->activity_id = $activity20->id;
						$calculation_equipment_activity20_4->save();

						$calculation_equipment_activity20_5 = new MoreEquipment;
						$calculation_equipment_activity20_5->equipment_name = 'MW-A-H5W20-EQU5';
						$calculation_equipment_activity20_5->unit = '200';
						$calculation_equipment_activity20_5->rate = '8';
						$calculation_equipment_activity20_5->amount = '5';
						$calculation_equipment_activity20_5->activity_id = $activity20->id;
						$calculation_equipment_activity20_5->save();

		$chapter6 = new Chapter;
		$chapter6->chapter_name = 'MW-O-H6';
		$chapter6->priority = 6;
		$chapter6->project_id = $project->id;
		$chapter6->save();

	       	$activity21 = new Activity;
	       	$activity21->activity_name = 'MW-O-H6W21-21/21/21';
	       	$activity21->priority = 21;
	       	$activity21->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity21->chapter_id = $chapter6->id;
	       	$activity21->tax_labor_id = $tax1->id;
	       	$activity21->tax_material_id = $tax1->id;
	       	$activity21->tax_equipment_id = $tax1->id;
	       	$activity21->part_id = 2;
	       	$activity21->part_type_id = 1;
	       	$activity21->detail_id = 1;
	       	$activity21->save();

				$calculation_labor_activity21 = new MoreLabor;
				$calculation_labor_activity21->rate = '11.00';
				$calculation_labor_activity21->amount = '14';
				$calculation_labor_activity21->activity_id = $activity21->id;
				$calculation_labor_activity21->save();

					$calculation_material_activity21_1 = new MoreMaterial;
					$calculation_material_activity21_1->material_name = 'MW-O-H6W21-MAT1';
					$calculation_material_activity21_1->unit = '201';
					$calculation_material_activity21_1->rate = '9';
					$calculation_material_activity21_1->amount = '1';
					$calculation_material_activity21_1->activity_id = $activity21->id;
					$calculation_material_activity21_1->save();

					$calculation_material_activity21_2 = new MoreMaterial;
					$calculation_material_activity21_2->material_name = 'MW-O-H6W21-MAT2';
					$calculation_material_activity21_2->unit = '202';
					$calculation_material_activity21_2->rate = '9';
					$calculation_material_activity21_2->amount = '2';
					$calculation_material_activity21_2->activity_id = $activity21->id;
					$calculation_material_activity21_2->save();

					$calculation_material_activity21_3 = new MoreMaterial;
					$calculation_material_activity21_3->material_name = 'MW-O-H6W21-MAT3';
					$calculation_material_activity21_3->unit = '203';
					$calculation_material_activity21_3->rate = '9';
					$calculation_material_activity21_3->amount = '3';
					$calculation_material_activity21_3->activity_id = $activity21->id;
					$calculation_material_activity21_3->save();

					$calculation_material_activity21_4 = new MoreMaterial;
					$calculation_material_activity21_4->material_name = 'MW-O-H6W21-MAT4';
					$calculation_material_activity21_4->unit = '204';
					$calculation_material_activity21_4->rate = '9';
					$calculation_material_activity21_4->amount = '4';
					$calculation_material_activity21_4->activity_id = $activity21->id;
					$calculation_material_activity21_4->save();

					$calculation_material_activity21_5 = new MoreMaterial;
					$calculation_material_activity21_5->material_name = 'MW-O-H6W21-MAT5';
					$calculation_material_activity21_5->unit = '205';
					$calculation_material_activity21_5->rate = '9';
					$calculation_material_activity21_5->amount = '5';
					$calculation_material_activity21_5->activity_id = $activity21->id;
					$calculation_material_activity21_5->save();
				
						$calculation_equipment_activity21_1 = new MoreEquipment;
						$calculation_equipment_activity21_1->equipment_name = 'MW-O-H6W21-EQU1';
						$calculation_equipment_activity21_1->unit = '206';
						$calculation_equipment_activity21_1->rate = '10';
						$calculation_equipment_activity21_1->amount = '1';
						$calculation_equipment_activity21_1->activity_id = $activity21->id;
						$calculation_equipment_activity21_1->save();
						
						$calculation_equipment_activity21_2 = new MoreEquipment;
						$calculation_equipment_activity21_2->equipment_name = 'MW-O-H6W21-EQU2';
						$calculation_equipment_activity21_2->unit = '207';
						$calculation_equipment_activity21_2->rate = '10';
						$calculation_equipment_activity21_2->amount = '2';
						$calculation_equipment_activity21_2->activity_id = $activity21->id;
						$calculation_equipment_activity21_2->save();

						$calculation_equipment_activity21_3 = new MoreEquipment;
						$calculation_equipment_activity21_3->equipment_name = 'MW-O-H6W21-EQU3';
						$calculation_equipment_activity21_3->unit = '208';
						$calculation_equipment_activity21_3->rate = '10';
						$calculation_equipment_activity21_3->amount = '3';
						$calculation_equipment_activity21_3->activity_id = $activity21->id;
						$calculation_equipment_activity21_3->save();
						
						$calculation_equipment_activity21_4 = new MoreEquipment;
						$calculation_equipment_activity21_4->equipment_name = 'MW-O-H6W21-EQU4';
						$calculation_equipment_activity21_4->unit = '209';
						$calculation_equipment_activity21_4->rate = '10';
						$calculation_equipment_activity21_4->amount = '4';
						$calculation_equipment_activity21_4->activity_id = $activity21->id;
						$calculation_equipment_activity21_4->save();

						$calculation_equipment_activity21_5 = new MoreEquipment;
						$calculation_equipment_activity21_5->equipment_name = 'MW-O-H6W21-EQU5';
						$calculation_equipment_activity21_5->unit = '210';
						$calculation_equipment_activity21_5->rate = '10';
						$calculation_equipment_activity21_5->amount = '5';
						$calculation_equipment_activity21_5->activity_id = $activity21->id;
						$calculation_equipment_activity21_5->save();

			$activity22 = new Activity;
	       	$activity22->activity_name = 'MW-O-H6W22-21/21/6';
	       	$activity22->priority = 22;
	       	$activity22->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity22->chapter_id = $chapter6->id;
	       	$activity22->tax_labor_id = $tax1->id;
	       	$activity22->tax_material_id = $tax1->id;
	       	$activity22->tax_equipment_id = $tax2->id;
	       	$activity22->part_id = 2;
	       	$activity22->part_type_id = 1;
	       	$activity22->detail_id = 1;
	       	$activity22->save();

				$calculation_labor_activity22 = new MoreLabor;
				$calculation_labor_activity22->rate = '11.00';
				$calculation_labor_activity22->amount = '15';
				$calculation_labor_activity22->activity_id = $activity22->id;
				$calculation_labor_activity22->save();

					$calculation_material_activity22_1 = new MoreMaterial;
					$calculation_material_activity22_1->material_name = 'MW-O-H6W22-MAT1';
					$calculation_material_activity22_1->unit = '211';
					$calculation_material_activity22_1->rate = '11';
					$calculation_material_activity22_1->amount = '1';
					$calculation_material_activity22_1->activity_id = $activity22->id;
					$calculation_material_activity22_1->save();

					$calculation_material_activity22_2 = new MoreMaterial;
					$calculation_material_activity22_2->material_name = 'MW-O-H6W22-MAT2';
					$calculation_material_activity22_2->unit = '212';
					$calculation_material_activity22_2->rate = '11';
					$calculation_material_activity22_2->amount = '2';
					$calculation_material_activity22_2->activity_id = $activity22->id;
					$calculation_material_activity22_2->save();

					$calculation_material_activity22_3 = new MoreMaterial;
					$calculation_material_activity22_3->material_name = 'MW-O-H6W22-MAT3';
					$calculation_material_activity22_3->unit = '213';
					$calculation_material_activity22_3->rate = '11';
					$calculation_material_activity22_3->amount = '3';
					$calculation_material_activity22_3->activity_id = $activity22->id;
					$calculation_material_activity22_3->save();

					$calculation_material_activity22_4 = new MoreMaterial;
					$calculation_material_activity22_4->material_name = 'MW-O-H6W22-MAT4';
					$calculation_material_activity22_4->unit = '214';
					$calculation_material_activity22_4->rate = '11';
					$calculation_material_activity22_4->amount = '4';
					$calculation_material_activity22_4->activity_id = $activity22->id;
					$calculation_material_activity22_4->save();

					$calculation_material_activity22_5 = new MoreMaterial;
					$calculation_material_activity22_5->material_name = 'MW-O-H6W22-MAT5';
					$calculation_material_activity22_5->unit = '215';
					$calculation_material_activity22_5->rate = '11';
					$calculation_material_activity22_5->amount = '5';
					$calculation_material_activity22_5->activity_id = $activity22->id;
					$calculation_material_activity22_5->save();
				
						$calculation_equipment_activity22_1 = new MoreEquipment;
						$calculation_equipment_activity22_1->equipment_name = 'MW-O-H6W22-EQU1';
						$calculation_equipment_activity22_1->unit = '216';
						$calculation_equipment_activity22_1->rate = '12';
						$calculation_equipment_activity22_1->amount = '1';
						$calculation_equipment_activity22_1->activity_id = $activity22->id;
						$calculation_equipment_activity22_1->save();
						
						$calculation_equipment_activity22_2 = new MoreEquipment;
						$calculation_equipment_activity22_2->equipment_name = 'MW-O-H6W22-EQU2';
						$calculation_equipment_activity22_2->unit = '217';
						$calculation_equipment_activity22_2->rate = '12';
						$calculation_equipment_activity22_2->amount = '2';
						$calculation_equipment_activity22_2->activity_id = $activity22->id;
						$calculation_equipment_activity22_2->save();

						$calculation_equipment_activity22_3 = new MoreEquipment;
						$calculation_equipment_activity22_3->equipment_name = 'MW-O-H6W22-EQU3';
						$calculation_equipment_activity22_3->unit = '218';
						$calculation_equipment_activity22_3->rate = '12';
						$calculation_equipment_activity22_3->amount = '3';
						$calculation_equipment_activity22_3->activity_id = $activity22->id;
						$calculation_equipment_activity22_3->save();
						
						$calculation_equipment_activity22_4 = new MoreEquipment;
						$calculation_equipment_activity22_4->equipment_name = 'MW-O-H6W22-EQU4';
						$calculation_equipment_activity22_4->unit = '219';
						$calculation_equipment_activity22_4->rate = '12';
						$calculation_equipment_activity22_4->amount = '4';
						$calculation_equipment_activity22_4->activity_id = $activity22->id;
						$calculation_equipment_activity22_4->save();

						$calculation_equipment_activity22_5 = new MoreEquipment;
						$calculation_equipment_activity22_5->equipment_name = 'MW-O-H6W22-EQU5';
						$calculation_equipment_activity22_5->unit = '220';
						$calculation_equipment_activity22_5->rate = '12';
						$calculation_equipment_activity22_5->amount = '5';
						$calculation_equipment_activity22_5->activity_id = $activity22->id;
						$calculation_equipment_activity22_5->save();

			$activity23 = new Activity;
	       	$activity23->activity_name = 'MW-O-H6W23-21/6/6';
	       	$activity23->priority = 23;
	       	$activity23->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity23->chapter_id = $chapter6->id;
	       	$activity23->tax_labor_id = $tax1->id;
	       	$activity23->tax_material_id = $tax2->id;
	       	$activity23->tax_equipment_id = $tax2->id;
	       	$activity23->part_id = 2;
	       	$activity23->part_type_id = 1;
	       	$activity23->detail_id = 1;
	       	$activity23->save();

				$calculation_labor_activity23 = new MoreLabor;
				$calculation_labor_activity23->rate = '11.00';
				$calculation_labor_activity23->amount = '16';
				$calculation_labor_activity23->activity_id = $activity23->id;
				$calculation_labor_activity23->save();

					$calculation_material_activity23_1 = new MoreMaterial;
					$calculation_material_activity23_1->material_name = 'MW-O-H6W23-MAT1';
					$calculation_material_activity23_1->unit = '221';
					$calculation_material_activity23_1->rate = '13';
					$calculation_material_activity23_1->amount = '1';
					$calculation_material_activity23_1->activity_id = $activity23->id;
					$calculation_material_activity23_1->save();

					$calculation_material_activity23_2 = new MoreMaterial;
					$calculation_material_activity23_2->material_name = 'MW-O-H6W23-MAT2';
					$calculation_material_activity23_2->unit = '222';
					$calculation_material_activity23_2->rate = '13';
					$calculation_material_activity23_2->amount = '2';
					$calculation_material_activity23_2->activity_id = $activity23->id;
					$calculation_material_activity23_2->save();

					$calculation_material_activity23_3 = new MoreMaterial;
					$calculation_material_activity23_3->material_name = 'MW-O-H6W23-MAT3';
					$calculation_material_activity23_3->unit = '223';
					$calculation_material_activity23_3->rate = '13';
					$calculation_material_activity23_3->amount = '3';
					$calculation_material_activity23_3->activity_id = $activity23->id;
					$calculation_material_activity23_3->save();

					$calculation_material_activity23_4 = new MoreMaterial;
					$calculation_material_activity23_4->material_name = 'MW-O-H6W23-MAT4';
					$calculation_material_activity23_4->unit = '224';
					$calculation_material_activity23_4->rate = '13';
					$calculation_material_activity23_4->amount = '4';
					$calculation_material_activity23_4->activity_id = $activity23->id;
					$calculation_material_activity23_4->save();

					$calculation_material_activity23_5 = new MoreMaterial;
					$calculation_material_activity23_5->material_name = 'MW-O-H6W23-MAT5';
					$calculation_material_activity23_5->unit = '225';
					$calculation_material_activity23_5->rate = '13';
					$calculation_material_activity23_5->amount = '5';
					$calculation_material_activity23_5->activity_id = $activity23->id;
					$calculation_material_activity23_5->save();
				
						$calculation_equipment_activity23_1 = new MoreEquipment;
						$calculation_equipment_activity23_1->equipment_name = 'MW-O-H6W23-EQU1';
						$calculation_equipment_activity23_1->unit = '226';
						$calculation_equipment_activity23_1->rate = '14';
						$calculation_equipment_activity23_1->amount = '1';
						$calculation_equipment_activity23_1->activity_id = $activity23->id;
						$calculation_equipment_activity23_1->save();
						
						$calculation_equipment_activity23_2 = new MoreEquipment;
						$calculation_equipment_activity23_2->equipment_name = 'MW-O-H6W23-EQU2';
						$calculation_equipment_activity23_2->unit = '227';
						$calculation_equipment_activity23_2->rate = '14';
						$calculation_equipment_activity23_2->amount = '2';
						$calculation_equipment_activity23_2->activity_id = $activity23->id;
						$calculation_equipment_activity23_2->save();

						$calculation_equipment_activity23_3 = new MoreEquipment;
						$calculation_equipment_activity23_3->equipment_name = 'MW-O-H6W23-EQU3';
						$calculation_equipment_activity23_3->unit = '228';
						$calculation_equipment_activity23_3->rate = '14';
						$calculation_equipment_activity23_3->amount = '3';
						$calculation_equipment_activity23_3->activity_id = $activity23->id;
						$calculation_equipment_activity23_3->save();
						
						$calculation_equipment_activity23_4 = new MoreEquipment;
						$calculation_equipment_activity23_4->equipment_name = 'MW-O-H6W23-EQU4';
						$calculation_equipment_activity23_4->unit = '229';
						$calculation_equipment_activity23_4->rate = '14';
						$calculation_equipment_activity23_4->amount = '4';
						$calculation_equipment_activity23_4->activity_id = $activity23->id;
						$calculation_equipment_activity23_4->save();

						$calculation_equipment_activity23_5 = new MoreEquipment;
						$calculation_equipment_activity23_5->equipment_name = 'MW-O-H6W23-EQU5';
						$calculation_equipment_activity23_5->unit = '230';
						$calculation_equipment_activity23_5->rate = '14';
						$calculation_equipment_activity23_5->amount = '5';
						$calculation_equipment_activity23_5->activity_id = $activity23->id;
						$calculation_equipment_activity23_5->save();

			$activity24= new Activity;
	       	$activity24->activity_name = 'MW-O-H6W24-6/6/6';
	       	$activity24->priority = 24;
	       	$activity24->note = 'Lorem Ipsum is slechts een proeftekst uit het drukkerij- en zetterijwezen. Lorem Ipsum is de standaard proeftekst in deze bedrijfstak sinds de 16e eeuw, toen een onbekende drukker een zethaak met letters nam en ze door elkaar husselde om een font-catalogus te maken. Het heeft niet alleen vijf eeuwen overleefd maar is ook, vrijwel onveranderd, overgenomen in elektronische letterzetting. Het is in de jaren 60 populair geworden met de introductie van Letraset vellen met Lorem Ipsum passages en meer recentelijk door desktop publishing software zoals Aldus PageMaker die versies van Lorem Ipsum bevatten.';
	       	$activity24->chapter_id = $chapter6->id;
	       	$activity24->tax_labor_id = $tax2->id;
	       	$activity24->tax_material_id = $tax2->id;
	       	$activity24->tax_equipment_id = $tax2->id;
	       	$activity24->part_id = 2;
	       	$activity24->part_type_id = 1;
	       	$activity24->detail_id = 1;
	       	$activity24->save();

	  			$calculation_labor_activity24 = new MoreLabor;
				$calculation_labor_activity24->rate = '11.00';
				$calculation_labor_activity24->amount = '17';
				$calculation_labor_activity24->activity_id = $activity24->id;
				$calculation_labor_activity24->save();

					$calculation_material_activity24_1 = new MoreMaterial;
					$calculation_material_activity24_1->material_name = 'MW-O-H6W24-MAT1';
					$calculation_material_activity24_1->unit = '231';
					$calculation_material_activity24_1->rate = '15';
					$calculation_material_activity24_1->amount = '1';
					$calculation_material_activity24_1->activity_id = $activity24->id;
					$calculation_material_activity24_1->save();

					$calculation_material_activity24_2 = new MoreMaterial;
					$calculation_material_activity24_2->material_name = 'MW-O-H6W24-MAT2';
					$calculation_material_activity24_2->unit = '232';
					$calculation_material_activity24_2->rate = '15';
					$calculation_material_activity24_2->amount = '2';
					$calculation_material_activity24_2->activity_id = $activity24->id;
					$calculation_material_activity24_2->save();

					$calculation_material_activity24_3 = new MoreMaterial;
					$calculation_material_activity24_3->material_name = 'MW-O-H6W24-MAT3';
					$calculation_material_activity24_3->unit = '233';
					$calculation_material_activity24_3->rate = '15';
					$calculation_material_activity24_3->amount = '3';
					$calculation_material_activity24_3->activity_id = $activity24->id;
					$calculation_material_activity24_3->save();

					$calculation_material_activity24_4 = new MoreMaterial;
					$calculation_material_activity24_4->material_name = 'MW-O-H6W24-MAT4';
					$calculation_material_activity24_4->unit = '234';
					$calculation_material_activity24_4->rate = '15';
					$calculation_material_activity24_4->amount = '4';
					$calculation_material_activity24_4->activity_id = $activity24->id;
					$calculation_material_activity24_4->save();

					$calculation_material_activity24_5 = new MoreMaterial;
					$calculation_material_activity24_5->material_name = 'MW-O-H6W24-MAT5';
					$calculation_material_activity24_5->unit = '235';
					$calculation_material_activity24_5->rate = '15';
					$calculation_material_activity24_5->amount = '5';
					$calculation_material_activity24_5->activity_id = $activity24->id;
					$calculation_material_activity24_5->save();
				
						$calculation_equipment_activity24_1 = new MoreEquipment;
						$calculation_equipment_activity24_1->equipment_name = 'MW-O-H6W24-EQU1';
						$calculation_equipment_activity24_1->unit = '236';
						$calculation_equipment_activity24_1->rate = '16';
						$calculation_equipment_activity24_1->amount = '1';
						$calculation_equipment_activity24_1->activity_id = $activity24->id;
						$calculation_equipment_activity24_1->save();
						
						$calculation_equipment_activity24_2 = new MoreEquipment;
						$calculation_equipment_activity24_2->equipment_name = 'MW-O-H6W24-EQU2';
						$calculation_equipment_activity24_2->unit = '237';
						$calculation_equipment_activity24_2->rate = '16';
						$calculation_equipment_activity24_2->amount = '2';
						$calculation_equipment_activity24_2->activity_id = $activity24->id;
						$calculation_equipment_activity24_2->save();

						$calculation_equipment_activity24_3 = new MoreEquipment;
						$calculation_equipment_activity24_3->equipment_name = 'MW-O-H6W24-EQU3';
						$calculation_equipment_activity24_3->unit = '238';
						$calculation_equipment_activity24_3->rate = '16';
						$calculation_equipment_activity24_3->amount = '3';
						$calculation_equipment_activity24_3->activity_id = $activity24->id;
						$calculation_equipment_activity24_3->save();
						
						$calculation_equipment_activity24_4 = new MoreEquipment;
						$calculation_equipment_activity24_4->equipment_name = 'MW-O-H6W24-EQU4';
						$calculation_equipment_activity24_4->unit = '239';
						$calculation_equipment_activity24_4->rate = '16';
						$calculation_equipment_activity24_4->amount = '4';
						$calculation_equipment_activity24_4->activity_id = $activity24->id;
						$calculation_equipment_activity24_4->save();

						$calculation_equipment_activity24_5 = new MoreEquipment;
						$calculation_equipment_activity24_5->equipment_name = 'MW-O-H6W24-EQU5';
						$calculation_equipment_activity24_5->unit = '240';
						$calculation_equipment_activity24_5->rate = '16';
						$calculation_equipment_activity24_5->amount = '5';
						$calculation_equipment_activity24_5->activity_id = $activity24->id;
						$calculation_equipment_activity24_5->save();


     }
  }

  ?>
  
