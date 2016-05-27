<?php

namespace Calctool\Http\Controllers;

use Illuminate\Http\Request;

use Calctool\Http\Requests;
use Calctool\Http\Controllers\Controller;

use \Auth;
use \Mailgun;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        $data = array(
            'name' => $request->get('name'),
            'feedback_message' => $request->get('message'),
            'user' => 'anoniem',
            'remote' => $_SERVER['REMOTE_ADDR'],
            'agent' => $_SERVER['HTTP_USER_AGENT'],
        );

        if (Auth::check()) {
            $data['user'] = Auth::user()->username;
        }

        Mailgun::send('mail.feedback', $data, function($message) use ($data) {
            $message->to('info@calculatietool.com', 'CalculatieTool.com');
            $message->subject('CalculatieTool.com - Feeback');
        });

        return response()->json(['success' => 1]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendSupport(Request $request)
    {
        $data = array(
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'subject' => $request->get('subject'),
            'feedback_message' => $request->get('message'),
            'user' => 'anoniem',
            'remote' => $_SERVER['REMOTE_ADDR'],
            'agent' => $_SERVER['HTTP_USER_AGENT'],
        );

        if (Auth::check()) {
            $data['user'] = Auth::user()->username;
        }

        Mailgun::send('mail.feedback', $data, function($message) use ($data) {
            $message->to('info@calculatietool.com', 'CalculatieTool.com');
            $message->subject('CalculatieTool.com - Contact form');
        });

        return back()->with('success', 'Bericht verstuurd');
    }
}
