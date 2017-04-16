<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the BynqIO\CalculatieTool.com.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  CalculatieTool
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
 */

namespace BynqIO\CalculatieTool\Http\Controllers\Auth;

use BynqIO\CalculatieTool\Models\User;
use BynqIO\CalculatieTool\Models\Audit;
use BynqIO\CalculatieTool\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use Mail;
use Auth;
use Hash;

class PasswordResetController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * Instantiate a new password reset controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');

        //
    }

    /**
     * Retrieve password reset view.
     *
     * @return Route
     */
    public function index($token)
    {
        try {
            User::where('reset_token', $token)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->route('signin')
                ->withErrors(__('core.invresetlink'));
        }

        return view('auth.password');
    }

    /**
     * Enter new password in the reset form.
     *
     * @return Route
     */
    public function submitNewPassword(Request $request, $token)
    {
        $this->validate($request, [
            'secret' => array('required','confirmed','min:5'),
            'secret_confirmation' => array('required','min:5'),
        ]);

        try {
            $user = User::where('reset_token', $token)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->route('signin')
                ->withErrors(__('core.invresetlink'));
        }

        $user->secret = Hash::make($request->get('secret'));
        $user->reset_token = null;
        $user->save();

        Audit::CreateEvent('auth.update.password.success', 'Updated with: ' . Audit::UserAgent(), $user->id);

        Auth::login($user);
        return redirect()->route('dashboard')->with('success', __('core.passresetok'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Route
     */
    public function requestPasswordReset(Request $request)
    {
        $this->validate($request, [
            'email' => array('required','max:80','email')
        ]);

        try {
            $user = User::where('email', $request->get('email'))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->route('signin')
                ->with('success', __('core.passresetsent'));
        }

        $user->reset_token = sha1(mt_rand());
        $user->save();

        $data = [
            'email' => $user->email,
            'token' => $user->reset_token,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname
        ];
        Mail::send('mail.password', $data, function($message) use ($data) {
            $message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
            $message->subject('CalculatieTool.com - Wachtwoord herstellen');
            $message->from('info@calculatietool.com', 'CalculatieTool.com');
            $message->replyTo('support@calculatietool.com', 'CalculatieTool.com');
        });

        Audit::CreateEvent('auth.reset.password.mail.success', 'Reset with: ' . Audit::UserAgent(), $user->id);

        return redirect()->route('signin')->with('success', __('core.passresetsent'));
    }

}
