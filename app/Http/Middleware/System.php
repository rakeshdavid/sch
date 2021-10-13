<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Setting;
class System
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
        $platform = detectPlatform();
        if($platform[0] == env('USER_PLATFORM') && Setting::checkPlatformMaintenanceMode()) {
            return response(view('pages.maintenance', ['platform'=>'user']));
        }
        if($platform[0] == env('COACH_PLATFORM') && Setting::checkCoachesMaintenanceMode()) {
            return response(view('pages.maintenance', ['platform'=>'coach']));
        }

        return $next($request);
    }
}
