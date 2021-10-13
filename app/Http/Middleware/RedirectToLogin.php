<?php

namespace App\Http\Middleware;

use Closure;

class RedirectToLogin
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
        return redirect( url('/').'/login' );
    }

}
