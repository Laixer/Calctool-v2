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
        // $data = array(
        //     'name' => $request->get('name'),
        //     'feedback_message' => $request->get('message'),
        //     'user' => 'anoniem',
        //     'remote' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown',
        //     'agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown',
        // );

        // if (Auth::check()) {
        //     $data['user'] = Auth::user()->username;
        // }

        // Mailgun::send('mail.feedback', $data, function($message) use ($data) {
        //     $message->to('info@calculatietool.com', 'CalculatieTool.com');
        //     $message->subject('CalculatieTool.com - Feeback');
        // });

        return response()->json(['success' => 0]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendSupport(Request $request)
    {
        $this->validate($request, [
            'name' => array('required','max:100'),
            'email' => array('required','email'),
        ]);

        $data = array(
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'subject' => $request->get('subject'),
            'category' => $request->get('category'),
            'feedback_message' => $request->get('message'),
            'user' => 'anoniem',
            'remote' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown',
            'agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown',
        );

        if (Auth::check()) {
            $data['user'] = Auth::user()->username;
        }

        Mailgun::send('mail.feedback', $data, function($message) use ($data) {
            $message->to('info@calculatietool.com', 'CalculatieTool.com');
            $message->bcc($data['email'], $data['name']);
            $message->subject('CalculatieTool.com - Contact form');
        });

        return back()->with('success', 'Bericht en kopie verstuurd');
    }
}
