<?php

namespace App\Http\Middleware;

use Closure;

class TrimStrings
{
    protected $except = [
        'password',
        'password_confirmation',
    ];

    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
