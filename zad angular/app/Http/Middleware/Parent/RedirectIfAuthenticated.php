<?php

namespace App\Http\Middleware\Parent;

use Closure;
use Auth;
class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'parent')
    {
        if (Auth::guard($guard)->check()) {
            return redirect()->route('parent.dashboard');
        }
        return $next($request);
    }
}
