<?php

class QuickstartController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */

	public function getNewContact()
	{
		return View::make('user.new_contact');
	}

	public function getMyCompany()
	{
		return View::make('user.edit_mycompany');
	}

	public function doNewMyCompanyQuickstart()
	{
		$rules = array(
			/* Company */
			'company_type' => array('required_if:relationkind,zakelijk','numeric'),
			'company_name' => array('required_if:relationkind,zakelijk','max:50'),
			'kvk' => array('numeric','min:8'),
			'btw' => array('alpha_num','min:14'),
			'street' => array('required','alpha','max:60'),
			'address_number' => array('required','alpha_num','max:5'),
			'zipcode' => array('required','size:6'),
			'city' => array('required','alpha_num','max:35'),
			'province' => array('required','numeric'),
			'country' => array('required','numeric'),
			/* Contacty */
			'contact_name' => array('required','max:50'),
			'contact_firstname' => array('required','max:30'),
			'email' => array('required','email','max:80'),
			'contactfunction' => array('required','numeric'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			/* General */
			$relation = new Relation;
			$relation->user_id = Auth::id();
			$relation->note = Input::get('note');
			$relation->debtor_code = mt_rand(1000000, 9999999);

			/* Company */
			$relation->kind_id = RelationKind::where('kind_name','=','zakelijk')->first()->id;
			$relation->company_name = Input::get('company_name');
			$relation->type_id = Input::get('company_type');
			$relation->kvk = Input::get('kvk');
			$relation->btw = Input::get('btw');
			$relation->email = Input::get('email_comp');

			/* Adress */
			$relation->address_street = Input::get('street');
			$relation->address_number = Input::get('address_number');
			$relation->address_postal = Input::get('zipcode');
			$relation->address_city = Input::get('city');
			$relation->province_id = Input::get('province');
			$relation->country_id = Input::get('country');

			$relation->save();

			$contact = new Contact;
			$contact->firstname = Input::get('contact_firstname');
			$contact->lastname = Input::get('contact_name');
			$contact->mobile = Input::get('mobile');
			$contact->relation_id = $relation->id;
			$contact->function_id = Input::get('contactfunction');

			$contact->save();

			$user = Auth::user();
			$user->self_id = $relation->id;
			$user->save();

			return Redirect::to('/')->with('success', 1)->withCookie(Cookie::forget('nstep'));

		}
	}

}
