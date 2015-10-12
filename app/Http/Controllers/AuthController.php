<?php

namespace Calctool\Http\Controllers;

use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;
use Longman\TelegramBot\Request as TRequest;

use Auth;
use Redis;

class AuthController extends Controller {

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function doLogin(Request $request)
	{
		$errors = new MessageBag;

		$username = strtolower(trim($request->input('username')));
		$userdata = array(
			'username' 	=> $username,
			'password' 	=> $request->input('secret'),
			'active' 	=> 1,
			'banned' 	=> NULL
		);

		$userdata2 = array(
			'email' 	=> $username,
			'password' 	=> $request->input('secret'),
			'active' 	=> 1,
			'banned' 	=> NULL
		);

		$remember = $request->input('rememberme') ? true : false;

		if (Redis::exists('auth:'.$username.':block')) {
			$errors = new MessageBag(['auth' => ['Account geblokkeerd voor 15 minuten']]);
			return \Redirect::to('login')->withErrors($errors)->withInput($request->except('secret'));
		}

		if(Auth::attempt($userdata, $remember) || Auth::attempt($userdata2, $remember)){

			/* Email must be confirmed */
			if (Auth::user()->confirmed_mail == NULL) {
				Auth::logout();
				$errors = new MessageBag(['mail' => ['Email nog niet bevestigd']]);
				return \Redirect::to('login')->withErrors($errors)->withInput($request->except('secret'));
			}

			Redis::del('auth:'.$username.':fail', 'auth:'.$username.':block');

			$log = new \Calctool\Models\Audit;
			$log->ip = $_SERVER['REMOTE_ADDR'];
			$log->event = '[LOGIN] [SUCCESS] ' . $_SERVER['HTTP_USER_AGENT'];
			$log->user_id = Auth::id();
			$log->save();

			/* Redirect to dashboard*/
			return redirect('/');
		} else {

			// Login failed
			$errors = new MessageBag(['password' => ['Gebruikersnaam of wachtwoord verkeerd']]);

			// Count the failed logins
			$failcount = Redis::get('auth:'.$username.':fail');
			if ($failcount >= 4) {
				Redis::set('auth:'.$username.':block', true);
				Redis::expire('auth:'.$username.':block', 900);
			} else {
				Redis::incr('auth:'.$username.':fail');
			}

			$failuser = \Calctool\Models\User::where('username', $username)->first();
			if ($failuser) {
				$log = new \Calctool\Models\Audit;
				$log->ip = $_SERVER['REMOTE_ADDR'];
				$log->event = '[LOGIN] [FAILED] '.$failcount;
				$log->user_id = $failuser->id;
				$log->save();
			}

			return \Redirect::to('login')->withErrors($errors)->withInput(Input::except('secret'))->withCookie(Cookie::forget('swpsess'));
		}
	}

