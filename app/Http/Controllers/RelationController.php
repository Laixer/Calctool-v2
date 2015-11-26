<?php

namespace Calctool\Http\Controllers;

use Illuminate\Http\Request;
use JeroenDesloovere\VCard\VCard;

use \Calctool\Models\Relation;
use \Calctool\Models\RelationKind;
use \Calctool\Models\Contact;
use \Calctool\Models\ContactFunction;
use \Calctool\Models\Resource;

use \Auth;
use \Image;

class RelationController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */

	public function getNew()
	{
		return view('user.new_relation');
	}

	public function getEdit()
	{
		return view('user.edit_relation');
	}

	public function getNewContact()
	{
		return view('user.new_contact');
	}

	public function getEditContact()
	{
		return view('user.edit_contact');
	}

	public function getMyCompany()
	{
		return view('user.edit_mycompany');
	}

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

		return back()->with('success', 'Uw bedrijfsgegevens zijn aangepast');
	}

	public function doUpdate(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer'),
			'debtor' => array('required','alpha_num','max:10'),
			'company_type' => array('required_if:relationkind,zakelijk','numeric'),
			'company_name' => array('required_if:relationkind,zakelijk','max:50'),
			'email_comp' => array('required_if:relationkind,zakelijk','email','max:80'),
			'street' => array('required','regex:/^[A-Za-z0-9\s]*$/','max:60'),
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

		return back()->with('success', 'Relatie is aangepast');
	}

	public function getDelete(Request $request, $relation_id)
	{
		$relation = \Calctool\Models\Relation::find($relation_id);
		if (!$relation || !$relation->isOwner()) {
			return back()->withInput($request->all());
		}

		$relation->active = false;

		$relation->save();

		return redirect('/relation');
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

		return back()->with('success', 'Contactgegevens zijn aangepast');
	}

	public function doUpdateIban(Request $request)
	{
		$relation = \Calctool\Models\Relation::find($request->input('id'));
		if (!$relation || !$relation->isOwner()) {
			return back()->withInput($request->all());
		}

		$relation->iban = $request->input('iban');
		$relation->iban_name = $request->input('iban_name');

		$relation->save();

		return back()->with('success', 'Betalingsgegevens zijn aangepast');
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
			/* Adress */
			'street' => array('required','regex:/^[A-Za-z0-9\s]*$/','max:60'),
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

		return back()->with('success', 'Uw bedrijfsgegevens zijn opgeslagen');
	}

	public function doNew(Request $request)
	{
		$rules = array(
			/* General */
			'relationkind' => array('required','numeric'),
			'debtor' => array('required','alpha_num','max:10'),
			/* Company */
			'company_type' => array('required_if:relationkind,1','numeric'),
			'company_name' => array('required_if:relationkind,1','max:50'),
			'email_comp' => array('required_if:relationkind,1','email','max:80'),
			/* Contact */
			'contact_name' => array('required','max:50'),
			'contact_firstname' => array('required','max:30'),
			'email' => array('required','email','max:80'),
			'contactfunction' => array('required','numeric'),
			/* Adress */
			'street' => array('required','regex:/^[A-Za-z0-9\s]*$/','max:60'),
			'address_number' => array('required','alpha_num','max:5'),
			'zipcode' => array('required','size:6'),
			'city' => array('required','alpha_num','max:35'),
			'province' => array('required','numeric'),
			'country' => array('required','numeric'),
		);

		$this->validate($request, $rules);

		/* General */
		$relation = new \Calctool\Models\Relation;
		$relation->user_id = \Auth::id();
		$relation->note = $request->input('note');
		$relation->kind_id = $request->input('relationkind');
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

		if ($request->input('iban'))
			$relation->iban = $request->input('iban');
		if ($request->input('iban_name'))
			$relation->iban_name = $request->input('iban_name');

		$relation->save();

		/* Contact */
		$contact = new \Calctool\Models\Contact;
		$contact->firstname = $request->input('contact_firstname');
		$contact->lastname = $request->input('contact_name');
		$contact->mobile = $request->input('mobile');
		$contact->phone = $request->input('telephone');
		$contact->email = $request->input('email');
		$contact->note = $request->input('note');
		$contact->relation_id = $relation->id;
		if ($relation_kind->kind_name == "zakelijk") {
			$contact->function_id = $request->input('contactfunction');
		} else {
			$contact->function_id = ContactFunction::where('function_name','=','opdrachtgever')->first()->id;
		}
		if ($request->input('gender') == '-1') {
			$contact->gender = NULL;
		} else {
			$contact->gender = $request->input('gender');
		}

		$contact->save();

		return redirect('/relation-'.$relation->id.'/edit')->with('success', 1);
	}

	public function doNewContact(Request $request)
	{
		$this->validate($request, [
			'id' => array('required','integer'),
			'contact_name' => array('required','max:50'),
			'contact_firstname' => array('required','max:30'),
			'email' => array('required','email','max:80'),
		]);

		$relation = \Calctool\Models\Relation::find($request->input('id'));
		if (!$relation || !$relation->isOwner()) {
			return back()->withInput($request->all());
		}

		$contact = new \Calctool\Models\Contact;
		$contact->firstname = $request->input('contact_firstname');
		$contact->lastname = $request->input('contact_name');
		$contact->mobile = $request->input('mobile');
		$contact->phone = $request->input('telephone');
		$contact->email = $request->input('email');
		$contact->note = $request->input('note');
		$contact->relation_id = $relation->id;
		if (\Calctool\Models\RelationKind::find($relation->kind_id)->kind_name=='zakelijk') {
			$contact->function_id = $request->input('contactfunction');
		} else {
			$contact->function_id = ContactFunction::where('function_name','=','opdrachtgever')->first()->id;
		}
		if ($request->input('gender') == '-1') {
			$contact->gender = NULL;
		} else {
			$contact->gender = $request->input('gender');
		}

		$contact->save();

		return redirect('/relation-'.$request->input('id').'/edit')->with('success', 1);
	}

	public function doMyCompanyNewContact(Request $request)
	{
		$this->validate($request, [
			/* Contact */
			'id' => array('required','integer'),
			'contact_name' => array('required','max:50'),
			'contact_firstname' => array('required','max:30'),
			//'mobile' => array('alpha_num','max:14'),
			//'telephone' => array('alpha_num','max:14'),
			'email' => array('required','email','max:80'),
			'contactfunction' => array('required','numeric'),
		]);

			$relation = Relation::find($request->input('id'));
			if (!$relation || !$relation->isOwner()) {
				return Redirect::back()->withInput($request->all());
			}

			$contact = new Contact;
			$contact->firstname = $request->input('contact_firstname');
			$contact->lastname = $request->input('contact_name');
			$contact->mobile = $request->input('mobile');
			$contact->phone = $request->input('telephone');
			$contact->email = $request->input('email');
			$contact->note = $request->input('note');
			$contact->relation_id = $relation->id;
			$contact->function_id = $request->input('contactfunction');
			if ($request->input('gender') == '-1') {
				$contact->gender = NULL;
			} else {
				$contact->gender = $request->input('gender');
			}

			$contact->save();

			return redirect('/mycompany')->with('success', 'Nieuw contact aangemaakt');
	}

	public function doDeleteContact()
	{
		$rules = array(
			'id' => array('required','integer'),
		);

		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::back()->withErrors($validator)->withInput($request->all());
		} else {

			$rec = Contact::find($request->input('id'));
			if (!$rec)
				return Redirect::back()->withInput($request->all());
			$relation = Relation::find($rec->relation_id);
			if (!$relation || !$relation->isOwner()) {
				return Redirect::back()->withInput($request->all());
			}

			$rec->delete();

			return Redirect::back()->with('success', 'Contact verwijderd');
		}
	}

	public function getAll()
	{
		return view('user.relation');
	}

	public function doNewLogo(Request $request)
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

				return back()->with('success', 'Uw logo is geupload');
			} else {

				$messages->add('file', 'Geen afbeelding geupload');

				// redirect our user back to the form with the errors from the validator
				return back()->withErrors($messages);
			}

	}

	public function downloadVCard(Request $request, $relation_id, $contact_id)
	{
		$contact = \Calctool\Models\Contact::find($contact_id);
		if (!$contact) {
			return;
		} else {
			$relation = \Calctool\Models\Relation::find($contact->relation_id);
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
		$vcard->addJobtitle(ucwords(\Calctool\Models\ContactFunction::find($contact->function_id)->function_name));
		$vcard->addEmail($relation->email);
		if ($relation->phone)
			$vcard->addPhoneNumber($relation->phone, 'WORK');
		if ($relation->mobile)
			$vcard->addPhoneNumber($relation->mobile, 'WORK');
		//$vcard->addAddress(null, null, 'street', 'worktown', null, 'workpostcode', 'Belgium');
		//$vcard->addURL('http://www.jeroendesloovere.be');

		// return vcard as a download
		return $vcard->download();
return \Response::make(
    $vcard->getOutput(),
    200,
    $vcard->getHeaders(true)
);


	}
}
