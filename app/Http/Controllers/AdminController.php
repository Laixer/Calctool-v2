<?php

namespace Calctool\Http\Controllers;

use Illuminate\Http\Request;

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
		return view('admin.dashboard');
	}

	public function getAlert()
	{
		return view('admin.alert');
	}

	public function getPHPInfo()
	{
		return view('admin.phpinfo');
	}

	public function doNewAlert()
	{
		$rules = array(
			'level' => array('required'),
			'message' => array('required'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {

			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$alert = new SystemMessage;
			$alert->level = $request->input('level');
			$alert->content = $request->input('message');
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

			$alert = SystemMessage::find($request->input('id'));
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

			$subtract = $request->input('amount');

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
			'username' => array('required','unique:user_account'),
			'secret' => array('required'),
			'type' => array('required'),

			/* Contact */
			'lastname' => array('max:50'),
			'firstname' => array('max:30'),
			'mobile' => array('numeric'),
			'telephone' => array('numeric'),
			'email' => array('required','email','max:80','unique:user_account'),
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
			$user->username = strtolower(trim($request->input('username')));
			$user->secret = Hash::make($request->input('secret'));
			$user->user_type = $request->input('type');

			/* Server */
			$user->api = md5(mt_rand());
			$user->token = sha1($user->secret);
			$user->referral_key = md5(mt_rand());
			$user->ip = $_SERVER['REMOTE_ADDR'];

			/* Contact */
			if ($request->input('firstname'))
				$user->firstname = $request->input('firstname');
			else
				$user->firstname = $user->username;
			if ($request->input('lastname'))
				$user->lastname = $request->input('lastname');
			$user->email = $request->input('email');
			if ($request->input('mobile'))
				$user->mobile = $request->input('mobile');
			if ($request->input('telephone'))
				$user->phone = $request->input('telephone');
			if ($request->input('website'))
				$user->website = $request->input('website');

			/* Adress */
			if ($request->input('address_street'))
				$user->address_street = $request->input('address_street');
			if ($request->input('address_number'))
				$user->address_number = $request->input('address_number');
			if ($request->input('address_zipcode'))
				$user->address_postal = $request->input('address_zipcode');
			if ($request->input('address_city'))
				$user->address_city = $request->input('address_city');
			$user->province_id = $request->input('province');
			$user->country_id = $request->input('country');

			/* Overig */
			$user->expiration_date = $request->input('expdate');
			if ($request->input('note'))
				$user->note = $request->input('note');
			if ($request->input('notepad'))
				$user->notepad = $request->input('notepad');
			if ($request->input('confirmdate'))
				$user->confirmed_mail = $request->input('confirmdate');
			if ($request->input('bandate'))
				$user->banned = $request->input('bandate');
			if ($request->input('toggle-active'))
				$user->active = true;
			else
				$user->active = false;
			if ($request->input('toggle-api'))
				$user->api_access = true;
			else
				$user->api_access = false;
			if (!$request->input('gender') || $request->input('gender') == '-1')
				$user->gender = NULL;
			else
				$user->gender = $request->input('gender');

			$user->save();

			return back()->with('success', 1);
		}
	}

	public function doUpdateUser(Request $request, $user_id)
	{
		$this->validate($request, [
			'username' => array('required'),
			'email' => array('required','email','max:80'),
		]);

			/* General */
			$user = \Calctool\Models\User::find($user_id);
			if ($request->input('username'))
				$user->username = strtolower(trim($request->input('username')));
			if ($request->input('secret'))
				$user->secret = Hash::make($request->input('secret'));
			if ($request->input('type'))
				$user->user_type = $request->input('type');

			/* Contact */
			if ($request->input('firstname'))
				$user->firstname = $request->input('firstname');
			else
				$user->firstname = $user->username;
			if ($request->input('lastname'))
				$user->lastname = $request->input('lastname');
			if ($request->input('email'))
				$user->email = $request->input('email');
			if ($request->input('mobile'))
				$user->mobile = $request->input('mobile');
			if ($request->input('telephone'))
				$user->phone = $request->input('telephone');
			if ($request->input('website'))
				$user->website = $request->input('website');

			/* Adress */
			if ($request->input('address_street'))
				$user->address_street = $request->input('address_street');
			if ($request->input('address_number'))
				$user->address_number = $request->input('address_number');
			if ($request->input('address_zipcode'))
				$user->address_postal = $request->input('address_zipcode');
			if ($request->input('address_city'))
				$user->address_city = $request->input('address_city');
			$user->province_id = $request->input('province');
			$user->country_id = $request->input('country');

			/* Overig */
			if ($request->input('expdate'))
				$user->expiration_date = $request->input('expdate');
			if ($request->input('note'))
				$user->note = $request->input('note');
			if ($request->input('notepad'))
				$user->notepad = $request->input('notepad');
			if ($request->input('confirmdate'))
				$user->confirmed_mail = $request->input('confirmdate');
			if ($request->input('bandate'))
				$user->banned = $request->input('bandate');
			else
				$user->banned = null;
			if ($request->input('toggle-active'))
				$user->active = true;
			else
				$user->active = false;
			if ($request->input('toggle-api'))
				$user->api_access = true;
			else
				$user->api_access = false;
			if (!$request->input('gender') || $request->input('gender') == '-1')
				$user->gender = NULL;
			else
				$user->gender = $request->input('gender');

			$user->save();

			return back()->with('success', 1);
	}

	public function getSwitchSession()
	{
		if (!Auth::user()->isAdmin())
			return Redirect::back();

		$cook = Cookie::make('swpsess', Auth::id(), 180);

		Auth::loginUsingId(Route::input('user_id'));

		return Redirect::to('/')->withCookie($cook);

	}

	public function getSwitchSessionBack()
	{
		$swap_session = Cookie::get('swpsess');
		if (!$swap_session)
			return Redirect::back();

		$user = User::find($swap_session);
		if (!$user->isAdmin())
			return Redirect::back();

		Auth::loginUsingId($user->id);

		return Redirect::to('/')->withCookie(Cookie::forget('swpsess'));

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
			$resource = Resource::find($request->input('id'));
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
