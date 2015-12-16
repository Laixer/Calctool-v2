<?php

namespace Calctool\Http\Controllers;

use Illuminate\Http\Request;

use \Calctool\Models\MessageBox;

use \Auth;

class MessageBoxController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /relation
	 *
	 * @return Response
	 */

	public function getMessage(Request $request, $message_id)
	{
		/* General */
		$message = MessageBox::find($message_id);
		if (!$message || !$message->isOwner())
			return back();

		$message->read = date('Y-m-d');

		$message->save();

		return view('user.message');
	}

	public function doRead(Request $request, $message_id)
	{
		/* General */
		$message = MessageBox::find($message_id);
		if (!$message || !$message->isOwner())
			return back();

		$message->read = date('d-m-Y');

		$message->save();

		return back()->with('success', 'Bericht gelezen');
	}

	public function doDelete(Request $request, $message_id)
	{
		/* General */
		$message = MessageBox::find($message_id);
		if (!$message || !$message->isOwner())
			return back();

		$message->active = false;

		$message->save();

		return redirect('/messagebox')->with('success', 'Bericht gelezen');
	}


}
