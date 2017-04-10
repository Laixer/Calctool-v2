<?php

use \CalculatieTool\Models\Province;
use \CalculatieTool\Models\Country;
use \CalculatieTool\Models\RelationType;
use \CalculatieTool\Models\RelationKind;
use \CalculatieTool\Models\ContactFunction;
use \CalculatieTool\Models\Relation;
use \CalculatieTool\Models\Contact;

/*
 * Static Models Only
 * Template for voorbeeldproject
 */
class VoorbeeldRelatieTemplate {

	public static function setup($userid)
	{
		$province = Province::where('province_name','=','zuid-holland')->first();
		$country = Country::where('country_name','=','nederland')->first();
		$relationtype = RelationType::where('type_name','=','adviesbureau')->first();
		$relationkind = RelationKind::where('kind_name','=','zakelijk')->first();
		$contact_function = ContactFunction::where('function_name','=','voorzitter')->first();

		$relation = new Relation;
		$relation->company_name		= 'Voorbeeldrelatie';
		$relation->address_street	= 'Demostraat';
		$relation->address_number	= '1';
		$relation->address_postal	= '1234AB';
		$relation->address_city		= 'Voorbeeldstad';
		$relation->debtor_code 		= 'VRBD123';
		$relation->kvk		 		= '12345678';
		$relation->btw 				= 'NL123456789B01';
		$relation->note 			= 'Dit is een voorbeeldrelatie';
		$relation->email 			= 'voorbeeld@calculatietool.com';
		$relation->phone 			= '0101234567';
		$relation->website 			= 'http://www.calculatietool.com';
		$relation->user_id 			= $userid;
		$relation->type_id 			= $relationtype->id;
		$relation->kind_id 			= $relationkind->id;
		$relation->province_id 		= $province->id;
		$relation->country_id 		= $country->id;
		$relation->save();

		$contact = new Contact;
		$contact->firstname 		= 'Jan';
		$contact->lastname 			= 'Janssen';
		$contact->email 			= 'voorbeeld@calculatietool.com';
		$contact->mobile 			= '0622222222';
		$contact->phone 			= '0103333333';
		$contact->note 				= 'Voorbeeld contactpersoon van relatie';
		$contact->relation_id 		= $relation->id;
		$contact->function_id 		= $contact_function->id;
		$contact->gender	 		= 'M';
		$contact->save();
     }
  }

  ?>
  
