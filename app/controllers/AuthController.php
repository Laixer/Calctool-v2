<?php

use Illuminate\Support\MessageBag;
//use App\Database\DemoProjectTemplate;

class AuthController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getLogin()
	{
		return View::make('auth.login');
	}

	public function getRegister()
	{
		return View::make('auth.registration');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function doLogin()
	{
		$errors = new MessageBag;

		$userdata = array(
			'username' 	=> strtolower(trim(Input::get('username'))),
			'password' 	=> Input::get('secret'),
			'active' 	=> 1,
			'banned' 	=> NULL
		);

		$remember = Input::get('rememberme') ? true : false;

		if(Auth::attempt($userdata, $remember)){

			// Email must be confirmed
			if (Auth::user()->confirmed_mail == NULL) {
				Auth::logout();
				$errors = new MessageBag(['mail' => ['Email nog niet bevestigd']]);
				return Redirect::to('login')->withErrors($errors)->withInput(Input::except('secret'));
			}

			// Redirect to dashboard
			return Redirect::to('/');
		}else{

			// Login failed
			$errors = new MessageBag(['password' => ['Gebruikersnaam of wachtwoord verkeerd']]);
			return Redirect::to('login')->withErrors($errors)->withInput(Input::except('secret'));
		}
	}

	public function doLogout()
	{
		Auth::logout(); // log the user out of our application
		return Redirect::to('login'); // redirect the user to the login screen
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function doRegister()
	{
		$rules = array(
			'username' => array('required','unique:user_account'),
			'email' => array('required','max:80','email','unique:user_account'),
			'secret' => array('required','confirmed','min:5'),
			'secret_confirmation' => array('required','min:5'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {

			return Redirect::to('register')->withErrors($validator)->withInput(Input::all());
		} else {
			$part = Part::where('part_name','=','contracting')->first();
			$part_type = PartType::where('type_name','=','calculation')->first();
			$tax = Tax::where('tax_rate','=',21)->first();

			$user = new User;
			$user->username = strtolower(trim(Input::get('username')));
			$user->secret = Hash::make(Input::get('secret'));
			$user->firstname = $user->username;
			$user->lastname = '';
			$user->api = md5(mt_rand());
			$user->token = sha1(mt_rand());
			$user->ip = $_SERVER['REMOTE_ADDR'];
			$user->promotion_code = md5(mt_rand());
			$user->address_street = '';
			$user->address_number = '';
			$user->address_postal = '';
			$user->address_city = '';
			$user->email = Input::get('email');
			$user->province_id = Province::where('province_name','=','zuid-holland')->first()->id;
			$user->country_id = Country::where('country_name','=','nederland')->first()->id;
			$user->user_type = UserType::where('user_type','=','user')->first()->id;

			Mail::queue('mail.confirm', array('api' => $user->api, 'token' => $user->token, 'username' => $user->username), function($message) {
				$message->to(Input::get('email'), strtolower(trim(Input::get('username'))))->subject('Calctool - Account activatie');
			});

			$user->save();

			return Redirect::to('register')->with('success', 'Account aangemaakt, er is een bevestingsmail verstuurd');
		}

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function getActivate()
	{
		$user = User::where('token','=',Route::Input('token'))->where('api','=',Route::Input('api'))->first();
		if (!$user) {
			$errors = new MessageBag(['activate' => ['Activatielink is niet geldig']]);
			return Redirect::to('login')->withErrors($errors);
		}
		if ($user->confirmed_mail) {
			$errors = new MessageBag(['activate' => ['Account is al geactiveerd']]);
			return Redirect::to('login')->withErrors($errors);
		}
		$user->confirmed_mail = date('Y-m-d H:i:s');
		$user->last_active = date('Y-m-d H:i:s');
		$user->save();

		DemoProjectTemplate::setup($user->id);

		Auth::login($user);
		return Redirect::to('/');
	}

}
