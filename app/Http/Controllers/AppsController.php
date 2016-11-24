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

			$row = 0;
			if (($handle = fopen($file->getRealPath(), "r")) !== FALSE) {
				while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
					if ($row++ == 0)
						continue;
					
					if (count($data)<22)
						continue;

					if (empty($data[1]) || empty($data[2]) || empty($data[3]) || empty($data[4]) )
						continue;
					if (empty($data[9]))
						continue;
					if (empty($data[17]) || empty($data[20]))
						continue;

					/* General */
					$relation = new Relation;
					$relation->user_id = Auth::id();
					$relation->note = $data[10];
					$relation->kind_id = $kind_particulier;
					$relation->debtor_code = $data[7];

					/* Company */
					if (strtolower($data[14] == 'zakelijk')) {
						$relation->kind_id = $kind_zakelijk;
						$relation->company_name = $data[0];
						$relation->type_id = $type_id;
						$relation->kvk = $data[5];
						$relation->btw = $data[6];
						$relation->phone = $data[8];
						$relation->email = $data[9];
						$relation->website = $data[11];
					}

					/* Adress */
					$relation->address_street = $data[1];
					$relation->address_number = $data[2];
					$relation->address_postal = $data[3];
					$relation->address_city = $data[4];
					$relation->province_id = $province_id;
					$relation->country_id = $country_id;

					$relation->iban = $data[12];
					$relation->iban_name = $data[13];

					$relation->save();

					/* Contact */
					$contact = new Contact;
					$contact->firstname = $data[16];
					$contact->lastname = $data[17];
					$contact->mobile = $data[18];
					$contact->phone = $data[19];
					$contact->email = $data[20];
					$contact->relation_id = $relation->id;
					
					if (strtolower($data[14] == 'zakelijk')) {
						$contact->function_id = $function_directeur;
					} else {
						$contact->function_id = $function_opdrachtgever;
					}
					
					if (strtolower($data[21]) == 'man')
						$contact->gender = 'M';
					if (strtolower($data[21]) == 'vrouw')
						$contact->gender = 'V';

					$contact->save();

				}
				fclose($handle);
			}
			return back()->with('success', 'Relatiebestand geimporteerd');
		} else {
			return back()->withErrors('Geen CSV geupload');
		}

	}

}
