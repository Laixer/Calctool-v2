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
		$relation->address_street	= 'Dorpsstraat';
		$relation->address_number	= '7';
		$relation->address_postal	= '3184EA';
		$relation->address_city		= 'Schiedam';
		$relation->debtor_code 		= 'CPTEA72';
		$relation->note 			= 'Dit is een voorbeeld relatie';
		$relation->user_id 			= $userid;
		$relation->type_id 			= $relationtype->id;
		$relation->kind_id 			= $relationkind->id;
		$relation->province_id 		= $province->id;
		$relation->country_id 		= $country->id;

		$relation->save();

		$project = new Project;
		$project->project_name 		= 'Demo project';
		$project->address_street 	= 'Coolsingel';
		$project->address_number 	= '40A';
		$project->address_postal 	= '3174EA';
		$project->address_city 		= 'Rotterdam';
		$project->note 				= 'Dit is een voorbeeld project';
		$project->hour_rate 		= 25;
		$project->user_id 			= $userid;
		$project->province_id 		= $province->id;
		$project->country_id 		= $country->id;
		$project->type_id 			= $projecttype->id;
		$project->client_id 		= $relation->id;

		$project->save();

		$contact = new Contact;
		$contact->firstname 		= 'Stoffer';
		$contact->lastname 			= 'Blik';
		$contact->email 			= 'stoffer@blik.nl';
		$contact->note 				= 'Voorbeeld contactpersoon';
		$contact->relation_id 		= $relation->id;
		$contact->function_id 		= $contact_function->id;

		$contact->save();
	}
 }
