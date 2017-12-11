<?php

namespace App\Http\Middleware\User;

use Closure;
use Core\Auth;
use \Redirect;
class RedirectIfNotAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'user')
    {
        if (!Auth::guard($guard)->check()) {
            return Redirect::to(\Config::get('app.url'));
        }

        return $next($request);
    }
}
