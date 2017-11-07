<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Core\Auth;
use \Redirect;
use \Core\Session;
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
        
        // if (!Session::get('user_logged_in') || !Session::get('ib_uid')) {
        //     return Redirect::to(\Config::get('app.url'));
        // }

        return $next($request);
    }
}
