<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Core\Auth;
use \Redirect;
use \Core\Session;
class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'admin')
    {
        
        if (Auth::guard($guard)->check()) {
           return  \Redirect::to(\Config::get('app.url').'admin/dashboard');
        }

        return $next($request);
    }
}
