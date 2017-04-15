<?php

namespace BynqIO\CalculatieTool\Http\Controllers;

use Illuminate\Http\Request;

use BynqIO\CalculatieTool\Http\Requests;
use BynqIO\CalculatieTool\Http\Controllers\Controller;
use BynqIO\CalculatieTool\Jobs\SendSupportMail;

use \Auth;
use \Mail;

class SupportController extends Controller
{
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

        dispatch(new SendSupportMail(
            $request->get('name'),
            $request->get('email'),
            $request->get('subject'),
            $request->get('category'),
            $request->get('message')
        ));

        return back()->with('success', 'Bericht en kopie verstuurd');
    }

    /**
     * Return support view.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSupport(Request $request)
    {
        $user = null;
        if (Auth::check())
            $user = Auth::user();

        return view('generic.support', ['user' => $user]);
    }

}
