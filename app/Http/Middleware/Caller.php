<?php

namespace App\Http\Middleware;

use Closure;

class Caller
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
        return Auth::check() && Auth::user()->Role->id == 2 ? $next($request) : redirect()->route('login');
    }
}
