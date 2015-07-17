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
		$relation->kvk		 		= '1234567890';
		$relation->btw 				= 'NL1234567890B1';
		$relation->note 			= 'Dit is een demo relatie';
		$relation->email 			= 'demo@relatie.nl';
		$relation->mobile 			= '0611111111';
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
		$project->user_id 			= $userid;
		$project->province_id 		= $province->id;
		$project->country_id 		= $country->id;
		$project->type_id 			= $projecttype->id;
		$project->client_id 		= $relation->id;

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
