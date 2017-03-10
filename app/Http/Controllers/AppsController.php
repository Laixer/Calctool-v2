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

			$row = 0; $success = 0; $skip = 0;
			if (($handle = fopen($file->getRealPath(), "r")) !== FALSE) {

				$line = fgets($handle);

				$delimiter = ";";
				if (count(explode(",", $line)) == 21)
					$delimiter = ",";

				rewind($handle);
				while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
					if ($row++ == 0) {
						if (strtolower($data[0]) != 'bedrijfsnaam')
							return back()->withErrors('Bestand heeft verkeerde indeling');

						if (strlen($data[14]) > 0)
							return back()->withErrors('Bestand heeft verkeerde indeling');

						if (strtolower($data[20]) != 'geslacht')
							return back()->withErrors('Bestand heeft verkeerde indeling');
						continue;
					}

					if (count($data) != 21) {
						$skip++;
						continue;
					}

					if (strlen($data[14]) > 0) {
						$skip++;
						continue;
					}

					/* Convert to params */
					$company_name = trim($data[0]);
					$address_street = $data[1];
					$address_number = trim($data[2]);
					$address_postal = trim($data[3]);
					$address_city = trim($data[4]);
					$kvk = trim($data[5]);
					$btw = trim($data[6]);
					$debtor = trim($data[7]);
					$phone_comp = trim($data[8]);
					$email_comp = trim($data[9]);
					$note = $data[10];
					$website = trim($data[11]);
					$iban = trim($data[12]);
					$iban_name = trim($data[13]);

					$firstname = trim($data[15]);
					$lastname = trim($data[16]);
					$mobile = trim($data[17]);
					$phone = trim($data[18]);
					$email = trim($data[19]);
					$gender = strtolower(trim($data[20]));

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
						'address_street' => array('required','max:60'),
						'address_number' => array('required','alpha_num','max:5'),
						'address_postal' => array('required','size:6'),
						'address_city' => array('required','max:35'),
						'phone' => array('max:12'),
						'mobile' => array('max:12'),
						'website' => array('max:180'),
					]);

					if ($validator->fails()) {
						$skip++;
						continue;
					}

					/* General */
					$relation = new Relation;
					$relation->user_id = Auth::id();
					$relation->note = $note;
					$relation->kind_id = $kind_particulier;
					$relation->debtor_code = $debtor;

					/* Company */
					if (!empty($company_name)) {
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

					if (!empty($company_name)) {
						$contact->function_id = $function_directeur;
					} else {
						$contact->function_id = $function_opdrachtgever;
					}

					if (!empty($gender)) {
						if ($gender == 'man' || $gender[0] == 'm')
							$contact->gender = 'M';
						if ($gender == 'vrouw' || $gender[0] == 'v' || $gender[0] == 'f')
							$contact->gender = 'V';
					}

					$contact->save();
					$success++;
				}
				fclose($handle);
			}
			return back()->with('success', $success . ' relaties geimporteerd, ' . $skip . ' overgeslagen');
		} else {
			return back()->withErrors('Geen CSV geupload');
		}

	}

	public function getExportRelation(Request $request)
	{
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="export.csv"');
		$fp = fopen('php://output', 'w');

		$header = ['Bedrijfsnaam','Straat','Nummer','Postcode','Plaats','KVK','BTWnummer','Debiteurnummer','Telefoon','email','Notitie','Website','Iban','Naam Iban houder','Type relatie','','Voornaam','Achternaam','Mobiel','Telefoon','Email','Geslacht'];

		fputcsv($fp, $header, ";");

		$relations = Relation::where('user_id',Auth::id())->where('active',true)->orderBy('created_at', 'desc')->get();
		foreach ($relations as $relation) {
			$contact = Contact::where('relation_id',$relation->id)->first();

			$row = [];
			array_push($row, $relation->company_name ? $relation->company_name : $contact->firstname . ' '. $contact->lastname);
			array_push($row, $relation->address_street);
			array_push($row, $relation->address_number);
			array_push($row, $relation->address_postal);
			array_push($row, $relation->address_city);
			array_push($row, $relation->kvk);
			array_push($row, $relation->btw);
			array_push($row, $relation->debtor);
			array_push($row, $relation->phone_comp);
			array_push($row, $relation->email_comp);
			array_push($row, $relation->note);
			array_push($row, $relation->website);
			array_push($row, $relation->iban);
			array_push($row, $relation->iban_name);
			array_push($row, ucfirst(RelationKind::find($relation->kind_id)->kind_name));
			array_push($row, '');
			array_push($row, $contact->firstname);
			array_push($row, $contact->lastname);
			array_push($row, $contact->mobile);
			array_push($row, $contact->phone);
			array_push($row, $contact->email);
			array_push($row, $contact->gender);

			fputcsv($fp, $row, ";");
		}
		fclose($fp);
	}
}
