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

	public function doUpdateMyCompany(Request $request)
	{
		$this->validate($request, [
			/* General */
			'id' => array('required','integer'),
			/* Company */
			'company_type' => array('required_if:relationkind,zakelijk','numeric'),
			'company_name' => array('required_if:relationkind,zakelijk','max:50'),
			'kvk' => array('numeric','min:8'),
			'btw' => array('alpha_num','min:14'),
			'telephone_comp' => array('alpha_num','max:12'),
			'email_comp' => array('required_if:relationkind,zakelijk','email','max:80'),
			//'website' => array('url','max:180'),
			/* Adress */
			'street' => array('required','alpha','max:60'),
			'address_number' => array('required','alpha_num','max:5'),
			'zipcode' => array('required','size:6'),
			'city' => array('required','alpha_num','max:35'),
			'province' => array('required','numeric'),
			'country' => array('required','numeric')
		]);

			/* General */
			$relation = Relation::find($request->input('id'));
			if (!$relation || !$relation->isOwner()) {
				return Redirect::back()->withInput($request->all());
			}
			$relation->note = $request->input('note');

			/* Company */
			$relation_kind = RelationKind::where('id','=',$relation->kind_id)->firstOrFail();
			if ($relation_kind->kind_name == "zakelijk") {
				$relation->company_name = $request->input('company_name');
				$relation->type_id = $request->input('company_type');
				$relation->kvk = $request->input('kvk');
				$relation->btw = $request->input('btw');
				$relation->phone = $request->input('telephone_comp');
				$relation->email = $request->input('email_comp');
				$relation->website = $request->input('website');
			}

			/* Adress */
			$relation->address_street = $request->input('street');
			$relation->address_number = $request->input('address_number');
			$relation->address_postal = $request->input('zipcode');
			$relation->address_city = $request->input('city');
			$relation->province_id = $request->input('province');
			$relation->country_id = $request->input('country');

			$relation->save();

			return back()->with('success', 1);
	}

	public function doUpdate(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer'),
			'debtor' => array('required','alpha_num','max:10'),
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
		$relation = \Calctool\Models\Relation::find($request->input('id'));
		if (!$relation || !$relation->isOwner()) {
			return back()->withInput($request->all());
		}
		$relation->note = $request->input('note');
		$relation->debtor_code = $request->input('debtor');

		/* Company */
		$relation_kind = \Calctool\Models\RelationKind::where('id','=',$relation->kind_id)->firstOrFail();
		if ($relation_kind->kind_name == "zakelijk") {
			$relation->company_name = $request->input('company_name');
			$relation->type_id = $request->input('company_type');
			$relation->kvk = $request->input('kvk');
			$relation->btw = $request->input('btw');
			$relation->phone = $request->input('telephone_comp');
			$relation->email = $request->input('email_comp');
			$relation->website = $request->input('website');
		}

		/* Adress */
		$relation->address_street = $request->input('street');
		$relation->address_number = $request->input('address_number');
		$relation->address_postal = $request->input('zipcode');
		$relation->address_city = $request->input('city');
		$relation->province_id = $request->input('province');
		$relation->country_id = $request->input('country');

		$relation->save();

		return back()->with('success', 1);
	}

	public function doUpdateContact(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer'),
			'contact_name' => array('required','max:50'),
			'contact_firstname' => array('required','max:30'),
			'email' => array('required','email','max:80'),
		]);

		$contact = \Calctool\Models\Contact::find($request->input('id'));
		if (!$contact) {
			return back()->withInput($request->all());
		}
		$relation = \Calctool\Models\Relation::find($contact->relation_id);
		if (!$relation || !$relation->isOwner()) {
			return back()->withInput($request->all());
		}

		if ($request->input('contact_firstname')) {
			$contact->firstname = $request->input('contact_firstname');
		}
		$contact->lastname = $request->input('contact_name');
		$contact->mobile = $request->input('mobile');
		$contact->phone = $request->input('telephone');
		$contact->email = $request->input('email');
		$contact->note = $request->input('note');
		if ($request->input('contactfunction')) {
			$contact->function_id = $request->input('contactfunction');
		}
		if ($request->input('gender') == '-1') {
			$contact->gender = NULL;
		} else {
			$contact->gender = $request->input('gender');
		}

		$contact->save();

		return back()->with('success', 1);
	}

	public function doNewMyCompany(Request $request)
	{
		$this->validate($request, [
			/* Company */
			'company_type' => array('required_if:relationkind,zakelijk','numeric'),
			'company_name' => array('required_if:relationkind,zakelijk','max:50'),
			'kvk' => array('numeric','min:8'),
			'btw' => array('alpha_num','min:14'),
			'telephone_comp' => array('alpha_num','max:12'),
			'email_comp' => array('required_if:relationkind,zakelijk','email','max:80'),
			//'website' => array('url','max:180'),
			/* Adress */
			'street' => array('required','alpha','max:60'),
			'address_number' => array('required','alpha_num','max:5'),
			'zipcode' => array('required','size:6'),
			'city' => array('required','alpha_num','max:35'),
			'province' => array('required','numeric'),
			'country' => array('required','numeric'),
		]);

		/* General */
		$relation = new Relation;
		$relation->user_id = Auth::id();
		$relation->note = $request->input('note');
		$relation->debtor_code = mt_rand(1000000, 9999999);

		/* Company */
		$relation->kind_id = RelationKind::where('kind_name','=','zakelijk')->first()->id;
		$relation->company_name = $request->input('company_name');
		$relation->type_id = $request->input('company_type');
		$relation->kvk = $request->input('kvk');
		$relation->btw = $request->input('btw');
		$relation->phone = $request->input('telephone_comp');
		$relation->email = $request->input('email_comp');
		$relation->website = $request->input('website');

		/* Adress */
		$relation->address_street = $request->input('street');
		$relation->address_number = $request->input('address_number');
		$relation->address_postal = $request->input('zipcode');
		$relation->address_city = $request->input('city');
		$relation->province_id = $request->input('province');
		$relation->country_id = $request->input('country');

		$relation->save();

		$user = Auth::user();
		$user->self_id = $relation->id;
		$user->save();

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

		return back()->with('success', 1);
		//return redirect('/relation-'.$relation->id.'/edit')->with('success', 1);
	}

	/*public function doNewLogo(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer'),
			'image' => array('required', 'mimes:jpeg,bmp,png,gif'),
		]);

			if ($request->hasFile('image')) {
				$file = $request->file('image');
				$newname = Auth::id().'-'.md5(mt_rand()).'.'.$file->getClientOriginalExtension();
				$file->move('user-content', $newname);

				$image = Image::make('user-content/' . $newname)->resize(350, 100)->save();

				$resource = new Resource;
				$resource->resource_name = $newname;
				$resource->file_location = 'user-content/' . $newname;
				$resource->file_size = $image->filesize();
				$resource->user_id = Auth::id();
				$resource->description = 'Relatielogo';

				$resource->save();

				$relation = Relation::find($request->input('id'));
				if (!$relation || !$relation->isOwner()) {
					return back()->withInput($request->all());
				}
				$relation->logo_id = $resource->id;

				$relation->save();

				return back()->with('success', 1);
			} else {

				$messages->add('file', 'Geen afbeelding geupload');

				// redirect our user back to the form with the errors from the validator
				return back()->withErrors($messages);
			}

	}*/

}
