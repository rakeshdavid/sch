<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Coach
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
//        if(Auth::check() && Auth::user()->isCoach()) {
//            return $next($request);
//        }
//
//        return redirect('/');
        return $next($request);
    }
}
