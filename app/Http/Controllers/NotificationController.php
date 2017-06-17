<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the Dynq project.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  Dynq
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
 */

namespace BynqIO\Dynq\Http\Controllers;

use BynqIO\Dynq\Models\MessageBox;
use Illuminate\Http\Request;

use Auth;

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
