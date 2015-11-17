<?php

namespace Calctool\Http\Controllers;

use Illuminate\Http\Request;

use \Calctool\Models\Wholesale;
use \Calctool\Models\WholesaleType;

use \Auth;

class WholesaleController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */

	public function doUpdateIban(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer'),
			'iban' => array('alpha_num'),
			'iban_name' => array('required','max:50')
		]);

		$wholesale = Wholesale::find($request->input('id'));
		if (!$wholesale || !$wholesale->isOwner()) {
			return back()->withInput($request->all());
		}
		$wholesale->iban = $request->get('iban');
		$wholesale->iban_name = $request->get('iban_name');

		$wholesale->save();

		return back()->with('success', 1);
	}

	public function getDelete(Request $request, $wholesale_id)
	{
		$wholesale = Wholesale::find($wholesale_id);
		if (!$wholesale || !$wholesale->isOwner()) {
			return back()->withInput($request->all());
		}

		$wholesale->active = false;

		$wholesale->save();

		return redirect('/wholesale');
	}

	public function doUpdate(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer'),
			'company_type' => array('required_if:relationkind,zakelijk','numeric'),
			'company_name' => array('required_if:relationkind,zakelijk','max:50'),
			'email_comp' => array('required_if:relationkind,zakelijk','email','max:80'),
			'street' => array('required','alpha','max:60'),
			'address_number' => array('required','alpha_num','max:5'),
			'zipcode' => array('required','size:6'),
			'city' => array('required','alpha_num','max:35'),
			'province' => array('required','numeric'),
			'country' => array('required','numeric')
		]);

		/* General */
		$wholesale = Wholesale::find($request->input('id'));
		if (!$wholesale || !$wholesale->isOwner()) {
			return back()->withInput($request->all());
		}
		$wholesale->note = $request->input('note');

		/* Company */
		$wholesale->company_name = $request->input('company_name');
		$wholesale->type_id = $request->input('company_type');
		$wholesale->phone = $request->input('telephone_comp');
		$wholesale->email = $request->input('email_comp');
		$wholesale->website = $request->input('website');

		/* Adress */
		$wholesale->address_street = $request->input('street');
		$wholesale->address_number = $request->input('address_number');
		$wholesale->address_postal = $request->input('zipcode');
		$wholesale->address_city = $request->input('city');
		$wholesale->province_id = $request->input('province');
		$wholesale->country_id = $request->input('country');

		$wholesale->save();

		return back()->with('success', 1);
	}

	public function doNew(Request $request)
	{
		$this->validate($request, [
			/* Company */
			'company_type' => array('required','numeric'),
			'company_name' => array('required','max:50'),
			/* Contact */
			'email' => array('email','max:80'),
			/* Adress */
			'street' => array('required','alpha','max:60'),
			'address_number' => array('required','alpha_num','max:5'),
			'zipcode' => array('required','size:6'),
			'city' => array('required','alpha_num','max:35'),
			'province' => array('required','numeric'),
			'country' => array('required','numeric'),
		]);

		/* General */
		$wholesale = new Wholesale;
		$wholesale->user_id = \Auth::id();
		$wholesale->note = $request->input('note');

		$wholesale->company_name = $request->input('company_name');
		$wholesale->type_id = $request->input('company_type');
		$wholesale->phone = $request->input('telephone_comp');
		$wholesale->email = $request->input('email');
		$wholesale->website = $request->input('website');

		/* Adress */
		$wholesale->address_street = $request->input('street');
		$wholesale->address_number = $request->input('address_number');
		$wholesale->address_postal = $request->input('zipcode');
		$wholesale->address_city = $request->input('city');
		$wholesale->province_id = $request->input('province');
		$wholesale->country_id = $request->input('country');

		$wholesale->save();

		return redirect('/wholesale-'.$wholesale->id.'/edit')->with('success', 1);
	}

}
