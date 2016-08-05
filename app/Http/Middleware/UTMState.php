<?php

namespace Calctool\Http\Middleware;

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
        $referrer = $request->server('HTTP_REFERER');
        if ($referrer) {
            if (!$request->session()->has('referrer')) {
                $request->session()->put('referrer', $referrer);
            }
        }

        return $next($request);
    }
}
