<?php

use Illuminate\Support\MessageBag;

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */
	public function getAll()
	{
		return View::make('admin.user');
	}

	public function getNew()
	{
		return View::make('admin.new_user');
	}

	public function getMyAccount()
	{
		return View::make('user.myaccount');
	}

	public function getPayment()
	{
		return View::make('user.payment');
	}

	public function doPayment()
	{
		$rules = array(
			'payoption' => array('required'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$mollie = new Mollie_API_Client;
			$mollie->setApiKey("test_GgXY6mWGW56AAfgC6NDBDXf4bCMfpz");

			$amount = 0;
			$description = 'None';
			$increment_months = 0;
			switch (Input::get('payoption')) {
				case 1:
					$amount = 29.95;
					$description = 'Verleng met een maand';
					$increment_months = 1;
					break;
				case 4:
					$amount = 82.95;
					$description = 'Verleng met 4 maanden';
					$increment_months = 4;
					break;
				case 6:
					$amount = 152.95;
					$description = 'Verleng met 6 maanden';
					$increment_months = 6;
					break;
				case 12:
					$amount = 287.95;
					$description = 'Verleng met 12 maanden';
					$increment_months = 12;
					break;
				case 13:
					$amount = 15000.95;
					$description = 'Goudse Kaas';
					$increment_months = (12*40);
					break;

				default:
					$errors = new MessageBag(['status' => ['Geen geldige optie']]);
					return Redirect::to('myaccount')->withErrors($errors);
			}

			$token = sha1(mt_rand().time());

			$payment = $mollie->payments->create(array(
				"amount"      => $amount,
				"description" => $description,
				"redirectUrl" => url('payment/order/'.$token),
			));

			$order = new Order;
			$order->transaction = $payment->id;
			$order->token = $token;
			$order->amount = $amount;
			$order->status = 'PENDING';
			$order->increment = $increment_months;
			$order->description = $description;
			$order->user_id = Auth::user()->id;

			$order->save();

			return Redirect::to($payment->links->paymentUrl);
		}
	}

	public function getPaymentFinish()
	{
		$order = Order::where('token','=',Route::Input('token'))->where('status','=','PENDING')->first();
		if (!$order) {
			$errors = new MessageBag(['status' => ['Transactie niet geldig']]);
			return Redirect::to('myaccount')->withErrors($errors);
		}

		$mollie = new Mollie_API_Client;
		$mollie->setApiKey("test_GgXY6mWGW56AAfgC6NDBDXf4bCMfpz");

		$payment = $mollie->payments->get($order->transaction);
		if ($payment->isPaid()) {
			$order->status = 'COMPLETE';
			$order->save();
		} else {
			$order->status = 'CANCELED';
			$order->save();
			$errors = new MessageBag(['status' => ['Transactie niet afgerond']]);
			return Redirect::to('myaccount')->withErrors($errors);
		}
		$user = Auth::user();
		$expdate = $user->expiration_date;
		//echo 'new date ' . date('Y-m-d', strtotime("+".$order->increment." month", strtotime($expdate)));
		$user->expiration_date = date('Y-m-d', strtotime("+".$order->increment." month", strtotime($expdate)));

		$user->save();

		return Redirect::to('myaccount')->with('success','Betaald');
	}

	public function doUpdateSecurity()
	{
		$rules = array(
			'secret' => array('confirmed','min:5'),
			'secret_confirmation' => array('min:5'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$user = Auth::user();
			if (Input::get('secret'))
				$user->secret = Hash::make(Input::get('secret'));
			if (Input::get('toggle-api'))
				$user->api_access = true;
			else
				$user->api_access = false;

			$user->save();

			return Redirect::back()->with('success', 1);
		}
	}

	public function doMyAccountUser()
	{
		$rules = array(
			'lastname' => array('required','max:50'),
			'firstname' => array('required','max:30'),
			'mobile' => array('alpha_num','max:14'),
			'telephone' => array('alpha_num','max:14'),
			'email' => array('required','email','max:80'),
			'website' => array('url','max:180'),
			'address_street' => array('required','alpha','max:60'),
			'address_number' => array('required','alpha_num','max:5'),
			'address_zipcode' => array('required','size:6'),
			'address_city' => array('required','alpha_num','max:35'),
			'province' => array('required','numeric'),
			'country' => array('required','numeric'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			/* General */
			$user = Auth::user();

			/* Contact */
			$user->firstname = Input::get('firstname');
			$user->lastname = Input::get('lastname');
			$user->email = Input::get('email');
			$user->mobile = Input::get('mobile');
			$user->phone = Input::get('phone');
			$user->website = Input::get('website');

			/* Adress */
			$user->address_street = Input::get('address_street');
			$user->address_number = Input::get('address_number');
			$user->address_postal = Input::get('address_zipcode');
			$user->address_city = Input::get('address_city');
			$user->province_id = Input::get('province');
			$user->country_id = Input::get('country');

			$user->save();

			return Redirect::back()->with('success', 1);
		}
	}

	public function doNew()
	{
		$rules = array(
			/* General */
			'username' => array('required'),
			'secret' => array('required'),

			/* Contact */
			'lastname' => array('required','max:50'),
			'firstname' => array('required','max:30'),
			'mobile' => array('alpha_num','max:14'),
			'telephone' => array('alpha_num','max:14'),
			'email' => array('required','email','max:80'),
			'website' => array('url','max:180'),

			/* Adress */
			'address_street' => array('required','alpha','max:60'),
			'address_number' => array('required','alpha_num','max:5'),
			'address_zipcode' => array('required','size:6'),
			'address_city' => array('required','alpha_num','max:35'),
			'province' => array('required','numeric'),
			'country' => array('required','numeric'),

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

			/* Contact */
			$user->firstname = Input::get('firstname');
			$user->lastname = Input::get('lastname');
			$user->email = Input::get('email');
			$user->mobile = Input::get('mobiler');
			$user->phone = Input::get('telephone');
			$user->website = Input::get('website');

			/* Adress */
			$user->address_street = Input::get('address_street');
			$user->address_number = Input::get('address_number');
			$user->address_postal = Input::get('address_zipcode');
			$user->address_city = Input::get('address_city');
			$user->province_id = Input::get('province');
			$user->country_id = Input::get('country');

			/* Overig */
			$user->note = Input::get('note');

			/* System */
			$user->api = md5(mt_rand());
			$user->ip = $_SERVER['REMOTE_ADDR'];
			$user->referral_key = md5(mt_rand());

			$user->save();

			return Redirect::back()->with('success', 1);
		}
	}

	public function doUpdateIban()
	{
		$rules = array(
			'id' => array('required','integer'),
			'iban' => array('alpha_num'),
			'iban_name' => array('required','max:50')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {
			$iban = Iban::find(Input::get('id'));
			$iban->iban = Input::get('iban');
			$iban->iban_name = Input::get('iban_name');

			$iban->save();

			return Redirect::back()->with('success', 1);
		}
	}

	public function doNewIban()
	{
		$rules = array(
			'iban' => array('alpha_num'),
			'iban_name' => array('required','max:50')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {
			$iban = new Iban;
			$iban->iban = Input::get('iban');
			$iban->iban_name = Input::get('iban_name');
			$iban->user_id = Auth::user()->id;

			$iban->save();

			return Redirect::back()->with('success', 1);
		}
	}
}
