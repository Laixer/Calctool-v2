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

use BynqIO\CalculatieTool\Models\Audit;
use BynqIO\CalculatieTool\Http\Controllers\Controller;
use BynqIO\CalculatieTool\Http\Controllers\Auth\Traits\AuthenticateTrait;
use Illuminate\Http\Request;

use Auth;
use Cache;

class SigninController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Signin Controller
    |--------------------------------------------------------------------------
    |
    |
    */

    use AuthenticateTrait;

    /**
     * Instantiate a new signin controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('utm');

        //
    }

    private function urlAuthentiction($token)
    {
        $auth = explode(":", base64_decode($token));
        if (count($auth) == 2) {
            $result = $this->authenticate($auth[0], $auth[1]);

            /* Authentication result */
            if (!$result->success) {
                return redirect()
                    ->route('signin')
                    ->withErrors($result->error);
            }

            /* Redirect system to admin control panel */
            if (Auth::user()->isSystem()) {
                return redirect()->route('adminDashboard');
            }

            return redirect()->route('dashboard');
        }
    }

    protected function safeRedirect($url)
    {
        $parts = parse_url($url);
        if (isset($parts['path'])) {
            return secure_url($parts['path']);
        }
    }

    /**
     * Retrieve login view.
     *
     * @return Route
     */
    public function index(Request $request)
    {
        $repsonse = view('auth.signin');

        if ($request->has('delblock')) {
            Cache::forget('blockremote' . $request->ip());
        }

        if ($request->has('dauth')) {
            $repsonse = $this->urlAuthentiction($request->get('dauth'));
        }

        return $repsonse;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Route
     */
    public function signin(Request $request)
    {
        $result = $this->authenticate(
            $request->input('username'),
            $request->input('secret'),
            $request->input('rememberme') ? true : false);

        /* Authentication result */
        if (!$result->success) {
            return redirect()
                ->route('signin')
                ->withErrors($result->error)
                ->withInput($request->except('secret'));
        }

        /* Redirect system to admin control panel */
        if (Auth::user()->isSystem()) {
            return redirect()->route('adminDashboard');
        }

        /* Redirect user */
        if ($request->has('redirect')) {
            $redirect = $this->safeRedirect(urldecode($request->get('redirect')));
            if ($redirect) {
                return redirect($redirect);
            }
        }

        return redirect()->route('dashboard');
    }

}
