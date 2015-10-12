<?php

namespace Calctool\Http\Controllers;

use JeroenDesloovere\VCard\VCard;

class RelationController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */

	public function getNew()
	{
		return View::make('user.new_relation');
	}

	public function getEdit()
	{
		return View::make('user.edit_relation');
	}

	public function getNewContact()
	{
		return View::make('user.new_contact');
	}

	public function getEditContact()
	{
		return View::make('user.edit_contact');
	}

	public function getMyCompany()
	{
		return View::make('user.edit_mycompany');
	}

	public function doUpdateMyCompany()
	{
		$rules = array(
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
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			/* General */
			$relation = Relation::find(Input::get('id'));
			if (!$relation || !$relation->isOwner()) {
				return Redirect::back()->withInput(Input::all());
			}
			$relation->note = Input::get('note');

			/* Company */
			$relation_kind = RelationKind::where('id','=',$relation->kind_id)->firstOrFail();
			if ($relation_kind->kind_name == "zakelijk") {
				$relation->company_name = Input::get('company_name');
				$relation->type_id = Input::get('company_type');
				$relation->kvk = Input::get('kvk');
				$relation->btw = Input::get('btw');
				$relation->phone = Input::get('telephone_comp');
				$relation->email = Input::get('email_comp');
				$relation->website = Input::get('website');
			}

			/* Adress */
			$relation->address_street = Input::get('street');
			$relation->address_number = Input::get('address_number');
			$relation->address_postal = Input::get('zipcode');
			$relation->address_city = Input::get('city');
			$relation->province_id = Input::get('province');
			$relation->country_id = Input::get('country');

			$relation->save();

			return Redirect::back()->with('success', 1);
		}
	}

	public function doUpdate()
	{
		$rules = array(
			/* General */
			'id' => array('required','integer'),
			'debtor' => array('required','alpha_num','max:10'),
			/* Company */
			'company_type' => array('required_if:relationkind,zakelijk','numeric'),
			'company_name' => array('required_if:relationkind,zakelijk','max:50'),
			//'kvk' => array('numeric','min:8'),
			//'btw' => array('alpha_num','min:14'),
			//'telephone_comp' => array('alpha_num','max:12'),
			'email_comp' => array('required_if:relationkind,zakelijk','email','max:80'),
			//'website' => array('url','max:180'),
			/* Adress */
			'street' => array('required','alpha','max:60'),
			'address_number' => array('required','alpha_num','max:5'),
			'zipcode' => array('required','size:6'),
			'city' => array('required','alpha_num','max:35'),
			'province' => array('required','numeric'),
			'country' => array('required','numeric')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			/* General */
			$relation = Relation::find(Input::get('id'));
			if (!$relation || !$relation->isOwner()) {
				return Redirect::back()->withInput(Input::all());
			}
			$relation->note = Input::get('note');
			$relation->debtor_code = Input::get('debtor');

			/* Company */
			$relation_kind = RelationKind::where('id','=',$relation->kind_id)->firstOrFail();
			if ($relation_kind->kind_name == "zakelijk") {
				$relation->company_name = Input::get('company_name');
				$relation->type_id = Input::get('company_type');
				$relation->kvk = Input::get('kvk');
				$relation->btw = Input::get('btw');
				$relation->phone = Input::get('telephone_comp');
				$relation->email = Input::get('email_comp');
				$relation->website = Input::get('website');
			}

			/* Adress */
			$relation->address_street = Input::get('street');
			$relation->address_number = Input::get('address_number');
			$relation->address_postal = Input::get('zipcode');
			$relation->address_city = Input::get('city');
			$relation->province_id = Input::get('province');
			$relation->country_id = Input::get('country');

			$relation->save();

			return Redirect::back()->with('success', 1);
		}
	}

	public function doUpdateContact()
	{
		$rules = array(
			/* General */
			'id' => array('required','integer'),
			'contact_name' => array('required','max:50'),
			'contact_firstname' => array('required','max:30'),
			//'mobile' => array('alpha_num','max:14'),
			//'telephone' => array('alpha_num','max:14'),
			'email' => array('required','email','max:80'),
			//'contactfunction' => array('required','numeric'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$contact = Contact::find(Input::get('id'));
			if (!$contact)
				return Redirect::back()->withInput(Input::all());
			$relation = Relation::find($contact->relation_id);
			if (!$relation || !$relation->isOwner()) {
				return Redirect::back()->withInput(Input::all());
			}

			if (Input::get('contact_firstname'))
				$contact->firstname = Input::get('contact_firstname');//;
			$contact->lastname = Input::get('contact_name');
			$contact->mobile = Input::get('mobile');
			$contact->phone = Input::get('telephone');
			$contact->email = Input::get('email');
			$contact->note = Input::get('note');
			if (Input::get('contactfunction'))
				$contact->function_id = Input::get('contactfunction');
			if (Input::get('gender') == '-1')
				$contact->gender = NULL;
			else
				$contact->gender = Input::get('gender');

			$contact->save();

			return Redirect::back()->with('success', 1);
		}
	}

	public function doUpdateIban()
	{
		$rules = array(
			//'id' => array('required','integer'),
			//'iban' => array('alpha_num'),
			//'iban_name' => array('required','max:50')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$iban = Iban::find(Input::get('id'));
			if (!$iban)
				return Redirect::back()->withInput(Input::all());
			$relation = Relation::find($iban->relation_id);
			if (!$relation || !$relation->isOwner()) {
				return Redirect::back()->withInput(Input::all());
			}

			$iban->iban = Input::get('iban');
			$iban->iban_name = Input::get('iban_name');

			$iban->save();

			return Redirect::back()->with('success', 1);
		}
	}

	public function doNewIban()
	{
		$rules = array(
			//'id' => array('required','integer'),
			//'iban' => array('alpha_num'),
			//'iban_name' => array('required','max:50')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$relation = Relation::find(Input::get('id'));
			if (!$relation || !$relation->isOwner()) {
				return Redirect::back()->withInput(Input::all());
			}

			$iban = new Iban;
			$iban->iban = Input::get('iban');
			$iban->iban_name = Input::get('iban_name');
			$iban->relation_id = $relation->id;

			$iban->save();

			return Redirect::back()->with('success', 1);
		}
	}

	public function doNewMyCompany()
	{
		$rules = array(
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
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			/* General */
			$relation = new Relation;
			$relation->user_id = Auth::id();
			$relation->note = Input::get('note');
			$relation->debtor_code = mt_rand(1000000, 9999999);

			/* Company */
			$relation->kind_id = RelationKind::where('kind_name','=','zakelijk')->first()->id;
			$relation->company_name = Input::get('company_name');
			$relation->type_id = Input::get('company_type');
			$relation->kvk = Input::get('kvk');
			$relation->btw = Input::get('btw');
			$relation->phone = Input::get('telephone_comp');
			$relation->email = Input::get('email_comp');
			$relation->website = Input::get('website');

			/* Adress */
			$relation->address_street = Input::get('street');
			$relation->address_number = Input::get('address_number');
			$relation->address_postal = Input::get('zipcode');
			$relation->address_city = Input::get('city');
			$relation->province_id = Input::get('province');
			$relation->country_id = Input::get('country');

			$relation->save();

			$user = Auth::user();
			$user->self_id = $relation->id;
			$user->save();

			return Redirect::back()->with('success', 1);
		}
	}

	public function doNew()
	{
		$rules = array(
			/* General */
			'relationkind' => array('required','numeric'),
			'debtor' => array('required','alpha_num','max:10'),
			/* Company */
			'company_type' => array('required_if:relationkind,1','numeric'),
			'company_name' => array('required_if:relationkind,1','max:50'),
			//'kvk' => array('numeric','min:8'),
			//'btw' => array('alpha_num','min:14'),
			//'telephone_comp' => array('alpha_num','max:12'),
			'email_comp' => array('required_if:relationkind,1','email','max:80'),
			//'website' => array('url','max:180'),
			/* Contact */
			'contact_name' => array('required','max:50'),
			'contact_firstname' => array('required','max:30'),
			//'mobile' => array('alpha_num','max:14'),
			//'telephone' => array('alpha_num','max:14'),
			'email' => array('required','email','max:80'),
			'contactfunction' => array('required','numeric'),
			/* Adress */
			'street' => array('required','alpha','max:60'),
			'address_number' => array('required','alpha_num','max:5'),
			'zipcode' => array('required','size:6'),
			'city' => array('required','alpha_num','max:35'),
			'province' => array('required','numeric'),
			'country' => array('required','numeric'),
			/* Payment */
			//'iban' => array('alpha_num'),
			//'iban_name' => array('required','max:50')
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			/* General */
			$relation = new Relation;
			$relation->user_id = Auth::id();
			$relation->note = Input::get('note');
			$relation->kind_id = Input::get('relationkind');
			$relation->debtor_code = Input::get('debtor');

			/* Company */
			$relation_kind = RelationKind::where('id','=',$relation->kind_id)->firstOrFail();
			if ($relation_kind->kind_name == "zakelijk") {
				$relation->company_name = Input::get('company_name');
				$relation->type_id = Input::get('company_type');
				$relation->kvk = Input::get('kvk');
				$relation->btw = Input::get('btw');
				$relation->phone = Input::get('telephone_comp');
				$relation->email = Input::get('email_comp');
				$relation->website = Input::get('website');
			}

			/* Adress */
			$relation->address_street = Input::get('street');
			$relation->address_number = Input::get('address_number');
			$relation->address_postal = Input::get('zipcode');
			$relation->address_city = Input::get('city');
			$relation->province_id = Input::get('province');
			$relation->country_id = Input::get('country');

			$relation->save();

			/* Contact */
			$contact = new Contact;
			$contact->firstname = Input::get('contact_firstname');
			$contact->lastname = Input::get('contact_name');
			$contact->mobile = Input::get('mobile');
			$contact->phone = Input::get('telephone');
			$contact->email = Input::get('email');
			$contact->note = Input::get('note');
			$contact->relation_id = $relation->id;
			if ($relation_kind->kind_name == "zakelijk") {
				$contact->function_id = Input::get('contactfunction');
			} else {
				$contact->function_id = ContactFunction::where('function_name','=','opdrachtgever')->first()->id;
			}
			if (Input::get('gender') == '-1') {
				$contact->gender = NULL;
			} else {
				$contact->gender = Input::get('gender');
			}

			$contact->save();

			/*Betalingsgevens*/
			$iban = new Iban;
			$iban->iban = Input::get('iban');
			$iban->iban_name = Input::get('iban_name');
			$iban->relation_id = $relation->id;

			$iban->save();

			return Redirect::to('/relation-'.$relation->id.'/edit')->with('success', 1);
		}
	}

	public function doNewContact()
	{
		$rules = array(
			/* Contact */
			'id' => array('required','integer'),
			'contact_name' => array('required','max:50'),
			'contact_firstname' => array('required','max:30'),
			//'mobile' => array('alpha_num','max:14'),
			//'telephone' => array('alpha_num','max:14'),
			'email' => array('required','email','max:80'),
			//'contactfunction' => array('required','numeric'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$relation = Relation::find(Input::get('id'));
			if (!$relation || !$relation->isOwner()) {
				return Redirect::back()->withInput(Input::all());
			}

			$contact = new Contact;
			$contact->firstname = Input::get('contact_firstname');
			$contact->lastname = Input::get('contact_name');
			$contact->mobile = Input::get('mobile');
			$contact->phone = Input::get('telephone');
			$contact->email = Input::get('email');
			$contact->note = Input::get('note');
			$contact->relation_id = $relation->id;
			if (RelationKind::find($relation->kind_id)->kind_name=='zakelijk') {
				$contact->function_id = Input::get('contactfunction');
			} else {
				$contact->function_id = ContactFunction::where('function_name','=','opdrachtgever')->first()->id;
			}
			if (Input::get('gender') == '-1')
				$contact->gender = NULL;
			else
				$contact->gender = Input::get('gender');

			$contact->save();

			return Redirect::to('/relation-'.Input::get('id').'/edit')->with('success', 1);
		}
	}

	public function doMyCompanyNewContact()
	{
		$rules = array(
			/* Contact */
			'id' => array('required','integer'),
			'contact_name' => array('required','max:50'),
			'contact_firstname' => array('required','max:30'),
			//'mobile' => array('alpha_num','max:14'),
			//'telephone' => array('alpha_num','max:14'),
			'email' => array('required','email','max:80'),
			'contactfunction' => array('required','numeric'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$relation = Relation::find(Input::get('id'));
			if (!$relation || !$relation->isOwner()) {
				return Redirect::back()->withInput(Input::all());
			}

			$contact = new Contact;
			$contact->firstname = Input::get('contact_firstname');
			$contact->lastname = Input::get('contact_name');
			$contact->mobile = Input::get('mobile');
			$contact->phone = Input::get('telephone');
			$contact->email = Input::get('email');
			$contact->note = Input::get('note');
			$contact->relation_id = $relation->id;
			$contact->function_id = Input::get('contactfunction');

			$contact->save();

			return Redirect::to('/mycompany')->with('success', 1);
		}
	}

	public function doDeleteContact()
	{
		$rules = array(
			'id' => array('required','integer'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			$rec = Contact::find(Input::get('id'));
			if (!$rec)
				return Redirect::back()->withInput(Input::all());
			$relation = Relation::find($rec->relation_id);
			if (!$relation || !$relation->isOwner()) {
				return Redirect::back()->withInput(Input::all());
			}

			$rec->delete();

			return Redirect::back()->with('success', 1);
		}
	}

	public function getAll()
	{
		return View::make('user.relation');
	}

	public function doNewLogo()
	{
		$rules = array(
			'id' => array('required','integer'),
			'image' => array('required', 'mimes:jpeg,bmp,png,gif'),
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {

			if (Input::hasFile('image')) {
				$file = Input::file('image');
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

				$relation = Relation::find(Input::get('id'));
				if (!$relation || !$relation->isOwner()) {
					return Redirect::back()->withInput(Input::all());
				}
				$relation->logo_id = $resource->id;

				$relation->save();

				return Redirect::back()->with('success', 1);
			} else {

				$messages->add('file', 'Geen afbeelding geupload');

				// redirect our user back to the form with the errors from the validator
				return Redirect::back()->withErrors($messages);
			}

		}
	}

	public function downloadVCard()
	{

		$contact = Contact::find(Route::Input('contact_id'));
		if (!$contact) {
			return;
		} else {
			$relation = Relation::find($contact->relation_id);
			if (!$relation || !$relation->isOwner()) {
				return;
			}
		}

		// define vcard
		$vcard = new VCard();

		// define variables
		$additional = '';
		$prefix = '';
		$suffix = '';

		// add personal data
		$vcard->addName($contact->lastname, $contact->firstname, $additional, $prefix, $suffix);

		// add work data
		$vcard->addCompany($relation->company_name);
		$vcard->addJobtitle(ucwords(ContactFunction::find($contact->function_id)->function_name));
		$vcard->addEmail($relation->email);
		if ($relation->phone)
			$vcard->addPhoneNumber($relation->phone, 'WORK');
		if ($relation->mobile)
			$vcard->addPhoneNumber($relation->mobile, 'WORK');
		//$vcard->addAddress(null, null, 'street', 'worktown', null, 'workpostcode', 'Belgium');
		//$vcard->addURL('http://www.jeroendesloovere.be');

		// return vcard as a download
		return $vcard->download();
	}
}
