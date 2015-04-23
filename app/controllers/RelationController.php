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

	public function getEdit()
	{
		return View::make('user.edit_relation');
	}

	public function getNewContact() {
		return View::make('user.new_contact');
	}

	public function getEditContact() {
		return View::make('user.edit_contact');
	}

	public function doUpdate()
	{
		$rules = array(
			/* General */
			'id' => array('required','integer'),
			'relationkind' => array('required','numeric'),
			'debtor' => array('required','alpha_num','max:10'),
			/* Company */
			'company_type' => array('required_if:relationkind,zakelijk','numeric'),
			'company_name' => array('required_if:relationkind,zakelijk','max:50'),
			'kvk' => array('numeric','min:12'),
			'btw' => array('alpha_num','min:14'),
			'telephone_comp' => array('alpha_num','max:12'),
			'email_comp' => array('required_if:relationkind,zakelijk','email','max:80'),
			'website' => array('url','max:180'),
			/* Adress */
			'street' => array('required','alpha','max:60'),
			'address_number' => array('required','alpha_num','max:5'),
			'zipcode' => array('required','size:6'),
			'city' => array('required','alpha_num','max:35'),
			'province' => array('required','numeric'),
			'country' => array('required','numeric')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			/* General */
			$relation = Relation::find(Input::get('id'));
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

			return Redirect::back()->with('success', 1);
		}
	}

	public function doUpdateContact() {
		$rules = array(
			/* General */
			'id' => array('required','integer'),
			'contact_name' => array('required','max:50'),
			'contact_firstname' => array('required','max:30'),
			'mobile' => array('alpha_num','max:14'),
			'telephone' => array('alpha_num','max:14'),
			'email' => array('required','email','max:80'),
			'contactfunction' => array('required','numeric'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {
			$contact = Contact::find(Input::get('id'));
			$contact->firstname = Input::get('contact_name');
			$contact->lastname = Input::get('contact_firstname');
			$contact->mobile = Input::get('mobile');
			$contact->phone = Input::get('telephone');
			$contact->email = Input::get('email');
			$contact->note = Input::get('note');
			$contact->function_id = Input::get('contactfunction');

			$contact->save();

			return Redirect::back()->with('success', 1);
		}
	}

	public function doNew()
	{
		$rules = array(
			/* General */
			'relationkind' => array('required','numeric'),
			'debtor' => array('required','alpha_num','max:10'),
			/* Company */
			'company_type' => array('required_if:relationkind,zakelijk','numeric'),
			'company_name' => array('required_if:relationkind,zakelijk','max:50'),
			'kvk' => array('numeric','min:12'),
			'btw' => array('alpha_num','min:14'),
			'telephone_comp' => array('alpha_num','max:12'),
			'email_comp' => array('required_if:relationkind,zakelijk','email','max:80'),
			'website' => array('url','max:180'),
			/* Contact */
			'contact_name' => array('required','max:50'),
			'contact_firstname' => array('required','max:30'),
			'mobile' => array('alpha_num','max:14'),
			'telephone' => array('alpha_num','max:14'),
			'email' => array('required','email','max:80'),
			'contactfunction' => array('required','numeric'),
			/* Adress */
			'street' => array('required','alpha','max:60'),
			'address_number' => array('required','alpha_num','max:5'),
			'zipcode' => array('required','size:6'),
			'city' => array('required','alpha_num','max:35'),
			'province' => array('required','numeric'),
			'country' => array('required','numeric'),
			/* Payment */
			'iban' => array('alpha_num'),
			'iban_name' => array('required','max:50')
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

	public function doNewContact()
	{
		$rules = array(
			/* Contact */
			'id' => array('required','integer'),
			'contact_name' => array('required','max:50'),
			'contact_firstname' => array('required','max:30'),
			'mobile' => array('alpha_num','max:14'),
			'telephone' => array('alpha_num','max:14'),
			'email' => array('required','email','max:80'),
			'contactfunction' => array('required','numeric'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {
			$contact = new Contact;
			$contact->firstname = Input::get('contact_name');
			$contact->lastname = Input::get('contact_firstname');
			$contact->mobile = Input::get('mobile');
			$contact->phone = Input::get('telephone');
			$contact->email = Input::get('email');
			$contact->note = Input::get('note');
			$contact->relation_id = Input::get('id');
			$contact->function_id = Input::get('contactfunction');

			$contact->save();

			return Redirect::back()->with('success', 1);
		}
	}

	public function getAll()
	{
		return View::make('user.relation');
	}

}
