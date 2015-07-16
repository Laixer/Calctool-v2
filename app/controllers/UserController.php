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
			$mollie->setApiKey("live_dUapTi8xt2DujzS6WkPyGt8T7UpqY3");

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
					$amount = 80.85;
					$description = 'Verleng met 4 maanden';
					$increment_months = 4;
					break;
				case 6:
					$amount = 152.75;
					$description = 'Verleng met 6 maanden';
					$increment_months = 6;
					break;
				case 12:
					$amount = 287.52;
					$description = 'Verleng met 12 maanden';
					$increment_months = 12;
					break;
				case 13:
					$amount = 1.95;
					$description = 'Kaasbetaling';
					$increment_months = 1;
					break;
				default:
					$errors = new MessageBag(['status' => ['Geen geldige optie']]);
					return Redirect::to('myaccount')->withErrors($errors);
			}

			$token = sha1(mt_rand().time());

			$payment = $mollie->payments->create(array(
				"amount"      => $amount,
				"description" => $description,
				"webhookUrl" => url('payment/webhook/'),
				"redirectUrl" => url('payment/order/'.$token),
				"metadata"    => array(
					"token" => $token,
					"uid" => Auth::id(),
					"incr" => $increment_months
				),
			));

			$order = new Payment;
			$order->transaction = $payment->id;
			$order->token = $token;
			$order->amount = $amount;
			$order->status = $payment->status;
			$order->increment = $increment_months;
			$order->description = $description;
			$order->method = '';
			$order->user_id = Auth::user()->id;

			$order->save();

			return Redirect::to($payment->links->paymentUrl);
		}
	}

	public function doPaymentUpdate()
	{
		$order = Payment::where('transaction','=',Input::get('id'))->where('status','=','open')->first();
		if (!$order) {
			return;
		}

		$mollie = new Mollie_API_Client;
		$mollie->setApiKey("live_dUapTi8xt2DujzS6WkPyGt8T7UpqY3");

		$payment = $mollie->payments->get($order->transaction);
		if ($payment->metadata->token != $order->token)
			return;

		if ($payment->metadata->uid != $order->user_id)
			return;

		$order->status = $payment->status;
		$order->method = $payment->method;
		$order->save();

		if ($payment->isPaid()) {
			$user = User::find($order->user_id);
			$expdate = $user->expiration_date;
			$user->expiration_date = date('Y-m-d', strtotime("+".$order->increment." month", strtotime($expdate)));

			$data = array('email' => $user->email, 'amount' => number_format($order->amount, 2,",","."), 'expdate' => date('j F Y', strtotime($user->expiration_date)), 'username' => $user->username);
			Mailgun::send('mail.paid', $data, function($message) use ($data) {
				$message->to($data['email'], strtolower(trim($data['username'])))->subject('Calctool - Abonement verlengt');
			});

			$user->save();
		}
		return json_encode(['success' => 1]);
	}

	public function getPaymentFinish()
	{
		$order = Payment::where('token','=',Route::Input('token'))->first();
		if (!$order) {
			$errors = new MessageBag(['status' => ['Transactie niet geldig']]);
			return Redirect::to('myaccount')->withErrors($errors);
		}

		$mollie = new Mollie_API_Client;
		$mollie->setApiKey("live_dUapTi8xt2DujzS6WkPyGt8T7UpqY3");

		$payment = $mollie->payments->get($order->transaction);
		if ($payment->isPaid()) {
			return Redirect::to('myaccount')->with('success','Bedankt voor je knake');
		} else if ($payment->isOpen() || $payment->isPending()) {
			return Redirect::to('myaccount')->with('success','Betaling is nog niet bevestigd, dit kan enkele dagen duren');
		} else if ($payment->isCancelled()) {
			$order->status = $payment->status;
			$order->save();
			$errors = new MessageBag(['status' => ['Betaling is afgebroken']]);
			return Redirect::to('myaccount')->withErrors($errors);
		} else if ($payment->isExpired()) {
			$order->status = $payment->status;
			$order->save();
			$errors = new MessageBag(['status' => ['Betaling is verlopen']]);
			return Redirect::to('myaccount')->withErrors($errors);
		}
		$errors = new MessageBag(['status' => ['Transactie niet afgerond ('.$payment->status.')']]);
		return Redirect::to('myaccount')->withErrors($errors);
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

			$data = array('email' => Auth::user()->email, 'username' => Auth::user()->username);
			Mailgun::send('mail.password_update', $data, function($message) use ($data) {
				$message->to($data['email'], strtolower(trim($data['username'])))->subject('Calctool - Wachtwoord aangepast');
			});

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

			$data = array('email' => Auth::user()->email, 'username' => Auth::user()->username);
			Mailgun::send('mail.iban_update', $data, function($message) use ($data) {
				$message->to($data['email'], strtolower(trim($data['username'])))->subject('Calctool - Betaalgegevens aangepast');
			});

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

	public function doUpdatePreferences()
	{
		print_r(Input::all());

		$user = Auth::user();
		if (Input::get('pref_mailings_optin'))
			$user->pref_mailings_optin = true;
		else
			$user->pref_mailings_optin = false;

		$user->pref_hourrate_calc = Input::get('pref_hourrate_calc');
		$user->pref_hourrate_more = Input::get('pref_hourrate_more');
		$user->pref_profit_calc_contr_mat = Input::get('pref_profit_calc_contr_mat');
		$user->pref_profit_calc_contr_equip = Input::get('pref_profit_calc_contr_equip');
		$user->pref_profit_calc_subcontr_mat = Input::get('pref_profit_calc_subcontr_mat');
		$user->pref_profit_calc_subcontr_equip = Input::get('pref_profit_calc_subcontr_equip');
		$user->pref_profit_more_contr_mat = Input::get('pref_profit_more_contr_mat');
		$user->pref_profit_more_contr_equip = Input::get('pref_profit_more_contr_equip');
		$user->pref_profit_more_subcontr_mat = Input::get('pref_profit_more_subcontr_mat');
		$user->pref_profit_more_subcontr_equip = Input::get('pref_profit_more_subcontr_equip');

		$user->pref_email_offer = Input::get('pref_email_offer');
		$user->pref_offer_description = Input::get('pref_offer_description');
		$user->pref_closure_offer = Input::get('pref_closure_offer');
		$user->pref_email_invoice = Input::get('pref_email_invoice');
		$user->pref_invoice_description = Input::get('pref_invoice_description');
		$user->pref_invoice_closure = Input::get('pref_invoice_closure');
		$user->pref_email_invoice_first_reminder = Input::get('pref_email_invoice_first_reminder');
		$user->pref_email_invoice_last_reminder = Input::get('pref_email_invoice_last_reminder');
		$user->pref_email_invoice_first_demand = Input::get('pref_email_invoice_first_demand');
		$user->pref_email_invoice_last_demand = Input::get('pref_email_invoice_last_demand');

		$user->offernumber_prefix = Input::get('offernumber_prefix');
		$user->offer_counter = Input::get('offer_counter');
		$user->invoicenumber_prefix = Input::get('invoicenumber_prefix');
		$user->invoice_counter = Input::get('invoice_counter');
		$user->administration_cost = Input::get('administration_cost');

		$user->save();

		return Redirect::back()->with('success', 1);
	}
}
