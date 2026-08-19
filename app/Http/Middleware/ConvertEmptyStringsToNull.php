<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull as Middleware;

class ConvertEmptyStringsToNull extends Middleware
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