	public function doLogout()
	{
		Auth::logout(); // log the user out of our application
		return \Redirect::to('login'); // redirect the user to the login screen
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

			return \Redirect::to('register')->withErrors($validator)->withInput(Input::all());
		} else {
			$user = new User;
			$user->username = strtolower(trim(Input::get('username')));
			$user->secret = Hash::make(Input::get('secret'));
			$user->firstname = $user->username;
			$user->api = md5(mt_rand());
			$user->token = sha1($user->secret);
			$user->referral_key = md5(mt_rand());
			$user->ip = $_SERVER['REMOTE_ADDR'];
			$user->email = Input::get('email');
			$user->expiration_date = date('Y-m-d', strtotime("+1 month", time()));
			$user->user_type = UserType::where('user_type','=','user')->first()->id;

			Mailgun::send('mail.confirm', array('api' => $user->api, 'token' => $user->token, 'username' => $user->username), function($message) {
				$message->to(Input::get('email'), strtolower(trim(Input::get('username'))))->subject('Calctool - Account activatie');
			});

			$user->save();

			return \Redirect::to('register')->with('success', 'Account aangemaakt, er is een bevestingsmail verstuurd');
		}

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function doNewPassword()
	{
		$rules = array(
			'secret' => array('required','confirmed','min:5'),
			'secret_confirmation' => array('required','min:5'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {

			return \Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {
			$user = User::where('token','=',Route::Input('token'))->where('api','=',Route::Input('api'))->first();
			if (!$user) {
				$errors = new MessageBag(['activate' => ['Activatielink is niet geldig']]);
				return \Redirect::to('login')->withErrors($errors);
			}
			$user->secret = Hash::make(Input::get('secret'));
			$user->active = true;
			$user->token = sha1($user->secret);
			$user->save();

			$log = new Audit;
			$log->ip = $_SERVER['REMOTE_ADDR'];
			$log->event = '[NEWPASS] [SUCCESS]';
			$log->user_id = $user->id;
			$log->save();

			Auth::login($user);
			return \Redirect::to('/');
		}

	}

	private function informAdmin(User $newuser)
	{
		if ($_ENV['TELEGRAM_ENABLED']) {
			$telegram = new Longman\TelegramBot\Telegram($_ENV['TELEGRAM_API'], $_ENV['TELEGRAM_NAME']);
			Request::initialize($telegram);

			foreach (User::where('user_type','=',UserType::where('user_type','=','admin')->first()->id)->get() as $admin) {
				$tgram = Telegram::where('user_id','=',$admin->id)->first();
				if ($tgram && $tgram->alert) {

					$text  = "Nieuwe gebruiker aangemeld\n";
					$text .= "Gebruikersnaam: " . $newuser->username . "\n";
					$text .= "Email: " . $newuser->email . "\n";

					$data = array();
					$data['chat_id'] = $tgram->uid;
					$data['text'] = $text;

					$result = Request::sendMessage($data);
				}
			}
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function doActivate()
	{
		$user = User::where('token','=',Route::Input('token'))->where('api','=',Route::Input('api'))->first();
		if (!$user) {
			$errors = new MessageBag(['activate' => ['Activatielink is niet geldig']]);
			return \Redirect::to('login')->withErrors($errors);
		}
		if ($user->confirmed_mail) {
			$errors = new MessageBag(['activate' => ['Account is al geactiveerd']]);
			return \Redirect::to('login')->withErrors($errors);
		}
		$user->confirmed_mail = date('Y-m-d H:i:s');
		$user->save();

		//DemoProjectTemplate::setup($user->id);

		$this->informAdmin($user);

		$log = new Audit;
		$log->ip = $_SERVER['REMOTE_ADDR'];
		$log->event = '[ACTIVATE] [SUCCESS]';
		$log->user_id = $user->id;
		$log->save();

		Auth::login($user);
		return \Redirect::to('/')->withCookie(Cookie::make('nstep', 'intro_'.$user->id, 60*24*3));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function doBlockPassword()
	{
		$rules = array(
			'email' => array('required','max:80','email')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {

			return \Redirect::to('login')->with('success', 1);
		} else {
			$user = User::where('email','=',Input::get('email'))->first();
			if (!$user)
				return \Redirect::to('login')->with('success', 1);
			$user->secret = Hash::make(mt_rand());
			$user->active = false;
			$user->api = md5(mt_rand());

			$data = array('api' => $user->api, 'token' => $user->token, 'username' => $user->username);
			Mailgun::send('mail.password', $data, function($message) use ($data) {
				$message->to(Input::get('email'), strtolower(trim($data['username'])))->subject('Calctool - Wachtwoord herstellen');
			});

			$user->save();

			$log = new Audit;
			$log->ip = $_SERVER['REMOTE_ADDR'];
			$log->event = '[BLOCKPASS] [SUCCESS]';
			$log->user_id = $user->id;
			$log->save();

			return \Redirect::to('login')->with('success', 1);
		}

	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function doHideNextStep()
	{
		return Response::make(json_encode(['success' => 1]))->withCookie(Cookie::forget('nstep'));
	}

}
