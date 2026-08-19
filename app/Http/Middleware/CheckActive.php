<?php

namespace App\Http\Middleware;

use Closure;

class CheckActive
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && !auth()->user()->is_active) {
            auth()->logout();
            return redirect('/login')->with('error', 'Your account has been deactivated.');
        }

        return $next($request);
    }
}
