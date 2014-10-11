<?php

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

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Route
	 */
	public function doLogin()
	{
		$userdata = array(
			'username' 	=> Input::get('username'),
			'password' 	=> Input::get('secret'),
			'active' 	=> 1,
			'banned' 	=> NULL
		);

		$remember = Input::get('rememberme') ? true : false;

		if(Auth::attempt($userdata, $remember)){
			echo 'SUCCESS!';
		}else{
			return Redirect::to('login')
				->withErrors(true)
				->withInput(Input::except('secret'));
		}
	}

	public function doLogout()
	{
		Auth::logout(); // log the user out of our application
		return Redirect::to('login'); // redirect the user to the login screen
	}

}
