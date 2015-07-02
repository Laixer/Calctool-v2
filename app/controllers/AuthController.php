<?php

class AuthController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getLogin()
	{
		if(App::environment('local'))
		{
			$user = User::where('username','=','system')->first();

			Auth::login($user);

			return Redirect::to('/');
		}

		return View::make('auth.login');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function doLogin()
	{
		$userdata = array(
			'username' 	=> strtolower(trim(Input::get('username'))),
			'password' 	=> Input::get('secret'),
			'active' 	=> 1,
			'banned' 	=> NULL
		);

		$remember = Input::get('rememberme') ? true : false;

		if(Auth::attempt($userdata, $remember)){
			return Redirect::to('/');
		}else{
			return Redirect::route('login')
				->withErrors(true)
				->withInput(Input::except('secret'));
		}
	}

	public function doLogout()
	{
		Auth::logout(); // log the user out of our application
		return Redirect::route('login'); // redirect the user to the login screen
	}

}
