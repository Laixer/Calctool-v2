<?php

namespace Calctool\Http\Middleware;

use Closure;
use Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            return redirect('/login');
        }

        if (!Auth::guard($guard)->user()->isAdmin()) {
            return redirect('/');
        }

        return $next($request);
    }
}