<?php

class AdminController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function getDashboard()
	{
		return View::make('admin.dashboard');
	}

	public function getAlert()
	{
		return View::make('admin.alert');
	}

	public function getPHPInfo()
	{
		return View::make('admin.phpinfo');
	}

	public function doNewAlert()
	{
		$rules = array(
			'title' => array('required'),
			'message' => array('required'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {

			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$alert = new SystemMessage;
			$alert->title = Input::get('title');
			$alert->content = Input::get('message');
			$alert->active = true;

			$alert->save();

			return Redirect::back()->with('success', 1);
		}

	}

	public function doDeleteAlert()
	{
		$rules = array(
			'id' => array('required'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			$alert = SystemMessage::find(Input::get('id'));
			$alert->active = false;

			$alert->save();

			return json_encode(['success' => 1]);
		}

	}

	public function doRefund()
	{
		$rules = array(
			'amount' => array('required'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {

			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$subtract = Input::get('amount');

			$mollie = new Mollie_API_Client;
			$mollie->setApiKey($_ENV['MOLLIE_API']);

			$payment = $mollie->payments->get(Route::Input('transcode'));

			if ($subtract > ($payment->amount-$payment->amountRefunded))
				return Redirect::back()->withErrors($validator)->withInput(Input::all());

			$mollie->payments->refund($payment, $subtract);

			$order = Payment::where('transaction','=',$payment->id)->first();
			$order->status = $payment->status;
			$order->amount = $payment->amountRefunded;
			$order->save();

			if ($payment->amountRefunded == $payment->amount) {
				$user = User::find($order->user_id);
				$expdate = $user->expiration_date;
				$user->expiration_date = date('Y-m-d', strtotime("-".$order->increment." month", strtotime($expdate)));

				$data = array('email' => $user->email, 'amount' => number_format($order->amount, 2,",","."), 'username' => $user->username);
				Mailgun::send('mail.refund', $data, function($message) use ($data) {
					$message->to($data['email'], strtolower(trim($data['username'])))->subject('Calctool - Terugstorting');
				});

				$user->save();
			}

			return Redirect::back()->with('success', 1);
		}

	}

	public function doNewUser()
	{
		$rules = array(
			/* General */
			'username' => array('required'),
			'secret' => array('required'),
			'type' => array('required'),

			/* Contact */
			'lastname' => array('max:50'),
			'firstname' => array('max:30'),
			'mobile' => array('numeric'),
			'telephone' => array('numeric'),
			'email' => array('required','email','max:80'),
			'website' => array('url','max:180'),

			/* Adress */
			'address_street' => array('alpha','max:60'),
			'address_number' => array('alpha_num','max:5'),
			'address_zipcode' => array('size:6'),
			'address_city' => array('alpha_num','max:35'),
			'province' => array('numeric'),
			'country' => array('numeric'),

			'expdate' => array('required'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			/* General */
			$user = new User;
			$user->username = strtolower(trim(Input::get('username')));
			$user->secret = Hash::make(Input::get('secret'));
			$user->user_type = Input::get('type');

			/* Server */
			$user->api = md5(mt_rand());
			$user->token = sha1($user->secret);
			$user->referral_key = md5(mt_rand());
			$user->ip = $_SERVER['REMOTE_ADDR'];

			/* Contact */
			if (Input::get('firstname'))
				$user->firstname = Input::get('firstname');
			else
				$user->firstname = $user->username;
			if (Input::get('lastname'))
				$user->lastname = Input::get('lastname');
			$user->email = Input::get('email');
			if (Input::get('mobile'))
				$user->mobile = Input::get('mobile');
			if (Input::get('telephone'))
				$user->phone = Input::get('telephone');
			if (Input::get('website'))
				$user->website = Input::get('website');

			/* Adress */
			if (Input::get('address_street'))
				$user->address_street = Input::get('address_street');
			if (Input::get('address_number'))
				$user->address_number = Input::get('address_number');
			if (Input::get('address_zipcode'))
				$user->address_postal = Input::get('address_zipcode');
			if (Input::get('address_city'))
				$user->address_city = Input::get('address_city');
			$user->province_id = Input::get('province');
			$user->country_id = Input::get('country');

			/* Overig */
			$user->expiration_date = Input::get('expdate');
			if (Input::get('note'))
				$user->note = Input::get('note');
			if (Input::get('confirmdate'))
				$user->confirmed_mail = Input::get('confirmdate');
			if (Input::get('bandate'))
				$user->banned = Input::get('bandate');
			if (Input::get('toggle-active'))
				$user->active = true;
			else
				$user->active = false;
			if (Input::get('toggle-api'))
				$user->api_access = true;
			else
				$user->api_access = false;

			$user->save();

			return Redirect::back()->with('success', 1);
		}
	}

	public function doUpdateUser()
	{
		$rules = array(
			/* General */
			'username' => array('required'),
			'email' => array('required','email','max:80'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			/* General */
			$user = User::find(Route::input('user_id'));
			if (Input::get('username'))
				$user->username = strtolower(trim(Input::get('username')));
			if (Input::get('secret'))
				$user->secret = Hash::make(Input::get('secret'));
			if (Input::get('type'))
				$user->user_type = Input::get('type');

			/* Contact */
			if (Input::get('firstname'))
				$user->firstname = Input::get('firstname');
			else
				$user->firstname = $user->username;
			if (Input::get('lastname'))
				$user->lastname = Input::get('lastname');
			if (Input::get('email'))
				$user->email = Input::get('email');
			if (Input::get('mobile'))
				$user->mobile = Input::get('mobile');
			if (Input::get('telephone'))
				$user->phone = Input::get('telephone');
			if (Input::get('website'))
				$user->website = Input::get('website');

			/* Adress */
			if (Input::get('address_street'))
				$user->address_street = Input::get('address_street');
			if (Input::get('address_number'))
				$user->address_number = Input::get('address_number');
			if (Input::get('address_zipcode'))
				$user->address_postal = Input::get('address_zipcode');
			if (Input::get('address_city'))
				$user->address_city = Input::get('address_city');
			$user->province_id = Input::get('province');
			$user->country_id = Input::get('country');

			/* Overig */
			if (Input::get('expdate'))
				$user->expiration_date = Input::get('expdate');
			if (Input::get('note'))
				$user->note = Input::get('note');
			if (Input::get('confirmdate'))
				$user->confirmed_mail = Input::get('confirmdate');
			if (Input::get('bandate'))
				$user->banned = Input::get('bandate');
			else
				$user->banned = null;
			if (Input::get('toggle-active'))
				$user->active = true;
			else
				$user->active = false;
			if (Input::get('toggle-api'))
				$user->api_access = true;
			else
				$user->api_access = false;

			$user->save();

			return Redirect::back()->with('success', 1);
		}
	}

	public function getSwitchSession()
	{
		if (!Auth::user()->isAdmin())
			return Redirect::back();

		Auth::logout();
		Auth::loginUsingId(Route::input('user_id'));

		return Redirect::to('/');

	}

	public function doDeleteResource()
	{
		$rules = array(
			/* General */
			'id' => array('required'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return json_encode(['success' => 0, 'message' => $messages]);
		} else {

			/* General */
			$resource = Resource::find(Input::get('id'));
			$resource->unlinked = true;

			File::delete($resource->file_location);

			$resource->save();

			return json_encode(['success' => 1]);
		}
	}


	public function doTruncateLog()
	{
		if (!Auth::user()->isAdmin())
			return Redirect::back();

		File::put('../app/storage/logs/laravel.log', '');

		return Redirect::back()->with('success', 1);
	}

	public function getDemoProject()
	{
		DemoProjectTemplate::setup(Route::input('user_id'));

		return Redirect::back()->with('success', 1);
	}
}
