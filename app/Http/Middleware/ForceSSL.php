<?php

namespace App\Http\Middleware;

use Closure;

class ForceSSL
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
        if (env('FORCE_SSL', true)) {
            if(auth()->user()) {
                if (auth()->user()->isUser() && (request()->getHost() != env('USER_PLATFORM_DOMAIN'))) {
                    return redirect(env('USER_PLATFORM_LINK'));
                } elseif (auth()->user()->isCoach() && (request()->getHost() != env('COACH_PLATFORM_DOMAIN'))) {
                    return redirect(env('COACH_PLATFORM_LINK'));
                } elseif (auth()->user()->isAdmin() && (request()->getHost() != env('ADMIN_PLATFORM_DOMAIN'))) {
                    return redirect(env('ADMIN_PLATFORM_LINK'));
                }
            }

            if (!$request->secure()) {
                return redirect()->secure($request->getRequestUri());
            }
        }
        return $next($request);
    }
    
}
