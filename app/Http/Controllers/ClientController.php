<?php

namespace Calctool\Http\Controllers;

use Illuminate\Http\Request;

use \Calctool\Models\User;
use \Calctool\Models\MessageBox;
use \Calctool\Models\Project;
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
		if (!$project_share) {
			return back();
		}
		$project = Project::find($project_share->project_id);

		$project_share->client_note = $request->input('client_note');
		
		$project_share->save();

		$message = new MessageBox;
		$message->subject = 'Opdrachtgever heeft gereageerd';
		$message->message = 'De opdrachtgever heeft de volgende opmerking geplaatst bij project <a href="/project-' . $project->id . '/edit">' . $project->project_name . '</a>:<br /><br />' . nl2br($request->input('client_note'));
		$message->from_user = User::where('username', 'system')->first()['id'];
		$message->user_id =	$project->user_id;

		$message->save();

		return back()->with('success', 'Opmerking toegevoegd aan project');
	}

}
