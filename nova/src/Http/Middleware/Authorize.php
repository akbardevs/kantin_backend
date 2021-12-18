<?php

namespace Laravel\Nova\Http\Middleware;

use Laravel\Nova\Nova;
use Illuminate\Support\Facades\Auth;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        if (Auth::user()->role != 'ADMIN') {
            Auth::logout();
        }
        if (Nova::check($request)) {
            return $next($request);
        }

        return $next($request);
    }
}
