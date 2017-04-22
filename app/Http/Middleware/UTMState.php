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

namespace BynqIO\CalculatieTool\Http\Middleware;

use Closure;

class UTMState
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /* Save referrer on firsts request */
        $referrer = $request->server('HTTP_REFERER');
        if ($referrer) {
            if (!$request->session()->has('referrer')) {
                $request->session()->put('referrer', $referrer);
            }
        }

        /* Save campaign info */
        if ($request->has('utmcampaign')) {
            if (!$request->session()->has('utmcampaign')) {
                $request->session()->put('utmcampaign', $request->get('utmcampaign'));
            }
        }

        return $next($request);
    }
}
