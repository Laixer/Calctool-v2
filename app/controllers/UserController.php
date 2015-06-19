<?php

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */
	public function getAll()
	{
		return View::make('base.user');
	}

	public function getNew()
	{
		return View::make('base.new_user');
	}

public function doNew()
	{
		$rules = array(
			/* General */
			'username' => array('required','numeric'),
			'secret' => array('required','numeric','max:10'),

			/* Contact */
			'contact_name' => array('required','max:50'),
			'contact_firstname' => array('required','max:30'),
			'mobile' => array('alpha_num','max:14'),
			'telephone' => array('alpha_num','max:14'),
			'email' => array('required','email','max:80'),
			'website' => array('url','max:180'),
			/* Adress */
			'street' => array('required','alpha','max:60'),
			'address_number' => array('required','alpha_num','max:5'),
			'zipcode' => array('required','size:6'),
			'city' => array('required','alpha_num','max:35'),
			//'province' => array('required','numeric'),
			//'country' => array('required','numeric'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			/* General */
			$user = new User;
			$user->username = Input::get('username');
			$user->secret = Hash::make(Input::get('secret'));
			$user->user_type = 1;//Input::get('user_type');
			$user->note = Input::get('note');
			$user->ip = $_SERVER['REMOTE_ADDR'];
			$user->promotion_code = md5(mt_rand());
			$user->api = md5(mt_rand());

			/* Contact */
			$user->lastname = Input::get('contact_name');
			$user->firstname = Input::get('contact_firstname');
			$user->mobile = Input::get('mobiler');
			$user->phone = Input::get('telephone');
			$user->email = Input::get('email');
			$user->website = Input::get('website');

			/* Adress */
			$user->address_street = Input::get('street');
			$user->address_number = Input::get('address_number');
			$user->address_postal = Input::get('zipcode');
			$user->address_city = Input::get('city');
			//$user->province_id = Input::get('province');
			//$user->country_id = Input::get('country');

			$user->save();


			return Redirect::back()->with('success', 1);
		}
	}


}
