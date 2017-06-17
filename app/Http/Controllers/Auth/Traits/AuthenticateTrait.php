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

namespace BynqIO\Dynq\Http\Controllers\Auth\Traits;

use Auth;
use Cache;

trait AuthenticateTrait
{
    private function isBlocked()
    {
        if (Cache::has('blockremote' . $_SERVER['REMOTE_ADDR'])) {
            if (Cache::get('blockremote' . $_SERVER['REMOTE_ADDR']) >= 10) {
                return true;
            }
        }

        return false;
    }

    private function attemptFailed()
    {
        if (Cache::has('blockremote' . $_SERVER['REMOTE_ADDR'])) {
            Cache::increment('blockremote' . $_SERVER['REMOTE_ADDR']);
        } else {
            Cache::put('blockremote' . $_SERVER['REMOTE_ADDR'], 1, 15);
        }
    }

    /**
     * Authenticate user object.
     *
     * @param string   $username
     * @param string   $password
     * @param bool     $rememberme
     * @param string   $redirect
     *
     * @return object
     */
    protected function authenticate($username, $password, $rememberme = false)
    {
        $result = (object) [
            'success' => false,
            'error' => null
        ];

        $username = trim(strtolower($username));
        $usernameAuth = [
            'username' 	=> $username,
            'password' 	=> $password,
            'active' 	=> true,
            'banned' 	=> NULL
        ];
        $emailAuth = [
            'email' 	=> $username,
            'password' 	=> $password,
            'active' 	=> true,
            'banned' 	=> NULL
        ];

        /* Ignore empty requests */
        if (empty($username) || empty($password)) {
            return $result;
        }

        /* Check blocked clients */
        if ($this->isBlocked()) {
            $result->error = __('core.blocked');
            return $result;
        }

        /* Object authentication */
        if (Auth::attempt($usernameAuth, $rememberme) || Auth::attempt($emailAuth, $rememberme)) {

            /* Email must be confirmed */
            if (!Auth::user()->confirmed_mail) {
                Auth::logout();

                $result->error = __('core.notconfirmed');
                return $result;
            }

            $result->success = true;
        } else {
            $this->attemptFailed();
            $result->error = __('core.authfailed');
        }
        
        return $result;
    }

}
