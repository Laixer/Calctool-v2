<?php

class OfferController extends BaseController {

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

	public function doNewOffer()
	{
		$rules = array(
			'deliver' => array('required','integer','min:0'),
			'valid' => array('required','integer','min:0')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {
			$offer = new Offer;
			$offer->description = Input::get('description');
			$offer->closure = Input::get('closure');
			if (Input::get('toggle-payment'))
				$offer->downpayment = Input::get('toggle-payment');
			$offer->auto_email_reminder = false;
			$offer->offer_finish = date("Y-m-d H:i:s");
			$offer->deliver_id = Input::get('deliver');
			$offer->valid_id = Input::get('valid');
			$offer->project_id = Route::Input('project_id');
			$offer->resource_id = 1;

			$offer->save();

			return Redirect::back()->with('success', 'Opgeslagen');
		}

	}

}
