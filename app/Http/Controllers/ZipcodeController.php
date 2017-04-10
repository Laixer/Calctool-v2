<?php

namespace CalculatieTool\Http\Controllers;

use Illuminate\Http\Request;
use \CalculatieTool\Models\Province;

use \Auth;
use \Validator;
use \Cache;
use \Cookie;

class ZipcodeController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */

	public function getExternalAddress(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'zipcode' => array('required','size:6'),
			]);

		if ($validator->fails()) {
			return;
		}

		if (Cache::store('file')->has($request->get('zipcode') . $request->get('number'))) {
			$address = Cache::store('file')->get($request->get('zipcode') . $request->get('number'));
			$address['cache'] = true;
			return response()->json($address);
		}

		$headers = array();
		$headers[] = 'X-Api-Key: ' . config('services.postcode.key');

		$url = 'https://postcode-api.apiwise.nl/v2/addresses/?postcode=' . $request->get('zipcode') . '&number=' . $request->get('number');
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		$response = curl_exec($curl);
		if (curl_error($curl))
			return;

		$data = json_decode($response);
		curl_close($curl);
		if (empty($data)) {
			return;
		}

		if (!property_exists($data, '_embedded'))
			return;

		if (count($data->_embedded->addresses) == 1) {
			$address['postcode'] = $data->_embedded->addresses[0]->postcode;
			$address['street'] = $data->_embedded->addresses[0]->street;
			$address['number'] = $data->_embedded->addresses[0]->number;
			$address['province'] = $data->_embedded->addresses[0]->province->label;
			$address['province_id'] = Province::where('province_name', strtolower($data->_embedded->addresses[0]->province->label))->first()['id'];
			$address['city'] = $data->_embedded->addresses[0]->city->label;

			Cache::store('file')->put($request->get('zipcode') . $request->get('number'), $address, 43800);

			return response()->json($address);
		}

		return;
	}

}
