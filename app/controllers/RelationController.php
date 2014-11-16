<?php

class RelationController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */

	public function getNew()
	{
		return View::make('user.new_relation');
	}

	public function doNew()
	{
		$rules = array(
			/* General */
			'relationkind' => 'required|numeric',
			'debtor' => 'required|alpha_num|max:10',
			/* Company */
			'company_type' => 'required_if:relationkind,zakelijk|numeric',
			'company_name' => 'required_if:relationkind,zakelijk|max:50',
			'kvk' => 'numeric|max:12',
			'btw' => 'alpha_num|max:14',
			'telephone_comp' => 'numeric|max:14',
			'email_comp' => 'required_if:relationkind,zakelijk|email|max:80',
			'website' => 'url|max:180',
			/* Contact */
			'contact_name' => 'required|max:50',
			'contact_firstname' => 'required|max:30',
			'mobile' => 'numeric|max:14',
			'telephone' => 'numeric|max:14',
			'email' => 'required|email|max:80',
			'contactfunction' => 'required|numeric',
			/* Adress */
			'street' => 'required|alpha|max:60',
			'address_number' => 'required|alpha_num|max:5',
			'zipcode' => 'required|size:6',
			'city' => 'required|alpha_num|max:35',
			'province' => 'required|numeric',
			'country' => 'required|numeric',
			/* Payment */
			'iban' => 'alpha_num',
			'iban_name' => 'required|max:50'

		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			/* General */
			$relation = new Relation;
			$relation->user_id = Auth::user()->id;
			$relation->note = Input::get('note');
			$relation->kind_id = Input::get('relationkind');
			$relation->debtor_code = Input::get('debtor');

			/* Company */
			$relation_kind = RelationKind::where('id','=',$relation->kind_id)->firstOrFail();
			if ($relation_kind->kind_name == "zakelijk") {
				$relation->company_name = Input::get('company_name');
				$relation->type_id = Input::get('company_type');
				$relation->kvk = Input::get('kvk');
				$relation->btw = Input::get('btw');
				$relation->phone = Input::get('telephone_comp');
				$relation->email = Input::get('email_comp');
				$relation->website = Input::get('website');
			}

			/* Adress */
			$relation->address_street = Input::get('street');
			$relation->address_number = Input::get('address_number');
			$relation->address_postal = Input::get('zipcode');
			$relation->address_city = Input::get('city');
			$relation->province_id = Input::get('province');
			$relation->country_id = Input::get('country');

			$relation->save();

			/* Contact */
			$contact = new Contact;
			$contact->firstname = Input::get('contact_name');
			$contact->lastname = Input::get('contact_firstname');
			$contact->mobile = Input::get('mobile');
			$contact->phone = Input::get('telephone');
			$contact->email = Input::get('email');
			$contact->note = Input::get('note');
			$contact->relation_id = $relation->id;
			$contact->function_id = Input::get('contactfunction');

			$contact->save();

			/*Betalingsgevens*/
			$iban = new Iban;
			$iban->iban = Input::get('iban');
			$iban->iban_name = Input::get('company_name');
			$iban->relation_id = $relation->id;

			$iban->save();

			return Redirect::back()->with('success', 1);
		}
	}


	public function getAll()
	{
		return View::make('user.relation');
	}

}
