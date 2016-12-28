<?php

namespace Calctool\Http\Controllers;

use Illuminate\Http\Request;

use \Calctool\Models\Relation;
use \Calctool\Models\RelationKind;
use \Calctool\Models\RelationType;
use \Calctool\Models\Contact;
use \Calctool\Models\Province;
use \Calctool\Models\Country;
use \Calctool\Models\ContactFunction;

use \Auth;
use \Cookie;
use \Validator;

class AppsController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */

	public function getAppsDashboard()
	{
		return view('base.apps');
	}

	public function doImportRelation(Request $request)
	{
		$this->validate($request, [
			'csvfile' => array('required'),
		]);

		if ($request->hasFile('csvfile')) {
			$file = $request->file('csvfile');
			
			$kind_zakelijk = RelationKind::where('kind_name','zakelijk')->first()->id;
			$kind_particulier = RelationKind::where('kind_name','particulier')->first()->id;
			$type_id = RelationType::where('type_name','aannemer')->first()->id;

			$province_id = Province::where('province_name','overig')->first()->id;
			$country_id = Country::where('country_name','nederland')->first()->id;

			$function_directeur = ContactFunction::where('function_name','directeur')->first()->id;
			$function_opdrachtgever = ContactFunction::where('function_name','opdrachtgever')->first()->id;

			$row = 0; $success = 0;
			if (($handle = fopen($file->getRealPath(), "r")) !== FALSE) {
				while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
					if ($row++ == 0)
						continue;
					
					if (count($data) < 22)
						continue;

					/* Convert to params */
					$company_name = $data[0];
					$address_street = $data[1];
					$address_number = $data[2];
					$address_postal = $data[3];
					$address_city = $data[4];
					$kvk = $data[5];
					$btw = $data[6];
					$debtor = $data[7];
					$phone_comp = $data[8];
					$email_comp = $data[9];
					$note = $data[10];
					$website = $data[11];
					$iban = $data[12];
					$iban_name = $data[13];
					$kind = strtolower($data[14]);

					$firstname = $data[16];
					$lastname = $data[17];
					$mobile = $data[18];
					$phone = $data[19];
					$email = $data[20];
					$gender = strtolower($data[21]);

					/* Fixes */
					if (empty($debtor))
						$debtor = mt_rand(1000000, 9999999);
					if (empty($address_street))
						$address_street = 'onbekend';
					if (empty($address_number))
						$address_number = 0;
					if (empty($address_postal))
						$address_postal = '0000AA';
					if (empty($address_city))
						$address_city = 'onbekend';
					if (empty($email_comp))
						$email = 'onbekend@calculatietool.com';
					if (empty($lastname))
						$lastname = 'onbekend';
					if (empty($email))
						$email = 'onbekend@calculatietool.com';

					$input = compact(
						'debtor',
						'company_name',
						'email_comp',
						'lastname',
						'firstname',
						'email',
						'address_street',
						'address_number',
						'address_postal',
						'address_city',
						'phone',
						'mobile',
						'website'
					);

					$validator = Validator::make($input, [
						'debtor' => array('required','alpha_num','max:10'),
						'company_name' => array('max:50'),
						'email_comp' => array('email','max:80'),
						// 'contact_salutation' => array('max:16'),
						'lastname' => array('required','max:50'),
						'firstname' => array('max:30'),
						'email' => array('required','email','max:80'),
						// 'contactfunction' => array('required','numeric'),
						'address_street' => array('required','max:60'),
						'address_number' => array('required','alpha_num','max:5'),
						'address_postal' => array('required','size:6'),
						'address_city' => array('required','max:35'),
						// 'province' => array('required','numeric'),
						// 'country' => array('required','numeric'),
						'phone' => array('max:12'),
						'mobile' => array('max:12'),
						'website' => array('max:180'),
					]);

					if ($validator->fails())
						continue;
						// return back()->withErrors($validator);

					/* General */
					$relation = new Relation;
					$relation->user_id = Auth::id();
					$relation->note = $note;
					$relation->kind_id = $kind_particulier;
					$relation->debtor_code = $debtor;

					/* Company */
					if ($kind == 'zakelijk' || $kind[0] == 'z') {
						$relation->kind_id = $kind_zakelijk;
						$relation->company_name = $company_name;
						$relation->type_id = $type_id;
						$relation->kvk = $kvk;
						$relation->btw = $btw;
						$relation->phone = $phone_comp;
						$relation->email = $email_comp;
						$relation->website = $website;
					}

					/* Adress */
					$relation->address_street = $address_street;
					$relation->address_number = $address_number;
					$relation->address_postal = $address_postal;
					$relation->address_city = $address_city;
					$relation->province_id = $province_id;
					$relation->country_id = $country_id;

					$relation->iban = $iban;
					$relation->iban_name = $iban_name;

					$relation->save();

					/* Contact */
					$contact = new Contact;
					$contact->firstname = $firstname;
					$contact->lastname = $lastname;
					$contact->mobile = $mobile;
					$contact->phone = $phone;
					$contact->email = $email;
					$contact->relation_id = $relation->id;
					
					if ($kind == 'zakelijk' || $kind[0] == 'z') {
						$contact->function_id = $function_directeur;
					} else {
						$contact->function_id = $function_opdrachtgever;
					}
					
					if ($gender == 'man' || $gender[0] == 'm')
						$contact->gender = 'M';
					if ($gender == 'vrouw' || $gender[0] == 'v' || $gender[0] == 'f')
						$contact->gender = 'V';

					$contact->save();
					$success++;
				}
				fclose($handle);
			}
			return back()->with('success', $success . ' relaties geimporteerd');
		} else {
			return back()->withErrors('Geen CSV geupload');
		}

	}

}
