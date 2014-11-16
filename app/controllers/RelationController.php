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
			'relationkind' => 'required|numeric',
			'company_type' => 'required|numeric',
			'company_name' => 'required|max:50',
			'street' => 'required|alpha|max:60',
			'address_number' => 'required|alpha_num|max:5',
			'zipcode' => 'required|size:6',
			'city' => 'required|alpha_num|max:35',
			'provance' => 'required|numeric',
			'country' => 'required|numeric',
			'kvk' => 'numeric',
			'btw' => 'alph_num',
			'iban' => 'alph_num',
			'debtor' => 'required|alph_num',
			'telephone_comp' => 'numeric',
			'email_comp' => 'required|email|max:80',
			'contact_name' => 'required|max:50',
			'website' => 'url|max:180',
			'contact_firstname' => 'required|max:30',
			'mobile' => 'numeric',
			'telephone' => 'numeric',
			'email' => 'required|email|max:80'
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$relation = new Relation;
			$relation->company_name = Input::get('company_name');
			$relation->address_street = Input::get('street');
			$relation->address_number = Input::get('address_number');
			$relation->address_postal = Input::get('zipcode');
			$relation->address_city = Input::get('city');
			$relation->kvk = Input::get('kvk');
			$relation->btw = Input::get('btw');
			$relation->debtor_code = Input::get('debtor');
			$relation->telephone_comp = Input::get('telephone_comp');
			$relation->email_comp = Input::get('email_comp');
			$relation->note = Input::get('note');
			$relation->website = Input::get('website');
			$relation->user_id = Auth::user()->id;
			$relation->type_id = Input::get('company_type');
			$relation->kind_id = Input::get('company_kind');
			$relation->province_id = Input::get('province');
			$relation->country_id = Input::get('country');

			$relation->save();

			$iban = new Iban;
			$iban->iban = Input::get('iban');
			$iban->iban_name = Input::get('company_name');
			$iban->user_id = Auth::user()->id;
			$iban->relation_id = Auth::user()->id;

			return Redirect::back()->with('success', 1);
		}
	}


	public function getAll()
	{
		return View::make('user.relation');
	}

}
