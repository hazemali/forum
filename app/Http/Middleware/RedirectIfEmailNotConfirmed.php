<?php

namespace laravel\Http\Middleware;

use Closure;

class RedirectIfEmailNotConfirmed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->user()->confirmed) {
            return redirect('/threads')->with('flashError', 'you must confirm your email address first.');
        }
        return $next($request);
    }
}
