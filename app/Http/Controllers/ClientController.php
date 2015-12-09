<?php

namespace Calctool\Http\Controllers;

use Illuminate\Http\Request;

use \Calctool\Models\ProjectShare;

use \Auth;

class ClientController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */

	public function doUpdateCommunication(Request $request, $token)
	{

		$project_share = ProjectShare::where('token', $token)->first();
		if (! $project_share) {
			return back();
		}

		$project_share->client_note = $request->input('client_note');
		
		$project_share->save();

		return back();
	}

}
