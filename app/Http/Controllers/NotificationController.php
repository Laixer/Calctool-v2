<?php

namespace BynqIO\CalculatieTool\Http\Controllers;

use Illuminate\Http\Request;

use \BynqIO\CalculatieTool\Models\MessageBox;

use \Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /relation
     *
     * @return Response
     */
    public function notificationList(Request $request)
    {
        return view('user.notification');
    }

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

        $message->read = date('Y-m-d');

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

        return redirect('/notification')->with('success', 'Bericht gelezen');
    }


}
